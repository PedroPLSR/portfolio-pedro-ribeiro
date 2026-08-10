<?php
/**
 * Archive — minimal editorial list.
 *
 * @package Pedro_Ribeiro
 */

get_header();
?>
<main class="editorial">
	<div class="page-shell">
		<header>
			<h1 class="editorial__title">
				<?php
				if ( is_home() && ! is_front_page() ) {
					echo esc_html__( 'Escritos', 'pedro-ribeiro' );
				} else {
					the_archive_title();
				}
				?>
			</h1>
			<?php
			$archive_description = get_the_archive_description();
			if ( $archive_description ) :
				?>
				<div class="section__lead"><?php echo wp_kses_post( $archive_description ); ?></div>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<ul class="archive-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li class="archive-list__item">
						<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
						<time class="escritos-list__date" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p class="section__lead"><?php echo esc_html__( 'Nenhum escrito publicado ainda.', 'pedro-ribeiro' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
