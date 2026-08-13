<?php
/**
 * Backloggd Now fetcher — latest public review from /reviews/.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @var int Transient TTL (seconds). */
const PEDRO_RIBEIRO_NOW_BACKLOGGD_TTL = 300;

/** @var int HTTP timeout (seconds). */
const PEDRO_RIBEIRO_NOW_BACKLOGGD_TIMEOUT = 4;

/**
 * Fetch the latest public Backloggd review for a username.
 * Silent fail: any HTTP/parse/empty result → null.
 *
 * @param string $username Backloggd username.
 * @return array{title: string, review: string, image_url?: string, rating?: float, url?: string}|null
 */
function pedro_ribeiro_fetch_backloggd_now( $username ) {
	$username = is_string( $username ) ? trim( $username ) : '';
	if ( $username === '' ) {
		return null;
	}

	// Keep path-safe without altering common username chars (_ . -).
	$slug = preg_replace( '/[^A-Za-z0-9._-]/', '', $username );
	if ( ! is_string( $slug ) || $slug === '' ) {
		return null;
	}

	$cache_key = 'pr_now_backloggd_' . md5( strtolower( $slug ) );
	$cached    = get_transient( $cache_key );
	if ( is_array( $cached ) && array_key_exists( 'item', $cached ) ) {
		return $cached['item'];
	}

	$url = 'https://www.backloggd.com/u/' . rawurlencode( $slug ) . '/reviews/';

	$response = wp_remote_get(
		$url,
		array(
			'timeout'     => PEDRO_RIBEIRO_NOW_BACKLOGGD_TIMEOUT,
			'redirection' => 3,
			'headers'     => array(
				'Accept'     => 'text/html,application/xhtml+xml',
				'User-Agent' => 'Mozilla/5.0 (compatible; PedroRibeiroPortfolio/1.0)',
			),
		)
	);

	$item = pedro_ribeiro_parse_backloggd_review( $response );
	set_transient( $cache_key, array( 'item' => $item ), PEDRO_RIBEIRO_NOW_BACKLOGGD_TTL );

	return $item;
}

/**
 * Parse Backloggd reviews-page HTML for the first .review-card.
 *
 * @param array|WP_Error $response wp_remote_get result.
 * @return array{title: string, review: string, image_url?: string, rating?: float, url?: string}|null
 */
function pedro_ribeiro_parse_backloggd_review( $response ) {
	if ( is_wp_error( $response ) ) {
		return null;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return null;
	}

	$html = (string) wp_remote_retrieve_body( $response );
	if ( $html === '' || stripos( $html, 'review-card' ) === false ) {
		return null;
	}

	if ( ! class_exists( 'DOMDocument' ) ) {
		return null;
	}

	$previous = libxml_use_internal_errors( true );
	$dom      = new DOMDocument();
	$loaded   = $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $html );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	if ( ! $loaded ) {
		return null;
	}

	$xpath = new DOMXPath( $dom );
	$nodes = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " review-card ")]' );
	if ( ! $nodes || $nodes->length === 0 ) {
		return null;
	}

	$card = $nodes->item( 0 );
	if ( ! $card instanceof DOMElement ) {
		return null;
	}

	$title     = '';
	$image_url = '';

	$img_nodes = $xpath->query( './/img[@src]', $card );
	if ( $img_nodes && $img_nodes->length > 0 ) {
		$img = $img_nodes->item( 0 );
		if ( $img instanceof DOMElement ) {
			$image_url = trim( (string) $img->getAttribute( 'src' ) );
			$title     = trim( (string) $img->getAttribute( 'alt' ) );
		}
	}

	if ( $title === '' ) {
		$title_nodes = $xpath->query(
			'.//*[contains(concat(" ", normalize-space(@class), " "), " game-name ") or contains(concat(" ", normalize-space(@class), " "), " review-game ") or self::h3 or self::h2]',
			$card
		);
		if ( $title_nodes && $title_nodes->length > 0 ) {
			$title = trim( preg_replace( '/\s+/u', ' ', $title_nodes->item( 0 )->textContent ?? '' ) );
		}
	}

	$review = pedro_ribeiro_backloggd_extract_review_text( $xpath, $card );
	if ( $title === '' || $review === '' ) {
		return null;
	}

	$item = array(
		'title'  => $title,
		'review' => $review,
	);

	if ( $image_url !== '' ) {
		$item['image_url'] = pedro_ribeiro_backloggd_absolute_url( $image_url );
	}

	$rating = pedro_ribeiro_backloggd_extract_rating( $xpath, $card );
	if ( null !== $rating ) {
		$item['rating'] = $rating;
	}

	$url = pedro_ribeiro_backloggd_extract_url( $xpath, $card );
	if ( $url ) {
		$item['url'] = $url;
	}

	return $item;
}

/**
 * Extract plain review body from a review-card.
 *
 * @param DOMXPath   $xpath XPath helper.
 * @param DOMElement $card  Review card node.
 * @return string
 */
function pedro_ribeiro_backloggd_extract_review_text( DOMXPath $xpath, DOMElement $card ) {
	$candidates = $xpath->query(
		'.//*[contains(concat(" ", normalize-space(@class), " "), " review-body ") or contains(concat(" ", normalize-space(@class), " "), " review-text ") or contains(concat(" ", normalize-space(@class), " "), " body-text ") or contains(concat(" ", normalize-space(@class), " "), " formatted-text ")]',
		$card
	);

	$text = '';
	if ( $candidates && $candidates->length > 0 ) {
		$text = $candidates->item( 0 )->textContent ?? '';
	} else {
		// Fallback: card text minus obvious chrome (game title already captured separately).
		$text = $card->textContent ?? '';
	}

	$text = wp_strip_all_tags( (string) $text );
	$text = trim( preg_replace( '/\s+/u', ' ', $text ) );

	return $text;
}

/**
 * Derive 0–5 rating from .stars-top width percentage.
 *
 * @param DOMXPath   $xpath XPath helper.
 * @param DOMElement $card  Review card node.
 * @return float|null
 */
function pedro_ribeiro_backloggd_extract_rating( DOMXPath $xpath, DOMElement $card ) {
	$stars = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " stars-top ")]', $card );
	if ( ! $stars || $stars->length === 0 ) {
		return null;
	}

	$el = $stars->item( 0 );
	if ( ! $el instanceof DOMElement ) {
		return null;
	}

	$style = (string) $el->getAttribute( 'style' );
	if ( ! preg_match( '/width\s*:\s*([0-9.]+)\s*%/i', $style, $m ) ) {
		return null;
	}

	$percent = (float) $m[1];
	if ( $percent < 0 || $percent > 100 ) {
		return null;
	}

	// 60% → 3/5.
	return round( ( $percent / 100 ) * 5, 1 );
}

/**
 * Prefer a clean review URL (/review/{id}/), then game URL.
 * Avoids /likes/, /comments/, etc. that appear first in card markup.
 *
 * @param DOMXPath   $xpath XPath helper.
 * @param DOMElement $card  Review card node.
 * @return string|null
 */
function pedro_ribeiro_backloggd_extract_url( DOMXPath $xpath, DOMElement $card ) {
	$links = $xpath->query( './/a[@href]', $card );
	if ( ! $links || $links->length === 0 ) {
		return null;
	}

	$review_url = null;
	$game_url   = null;

	for ( $i = 0; $i < $links->length; $i++ ) {
		$link = $links->item( $i );
		if ( ! $link instanceof DOMElement ) {
			continue;
		}
		$href = trim( (string) $link->getAttribute( 'href' ) );
		if ( $href === '' || str_starts_with( $href, '#' ) ) {
			continue;
		}

		$normalized = pedro_ribeiro_backloggd_normalize_review_url( $href );
		if ( $normalized && null === $review_url ) {
			$review_url = pedro_ribeiro_backloggd_absolute_url( $normalized );
			continue;
		}

		if ( null === $game_url && false !== stripos( $href, '/games/' ) ) {
			$game_url = pedro_ribeiro_backloggd_absolute_url( $href );
		}
	}

	return $review_url ?: $game_url;
}

/**
 * Collapse /u/{user}/review/{id}/likes|comments/… → /u/{user}/review/{id}/.
 *
 * @param string $href Relative or absolute href.
 * @return string|null Clean review path/URL, or null if not a review link.
 */
function pedro_ribeiro_backloggd_normalize_review_url( $href ) {
	$href = trim( (string) $href );
	if ( ! preg_match( '#(/u/[^/]+/review/\d+)(?:/|$)#i', $href, $m ) ) {
		return null;
	}

	// Keep only scheme+host if absolute, plus the clean review path.
	if ( preg_match( '#^(https?://[^/]+)#i', $href, $host ) ) {
		return $host[1] . $m[1] . '/';
	}

	return $m[1] . '/';
}

/**
 * Make a Backloggd-relative URL absolute.
 *
 * @param string $url Possibly relative URL.
 * @return string
 */
function pedro_ribeiro_backloggd_absolute_url( $url ) {
	$url = trim( (string) $url );
	if ( $url === '' ) {
		return '';
	}
	if ( str_starts_with( $url, 'http://' ) || str_starts_with( $url, 'https://' ) ) {
		return $url;
	}
	if ( str_starts_with( $url, '//' ) ) {
		return 'https:' . $url;
	}
	if ( str_starts_with( $url, '/' ) ) {
		return 'https://www.backloggd.com' . $url;
	}
	return 'https://www.backloggd.com/' . ltrim( $url, '/' );
}
