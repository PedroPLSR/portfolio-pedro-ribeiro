<?php
/**
 * Single post — minimal editorial.
 *
 * @package Pedro_Ribeiro
 */

get_header();
?>
<main class="editorial">
	<div class="page-shell">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class(); ?>>
				<header>
					<h1 class="editorial__title"><?php echo esc_html( get_the_title() ); ?></h1>
					<p class="editorial__meta">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</p>
				</header>
				<div class="editorial__content">
					<?php the_content(); ?>
				</div>
				<a class="editorial__back" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php echo esc_html__( '← Voltar ao início', 'pedro-ribeiro' ); ?>
				</a>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
