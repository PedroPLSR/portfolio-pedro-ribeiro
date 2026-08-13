<?php
/**
 * Last.fm Now fetcher — prefer nowplaying, else most recent scrobble.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @var int Transient TTL (seconds). */
const PEDRO_RIBEIRO_NOW_LASTFM_TTL = 15;

/** @var int HTTP timeout (seconds). */
const PEDRO_RIBEIRO_NOW_LASTFM_TIMEOUT = 4;

/**
 * Fetch one Last.fm track for Now: nowplaying if present, otherwise last scrobble.
 * Returns null only on failure or empty track list.
 *
 * @param string $username Last.fm username.
 * @param string $api_key  API key from env / wp-config.
 * @return array{title: string, artist: string, url?: string, image_url?: string}|null
 */
function pedro_ribeiro_fetch_lastfm_now( $username, $api_key ) {
	$username = is_string( $username ) ? trim( $username ) : '';
	$api_key  = is_string( $api_key ) ? trim( $api_key ) : '';

	if ( $username === '' || $api_key === '' ) {
		return null;
	}

	$cache_key = 'pr_now_lastfm_' . md5( strtolower( $username ) );
	$cached    = get_transient( $cache_key );
	if ( is_array( $cached ) && array_key_exists( 'item', $cached ) ) {
		return $cached['item'];
	}

	$url = add_query_arg(
		array(
			'method'  => 'user.getRecentTracks',
			'user'    => $username,
			'api_key' => $api_key,
			'format'  => 'json',
			'limit'   => 1,
		),
		'https://ws.audioscrobbler.com/2.0/'
	);

	$response = wp_remote_get(
		$url,
		array(
			'timeout'     => PEDRO_RIBEIRO_NOW_LASTFM_TIMEOUT,
			'redirection' => 2,
			'headers'     => array(
				'Accept' => 'application/json',
			),
		)
	);

	$item = pedro_ribeiro_parse_lastfm_track( $response );
	set_transient( $cache_key, array( 'item' => $item ), PEDRO_RIBEIRO_NOW_LASTFM_TTL );

	return $item;
}

/**
 * Parse Last.fm recent-tracks: prefer nowplaying, else first recent track.
 *
 * @param array|WP_Error $response wp_remote_get result.
 * @return array{title: string, artist: string, url?: string, image_url?: string}|null
 */
function pedro_ribeiro_parse_lastfm_track( $response ) {
	if ( is_wp_error( $response ) ) {
		return null;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		return null;
	}

	$body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
	if ( ! is_array( $body ) ) {
		return null;
	}

	$track = $body['recenttracks']['track'] ?? null;
	if ( ! is_array( $track ) ) {
		return null;
	}

	// API may return a single object or a list; first entry is nowplaying or latest.
	if ( isset( $track[0] ) && is_array( $track[0] ) ) {
		$track = $track[0];
	}

	return pedro_ribeiro_map_lastfm_track( $track );
}

/**
 * Map a Last.fm track object to the normalized listening shape.
 *
 * @param array $track Raw track from API.
 * @return array{title: string, artist: string, url?: string, image_url?: string}|null
 */
function pedro_ribeiro_map_lastfm_track( array $track ) {
	$title = isset( $track['name'] ) ? trim( (string) $track['name'] ) : '';
	if ( $title === '' ) {
		return null;
	}

	$artist = '';
	if ( isset( $track['artist']['#text'] ) ) {
		$artist = trim( (string) $track['artist']['#text'] );
	} elseif ( isset( $track['artist']['name'] ) ) {
		$artist = trim( (string) $track['artist']['name'] );
	} elseif ( is_string( $track['artist'] ?? null ) ) {
		$artist = trim( $track['artist'] );
	}

	if ( $artist === '' ) {
		return null;
	}

	$item = array(
		'title'  => $title,
		'artist' => $artist,
	);

	if ( ! empty( $track['url'] ) && is_string( $track['url'] ) ) {
		$item['url'] = $track['url'];
	}

	$image_url = pedro_ribeiro_lastfm_pick_image( $track['image'] ?? null );
	if ( $image_url ) {
		$item['image_url'] = $image_url;
	}

	return $item;
}

/**
 * Prefer a medium/large image from Last.fm image array.
 *
 * @param mixed $images Image entries from API.
 * @return string|null
 */
function pedro_ribeiro_lastfm_pick_image( $images ) {
	if ( ! is_array( $images ) ) {
		return null;
	}

	$by_size = array();
	foreach ( $images as $image ) {
		if ( ! is_array( $image ) ) {
			continue;
		}
		$size = (string) ( $image['size'] ?? '' );
		$text = trim( (string) ( $image['#text'] ?? '' ) );
		if ( $text !== '' && $size !== '' ) {
			$by_size[ $size ] = $text;
		}
	}

	foreach ( array( 'large', 'medium', 'extralarge', 'small' ) as $size ) {
		if ( ! empty( $by_size[ $size ] ) ) {
			return $by_size[ $size ];
		}
	}

	return null;
}
