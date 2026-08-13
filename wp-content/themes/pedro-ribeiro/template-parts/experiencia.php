<?php
/**
 * Template part: Experiência (flex layout `experience`).
 *
 * Sub fields: experience_tag, experience_title, experience_list
 * (period, title, org_title, desc).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$experience_tag   = get_sub_field( 'experience_tag' );
$experience_title = get_sub_field( 'experience_title' );
$experience_list  = get_sub_field( 'experience_list' );

if ( ! $experience_tag && ! $experience_title && ! $experience_list ) {
	return;
}

?>
<section id="experiencia" class="section reveal">
	<div class="page-shell">
		<?php if ( $experience_tag ) : ?>
			<p class="section__label"><?php echo esc_html( $experience_tag ); ?></p>
		<?php endif; ?>
		<?php if ( $experience_title ) : ?>
			<h2 class="section__title"><?php echo esc_html( $experience_title ); ?></h2>
		<?php endif; ?>
		<ol class="timeline">
			<?php foreach ( $experience_list as $item ) : ?>
				<li class="timeline__item">
					<?php if ( $item['period'] ) : ?>
						<span class="timeline__period"><?php echo esc_html( $item['period'] ); ?></span>
					<?php endif; ?>
					<?php if ( $item['title'] ) : ?>
						<h3 class="timeline__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( $item['org_title'] ) : ?>
						<p class="timeline__org"><?php echo esc_html( $item['org_title'] ); ?></p>
					<?php endif; ?>
					<?php if ( $item['desc'] ) : ?>
						<div class="timeline__note"><?php echo wp_kses_post( $item['desc'] ); ?></div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
