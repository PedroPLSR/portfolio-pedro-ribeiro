<?php
/**
 * Template part: Contato (flex layout `contact`).
 *
 * Sub fields: contact_tag, contact_title, contact_desc, contact_list (title, link).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_tag   = get_sub_field( 'contact_tag' );
$contact_title = get_sub_field( 'contact_title' );
$contact_desc  = get_sub_field( 'contact_desc' );
$contact_list  = get_sub_field( 'contact_list' );

$items = array();
if ( ! empty( $contact_list ) && is_array( $contact_list ) ) {
	foreach ( $contact_list as $row ) {
		$link = $row['link'] ?? null;
		if ( empty( $link['url'] ) ) {
			continue;
		}
		$items[] = $row;
	}
}

if ( ! $contact_tag && ! $contact_title && ! $contact_desc && empty( $items ) ) {
	return;
}
?>
<section id="contato" class="section reveal">
	<div class="page-shell">
		<?php if ( $contact_tag ) : ?>
			<p class="section__label"><?php echo esc_html( $contact_tag ); ?></p>
		<?php endif; ?>
		<?php if ( $contact_title ) : ?>
			<h2 class="section__title"><?php echo esc_html( $contact_title ); ?></h2>
		<?php endif; ?>
		<?php if ( $contact_desc ) : ?>
			<div class="section__lead">
				<?php echo wp_kses_post( $contact_desc ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $items ) ) : ?>
			<ul class="contact-list">
				<?php foreach ( $items as $item ) : ?>
					<?php $link = $item['link']; ?>
					<li>
						<a
							href="<?php echo esc_url( $link['url'] ); ?>"
							<?php if ( ! empty( $link['target'] ) ) : ?>
								target="<?php echo esc_attr( $link['target'] ); ?>"
								rel="noopener noreferrer"
							<?php endif; ?>
						>
							<?php if ( ! empty( $item['title'] ) ) : ?>
								<span class="contact-list__label"><?php echo esc_html( $item['title'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $link['title'] ) ) : ?>
								<span><?php echo esc_html( $link['title'] ); ?></span>
							<?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</section>
