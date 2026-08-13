<?php
/**
 * Pedro Ribeiro theme functions (Underscores-based).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PEDRO_RIBEIRO_VERSION', '1.0.0' );
define( 'PEDRO_RIBEIRO_DIR', get_template_directory() );
define( 'PEDRO_RIBEIRO_URI', get_template_directory_uri() );

require_once PEDRO_RIBEIRO_DIR . '/inc/acf.php';
require_once PEDRO_RIBEIRO_DIR . '/inc/now.php';

/**
 * Theme setup.
 */
function pedro_ribeiro_setup() {
	load_theme_textdomain( 'pedro-ribeiro', PEDRO_RIBEIRO_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);
}
add_action( 'after_setup_theme', 'pedro_ribeiro_setup' );

/**
 * Enqueue Vite-built CSS/JS (production build under dist/).
 */
function pedro_ribeiro_enqueue_assets() {
	$manifest_path = PEDRO_RIBEIRO_DIR . '/dist/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		// Fallback when assets have not been built yet.
		return;
	}

	$manifest = json_decode( (string) file_get_contents( $manifest_path ), true );
	if ( ! is_array( $manifest ) ) {
		return;
	}

	$entry = $manifest['src/js/main.js'] ?? null;
	if ( ! is_array( $entry ) ) {
		return;
	}

	if ( ! empty( $entry['css'] ) && is_array( $entry['css'] ) ) {
		foreach ( $entry['css'] as $index => $css_file ) {
			wp_enqueue_style(
				'pedro-ribeiro-' . $index,
				PEDRO_RIBEIRO_URI . '/dist/' . ltrim( $css_file, '/' ),
				array(),
				PEDRO_RIBEIRO_VERSION
			);
		}
	}

	if ( ! empty( $entry['file'] ) ) {
		wp_enqueue_script(
			'pedro-ribeiro-main',
			PEDRO_RIBEIRO_URI . '/dist/' . ltrim( $entry['file'], '/' ),
			array(),
			PEDRO_RIBEIRO_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'pedro_ribeiro_enqueue_assets' );

/**
 * Google Fonts (Syne + Figtree) — same as accepted frontend.
 */
function pedro_ribeiro_enqueue_fonts() {
	wp_enqueue_style(
		'pedro-ribeiro-fonts',
		'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=Syne:wght@600;700;800&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'pedro_ribeiro_enqueue_fonts' );
