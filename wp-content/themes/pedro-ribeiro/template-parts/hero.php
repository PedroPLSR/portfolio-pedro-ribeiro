<?php
/**
 * Template part: Hero.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="topo" class="hero" aria-label="<?php echo esc_attr__( 'Início', 'pedro-ribeiro' ); ?>">
	<div class="page-shell">
		<h1 class="hero__name"><?php echo esc_html( 'Pedro Ribeiro' ); ?></h1>
		<p class="hero__positioning">
			<?php echo esc_html( 'Full-stack · WordPress & Laravel · Fortaleza / remoto' ); ?>
		</p>
		<div class="hero__ctas">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/#projetos' ) ); ?>">
				<?php echo esc_html__( 'Ver projetos', 'pedro-ribeiro' ); ?>
			</a>
			<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/#contato' ) ); ?>">
				<?php echo esc_html__( 'Contato', 'pedro-ribeiro' ); ?>
			</a>
		</div>
	</div>
</section>
