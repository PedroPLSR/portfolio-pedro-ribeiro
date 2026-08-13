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
	<script>
	(function () {
		var key = 'pedro-ribeiro-appearance';
		var saved = '';
		try {
			saved = localStorage.getItem(key) || '';
		} catch (e) {
			saved = '';
		}
		var theme = (saved === 'light' || saved === 'dark')
			? saved
			: (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
		var root = document.documentElement;
		root.setAttribute('data-theme', theme);
		root.style.colorScheme = theme;
	})();
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-atmosphere">
	<header class="site-nav" data-site-nav>
		<div class="page-shell site-nav__inner">
			<a class="site-nav__brand" href="<?php echo esc_url( home_url( '/#topo' ) ); ?>"><?php echo esc_html( 'PR' ); ?></a>
			<div class="site-nav__tools">
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
				<div class="site-appearance" data-appearance role="group" aria-label="<?php echo esc_attr__( 'Aparência', 'pedro-ribeiro' ); ?>">
					<button type="button" class="site-appearance__option" data-theme-set="light" aria-pressed="false">
						<?php echo esc_html__( 'Claro', 'pedro-ribeiro' ); ?>
					</button>
					<span class="site-appearance__sep" aria-hidden="true">|</span>
					<button type="button" class="site-appearance__option" data-theme-set="dark" aria-pressed="false">
						<?php echo esc_html__( 'Escuro', 'pedro-ribeiro' ); ?>
					</button>
				</div>
			</div>
		</div>
	</header>
