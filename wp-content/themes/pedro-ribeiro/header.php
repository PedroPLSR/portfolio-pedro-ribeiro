<?php
/**
 * Header — chrome + persistent in-page nav.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-atmosphere">
	<header class="site-nav" data-site-nav>
		<div class="page-shell site-nav__inner">
			<a class="site-nav__brand" href="<?php echo esc_url( home_url( '/#topo' ) ); ?>"><?php echo esc_html( 'PR' ); ?></a>
			<nav aria-label="<?php echo esc_attr__( 'Seções', 'pedro-ribeiro' ); ?>">
				<?php
				if ( function_exists( 'pedro_ribeiro_nav_location_has_items' ) && pedro_ribeiro_nav_location_has_items( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'depth'          => 1,
							'container'      => false,
							'menu_class'     => 'site-nav__list',
							'fallback_cb'    => 'pedro_ribeiro_primary_nav_fallback',
						)
					);
				} elseif ( function_exists( 'pedro_ribeiro_primary_nav_fallback' ) ) {
					pedro_ribeiro_primary_nav_fallback();
				}
				?>
			</nav>
		</div>
	</header>
