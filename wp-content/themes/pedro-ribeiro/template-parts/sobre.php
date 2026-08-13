<?php
/**
 * Template part: Sobre (flex layout `about`).
 *
 * Sub fields: about_tag, about_title, about_text.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_tag   = get_sub_field( 'about_tag' );
$about_title = get_sub_field( 'about_title' );
$about_text  = get_sub_field( 'about_text' );

if ( ! $about_tag && ! $about_title && ! $about_text ) {
	return;
}
?>
<section id="sobre" class="section reveal">
	<div class="page-shell">
		<?php if ( $about_tag ) : ?>
			<p class="section__label"><?php echo esc_html( $about_tag ); ?></p>
		<?php endif; ?>
		<?php if ( $about_title ) : ?>
			<h2 class="section__title"><?php echo esc_html( $about_title ); ?></h2>
		<?php endif; ?>
		<?php if ( $about_text ) : ?>
			<div class="section__lead">
				<?php echo wp_kses_post( $about_text ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
