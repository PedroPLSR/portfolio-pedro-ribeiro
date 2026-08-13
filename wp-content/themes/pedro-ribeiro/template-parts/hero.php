<?php
/**
 * Template part: Hero (flex layout `hero`).
 *
 * Sub fields: hero_text, hero_buttons (btn_info link, btn_color).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_text    = get_sub_field( 'hero_text' );
$hero_buttons = get_sub_field( 'hero_buttons' );

$valid_buttons = array();
if ( ! empty( $hero_buttons ) && is_array( $hero_buttons ) ) {
	foreach ( $hero_buttons as $cta ) {
		if ( empty( $cta['btn_info']['url'] ) ) {
			continue;
		}
		$valid_buttons[] = $cta;
	}
}

// Omit empty layout: need copy and/or at least one CTA with URL.
if ( ! $hero_text && empty( $valid_buttons ) ) {
	return;
}
?>
<section id="topo" class="hero" aria-label="<?php echo esc_attr__( 'Início', 'pedro-ribeiro' ); ?>">
	<div class="page-shell">
		<h1 class="hero__name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
		<?php if ( $hero_text ) : ?>
			<p class="hero__positioning"><?php echo esc_html( $hero_text ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $valid_buttons ) ) : ?>
			<div class="hero__ctas">
				<?php foreach ( $valid_buttons as $cta ) : ?>
					<?php
					$link  = $cta['btn_info'];
					$color = isset( $cta['btn_color'] ) ? (string) $cta['btn_color'] : '';
					?>
					<a
						class="btn btn--primary"
						href="<?php echo esc_url( $link['url'] ); ?>"
						<?php if ( ! empty( $link['target'] ) ) : ?>
							target="<?php echo esc_attr( $link['target'] ); ?>"
							rel="noopener noreferrer"
						<?php endif; ?>
						<?php if ( $color ) : ?>
							style="<?php echo esc_attr( '--btn-bg: ' . $color ); ?>"
						<?php endif; ?>
					>
						<?php echo esc_html( $link['title'] ?? '' ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
