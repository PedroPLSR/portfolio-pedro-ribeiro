<?php
/**
 * Template part: Now (flex layout `now`) — Phase D labels only.
 *
 * Sub fields in synced JSON: now_tag, now_title.
 * Live Last.fm / Backloggd fetchers are Phase E (T050–T053).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$config = function_exists( 'pedro_ribeiro_get_now_config' )
	? pedro_ribeiro_get_now_config()
	: array(
		'tag'   => (string) ( get_sub_field( 'now_tag' ) ?: '' ),
		'title' => (string) ( get_sub_field( 'now_title' ) ?: '' ),
	);

$now_tag   = $config['tag'] ?? '';
$now_title = $config['title'] ?? '';

// Phase D: no activity cards yet — omit layout if labels are empty.
if ( $now_tag === '' && $now_title === '' ) {
	return;
}
?>
<section id="now" class="section reveal">
	<div class="page-shell">
		<?php if ( $now_tag !== '' ) : ?>
			<p class="section__label"><?php echo esc_html( $now_tag ); ?></p>
		<?php endif; ?>
		<?php if ( $now_title !== '' ) : ?>
			<h2 class="section__title"><?php echo esc_html( $now_title ); ?></h2>
		<?php endif; ?>
	</div>
</section>
