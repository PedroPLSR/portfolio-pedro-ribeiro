<?php
/**
 * Template Name: Home
 *
 * Front page flexible content: flex_content on page_on_front.
 *
 * @package Pedro_Ribeiro
 */

get_header();

$home_id = (int) get_option( 'page_on_front' );
?>
<main>
	<?php if ( $home_id && function_exists( 'have_rows' ) && have_rows( 'flex_content', $home_id ) ) : ?>
		<?php
		while ( have_rows( 'flex_content', $home_id ) ) :
			the_row();
			$layout = get_row_layout();

			if ( 'hero' === $layout ) {
				get_template_part( 'template-parts/hero' );
			} elseif ( 'about' === $layout ) {
				get_template_part( 'template-parts/sobre' );
			} elseif ( 'projects' === $layout ) {
				get_template_part( 'template-parts/projetos' );
			} elseif ( 'experience' === $layout ) {
				get_template_part( 'template-parts/experiencia' );
			} elseif ( 'now' === $layout ) {
				get_template_part( 'template-parts/now' );
			} elseif ( 'posts' === $layout ) {
				get_template_part( 'template-parts/escritos' );
			} elseif ( 'contact' === $layout ) {
				get_template_part( 'template-parts/contato' );
			}
		endwhile;
		?>
	<?php endif; ?>
</main>
<?php
get_footer();
