<?php
/**
 * Template part: Now (flex layout `now`) — live Last.fm / Backloggd.
 *
 * Sub fields: now_tag, now_title, show_listening, show_gaming,
 * label_lastfm, label_backlogdd, lastfm_username, backloggd_username.
 * Card labels from ACF with fallbacks "Ouvindo" / "Última review".
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

$activity = function_exists( 'pedro_ribeiro_get_now_activity' )
	? pedro_ribeiro_get_now_activity()
	: array(
		'listening' => null,
		'gaming'    => null,
	);

$listening = $activity['listening'] ?? null;
$gaming    = $activity['gaming'] ?? null;
$has_review = is_array( $gaming ) && ! empty( $gaming['title'] ) && ! empty( $gaming['review'] );

// Contract: omit entire section if both sources are empty/failed/disabled.
if ( null === $listening && ! $has_review ) {
	return;
}

$now_tag         = $config['tag'] ?? '';
$now_title       = $config['title'] ?? '';
$label_listening = ! empty( $config['label_listening'] )
	? (string) $config['label_listening']
	: __( 'Ouvindo', 'pedro-ribeiro' );
$label_review    = ! empty( $config['label_review'] )
	? (string) $config['label_review']
	: __( 'Última review', 'pedro-ribeiro' );

/**
 * Truncate review text for Now UI (~100 chars + ellipsis).
 *
 * @param string $text Raw review text.
 * @return string
 */
$truncate_review = static function ( $text ) {
	$text = trim( (string) $text );
	if ( $text === '' ) {
		return '';
	}
	if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) {
		if ( mb_strlen( $text ) <= 100 ) {
			return $text;
		}
		return rtrim( mb_substr( $text, 0, 100 ) ) . '…';
	}
	if ( strlen( $text ) <= 100 ) {
		return $text;
	}
	return rtrim( substr( $text, 0, 100 ) ) . '…';
};
?>
<section id="now" class="section reveal">
	<div class="page-shell">
		<?php if ( $now_tag !== '' ) : ?>
			<p class="section__label"><?php echo esc_html( $now_tag ); ?></p>
		<?php endif; ?>
		<?php if ( $now_title !== '' ) : ?>
			<h2 class="section__title"><?php echo esc_html( $now_title ); ?></h2>
		<?php endif; ?>

		<div class="now-grid">
			<?php if ( is_array( $listening ) ) : ?>
				<article class="now-card now-card--listening">
					<p class="now-card__label"><?php echo esc_html( $label_listening ); ?></p>
					<?php if ( ! empty( $listening['image_url'] ) ) : ?>
						<img
							class="now-card__image"
							src="<?php echo esc_url( $listening['image_url'] ); ?>"
							alt=""
							width="64"
							height="64"
							loading="lazy"
							decoding="async"
						>
					<?php endif; ?>
					<div class="now-card__body">
						<?php if ( ! empty( $listening['url'] ) ) : ?>
							<a class="now-card__title" href="<?php echo esc_url( $listening['url'] ); ?>" rel="noopener noreferrer" target="_blank">
								<?php echo esc_html( $listening['title'] ); ?>
							</a>
						<?php else : ?>
							<p class="now-card__title"><?php echo esc_html( $listening['title'] ); ?></p>
						<?php endif; ?>
						<p class="now-card__meta"><?php echo esc_html( $listening['artist'] ); ?></p>
					</div>
				</article>
			<?php endif; ?>

			<?php if ( $has_review ) : ?>
				<?php
				$review_text = $truncate_review( $gaming['review'] );
				$rating      = isset( $gaming['rating'] ) ? (float) $gaming['rating'] : null;
				$filled      = null !== $rating ? (int) max( 0, min( 5, round( $rating ) ) ) : 0;
				?>
				<article class="now-card now-card--review">
					<p class="now-card__label"><?php echo esc_html( $label_review ); ?></p>
					<?php if ( ! empty( $gaming['image_url'] ) ) : ?>
						<img
							class="now-card__image"
							src="<?php echo esc_url( $gaming['image_url'] ); ?>"
							alt=""
							width="64"
							height="64"
							loading="lazy"
							decoding="async"
						>
					<?php endif; ?>
					<div class="now-card__body">
						<?php if ( ! empty( $gaming['url'] ) ) : ?>
							<a class="now-card__title" href="<?php echo esc_url( $gaming['url'] ); ?>" rel="noopener noreferrer" target="_blank">
								<?php echo esc_html( $gaming['title'] ); ?>
							</a>
						<?php else : ?>
							<p class="now-card__title"><?php echo esc_html( $gaming['title'] ); ?></p>
						<?php endif; ?>
						<?php if ( null !== $rating ) : ?>
							<p class="now-card__stars" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: rating out of 5 */ __( 'Nota %s de 5', 'pedro-ribeiro' ), (string) $rating ) ); ?>">
								<?php echo esc_html( str_repeat( '★', $filled ) . str_repeat( '☆', 5 - $filled ) ); ?>
								<span class="now-card__stars-value"><?php echo esc_html( (string) $rating ); ?></span>
							</p>
						<?php endif; ?>
						<?php if ( $review_text !== '' ) : ?>
							<p class="now-card__review"><?php echo esc_html( $review_text ); ?></p>
						<?php endif; ?>
					</div>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
