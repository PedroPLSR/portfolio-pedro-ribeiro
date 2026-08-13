<?php
/**
 * Navigation locations, fallback chrome, and empty-location helpers.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register header and footer menu locations.
 */
function pedro_ribeiro_register_nav_menus() {
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Principal', 'pedro-ribeiro' ),
			'footer'  => esc_html__( 'Rodapé', 'pedro-ribeiro' ),
		)
	);
}
add_action( 'after_setup_theme', 'pedro_ribeiro_register_nav_menus' );

/**
 * Home URL plus a stable section hash (hashes are never translated).
 *
 * @param string $hash Section id without a leading #, e.g. 'sobre'.
 * @return string
 */
function pedro_ribeiro_home_hash_url( $hash ) {
	$hash = ltrim( (string) $hash, '#' );
	return home_url( '/#' . $hash );
}

/**
 * Whether a theme location has at least one item.
 * Unassigned or zero items both count as empty.
 *
 * @param string $location Theme location slug.
 * @return bool
 */
function pedro_ribeiro_nav_location_has_items( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return false;
	}

	$menu = wp_get_nav_menu_object( $locations[ $location ] );
	if ( ! $menu || is_wp_error( $menu ) ) {
		return false;
	}

	$items = wp_get_nav_menu_items( $menu->term_id );
	return is_array( $items ) && count( $items ) > 0;
}

/**
 * Primary fallback: Sobre, Projetos, Experiência, Contato.
 *
 * @param array|string $args Unused wp_nav_menu args (fallback_cb signature).
 */
function pedro_ribeiro_primary_nav_fallback( $args = array() ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$items = array(
		'sobre'       => esc_html__( 'Sobre', 'pedro-ribeiro' ),
		'projetos'    => esc_html__( 'Projetos', 'pedro-ribeiro' ),
		'experiencia' => esc_html__( 'Experiência', 'pedro-ribeiro' ),
		'contato'     => esc_html__( 'Contato', 'pedro-ribeiro' ),
	);

	echo '<ul class="site-nav__list">';
	foreach ( $items as $hash => $label ) {
		printf(
			'<li><a href="%s">%s</a></li>',
			esc_url( pedro_ribeiro_home_hash_url( $hash ) ),
			$label
		);
	}
	echo '</ul>';
}
