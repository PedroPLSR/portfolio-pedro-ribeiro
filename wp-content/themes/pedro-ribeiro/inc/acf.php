<?php
/**
 * ACF Local JSON — load path only.
 * Do not invent field keys; owner syncs groups into acf-json/ (Phase D / T040).
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
