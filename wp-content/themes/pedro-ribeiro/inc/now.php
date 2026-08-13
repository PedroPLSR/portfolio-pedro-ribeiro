<?php
/**
 * Now activity aggregator — normalized { listening, gaming }.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once PEDRO_RIBEIRO_DIR . '/inc/now-lastfm.php';
require_once PEDRO_RIBEIRO_DIR . '/inc/now-backloggd.php';

/**
 * Resolve live Now activity from ACF toggles/usernames + external sources.
 * Failures and empty items become null; never throws UI errors.
 * Key `gaming` holds latest Backloggd review shape (not currently-playing).
 *
 * @param int|null $post_id Home page ID; defaults to page_on_front.
 * @return array{listening: array{title: string, artist: string, url?: string, image_url?: string}|null, gaming: array{title: string, review: string, image_url?: string, rating?: float, url?: string}|null}
 */
function pedro_ribeiro_get_now_activity( $post_id = null ) {
	$result = array(
		'listening' => null,
		'gaming'    => null,
	);

	$config = function_exists( 'pedro_ribeiro_get_now_config' )
		? pedro_ribeiro_get_now_config( $post_id )
		: array();

	// Default: expose sources. ACF "Esconder…" checked → hide_* true → skip fetch.
	$hide_listening = ! empty( $config['hide_listening'] );
	$hide_gaming    = ! empty( $config['hide_gaming'] );
	$lastfm_user    = isset( $config['lastfm_username'] ) ? (string) $config['lastfm_username'] : '';
	$backloggd_user = isset( $config['backloggd_username'] ) ? (string) $config['backloggd_username'] : '';

	if ( ! $hide_listening && $lastfm_user !== '' ) {
		$api_key = function_exists( 'pedro_ribeiro_get_lastfm_api_key' )
			? pedro_ribeiro_get_lastfm_api_key()
			: '';
		if ( $api_key !== '' ) {
			$result['listening'] = pedro_ribeiro_fetch_lastfm_now( $lastfm_user, $api_key );
		}
	}

	if ( ! $hide_gaming && $backloggd_user !== '' ) {
		$result['gaming'] = pedro_ribeiro_fetch_backloggd_now( $backloggd_user );
	}

	return $result;
}
