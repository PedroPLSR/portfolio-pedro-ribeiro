<?php
/**
 * ACF Local JSON + Phase D helpers.
 * Field keys come only from acf-json/ (owner sync). No API secrets in ACF.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tell ACF where to load/save field-group JSON.
 *
 * @param array $paths Existing paths.
 * @return array
 */
function pedro_ribeiro_acf_json_load_point( $paths ) {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'pedro_ribeiro_acf_json_load_point' );

/**
 * Save JSON into the theme acf-json folder when ACF is present.
 *
 * @param string $path Default save path.
 * @return string
 */
function pedro_ribeiro_acf_json_save_point( $path ) {
	return get_template_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'pedro_ribeiro_acf_json_save_point' );

/**
 * Static front page ID for flex_content context.
 *
 * @return int
 */
function pedro_ribeiro_home_id() {
	return (int) get_option( 'page_on_front' );
}

/**
 * Bootstrap LASTFM_API_KEY from the environment when not already defined.
 * Never read from ACF / admin content fields (T045).
 */
function pedro_ribeiro_bootstrap_secrets() {
	if ( defined( 'LASTFM_API_KEY' ) ) {
		return;
	}

	$env = getenv( 'LASTFM_API_KEY' );
	if ( is_string( $env ) && $env !== '' ) {
		define( 'LASTFM_API_KEY', $env );
	}
}
pedro_ribeiro_bootstrap_secrets();

/**
 * Last.fm API key from env / wp-config constant only.
 *
 * @return string
 */
function pedro_ribeiro_get_lastfm_api_key() {
	if ( defined( 'LASTFM_API_KEY' ) && LASTFM_API_KEY ) {
		return (string) LASTFM_API_KEY;
	}

	$env = getenv( 'LASTFM_API_KEY' );
	return is_string( $env ) ? $env : '';
}

/**
 * Now layout config from Home flex_content (owner JSON keys only).
 * ACF keys: now_tag, now_title, show_listening, show_gaming, label_lastfm,
 * label_backlogdd, lastfm_username, backloggd_username.
 * LASTFM_API_KEY is never read from ACF (env / wp-config only).
 *
 * ACF fields are named show_* but admin labels are "Esconder…":
 * checked (true) = hide that source; unchecked (default) = expose / fetch.
 * Normalized here as hide_listening / hide_gaming.
 *
 * @param int|null $post_id Home page ID; defaults to page_on_front.
 * @return array{tag: string, title: string, hide_listening: bool, hide_gaming: bool, label_listening: string, label_review: string, lastfm_username: string, backloggd_username: string}
 */
function pedro_ribeiro_get_now_config( $post_id = null ) {
	$config = array(
		'tag'                => '',
		'title'              => '',
		'hide_listening'     => false,
		'hide_gaming'        => false,
		'label_listening'    => '',
		'label_review'       => '',
		'lastfm_username'    => '',
		'backloggd_username' => '',
	);

	if ( ! function_exists( 'get_field' ) ) {
		return $config;
	}

	$post_id = null === $post_id ? pedro_ribeiro_home_id() : (int) $post_id;
	if ( ! $post_id ) {
		return $config;
	}

	// Prefer current flexible row when already inside a `now` layout.
	if ( function_exists( 'get_row_layout' ) && get_row_layout() === 'now' ) {
		return pedro_ribeiro_normalize_now_config(
			array(
				'now_tag'            => get_sub_field( 'now_tag' ),
				'now_title'          => get_sub_field( 'now_title' ),
				'show_listening'     => get_sub_field( 'show_listening' ),
				'show_gaming'        => get_sub_field( 'show_gaming' ),
				'label_lastfm'       => get_sub_field( 'label_lastfm' ),
				'label_backlogdd'    => get_sub_field( 'label_backlogdd' ),
				'lastfm_username'    => get_sub_field( 'lastfm_username' ),
				'backloggd_username' => get_sub_field( 'backloggd_username' ),
			)
		);
	}

	$rows = get_field( 'flex_content', $post_id );
	if ( ! is_array( $rows ) ) {
		return $config;
	}

	foreach ( $rows as $row ) {
		if ( ( $row['acf_fc_layout'] ?? '' ) !== 'now' ) {
			continue;
		}
		return pedro_ribeiro_normalize_now_config( $row );
	}

	return $config;
}

/**
 * Normalize a flex `now` row into the theme Now config shape.
 *
 * @param array $row ACF row or sub-field map.
 * @return array{tag: string, title: string, hide_listening: bool, hide_gaming: bool, label_listening: string, label_review: string, lastfm_username: string, backloggd_username: string}
 */
function pedro_ribeiro_normalize_now_config( array $row ) {
	return array(
		'tag'                => (string) ( $row['now_tag'] ?? '' ),
		'title'              => (string) ( $row['now_title'] ?? '' ),
		// ACF key show_* + label "Esconder" → checked means hide.
		'hide_listening'     => (bool) ( $row['show_listening'] ?? false ),
		'hide_gaming'        => (bool) ( $row['show_gaming'] ?? false ),
		'label_listening'    => trim( (string) ( $row['label_lastfm'] ?? '' ) ),
		'label_review'       => trim( (string) ( $row['label_backlogdd'] ?? '' ) ),
		'lastfm_username'    => trim( (string) ( $row['lastfm_username'] ?? '' ) ),
		'backloggd_username' => trim( (string) ( $row['backloggd_username'] ?? '' ) ),
	);
}
