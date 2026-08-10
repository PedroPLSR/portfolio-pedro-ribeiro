<?php
/**
 * Template part: Escritos — up to 3 recent posts; omit section if zero.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$escritos_query = new WP_Query(
	array(
		'post_type'              => 'post',
		'post_status'            => 'publish',
		'posts_per_page'         => 3,
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	)
);

if ( ! $escritos_query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>
<section id="escritos" class="section reveal">
	<div class="page-shell">
		<p class="section__label"><?php echo esc_html__( 'Escritos', 'pedro-ribeiro' ); ?></p>
		<h2 class="section__title"><?php echo esc_html__( 'Textos recentes', 'pedro-ribeiro' ); ?></h2>
		<ul class="escritos-list">
			<?php
			while ( $escritos_query->have_posts() ) :
				$escritos_query->the_post();
				?>
				<li class="escritos-list__item">
					<a class="escritos-list__link" href="<?php echo esc_url( get_permalink() ); ?>">
						<?php echo esc_html( get_the_title() ); ?>
					</a>
					<time class="escritos-list__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
</section>
<?php
wp_reset_postdata();
