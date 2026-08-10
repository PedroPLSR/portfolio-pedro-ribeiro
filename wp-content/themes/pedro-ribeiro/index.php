<?php
/**
 * Fallback index.
 *
 * @package Pedro_Ribeiro
 */

get_header();
?>
<main class="editorial">
	<div class="page-shell">
		<?php if ( have_posts() ) : ?>
			<ul class="archive-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li class="archive-list__item">
						<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
					</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p class="section__lead"><?php echo esc_html__( 'Nenhum conteúdo encontrado.', 'pedro-ribeiro' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
