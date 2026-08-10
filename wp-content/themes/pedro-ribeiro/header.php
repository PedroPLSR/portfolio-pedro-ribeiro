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
				<ul class="site-nav__list">
					<li><a href="<?php echo esc_url( home_url( '/#sobre' ) ); ?>"><?php echo esc_html__( 'Sobre', 'pedro-ribeiro' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#projetos' ) ); ?>"><?php echo esc_html__( 'Projetos', 'pedro-ribeiro' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#experiencia' ) ); ?>"><?php echo esc_html__( 'Experiência', 'pedro-ribeiro' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/#contato' ) ); ?>"><?php echo esc_html__( 'Contato', 'pedro-ribeiro' ); ?></a></li>
				</ul>
			</nav>
		</div>
	</header>
