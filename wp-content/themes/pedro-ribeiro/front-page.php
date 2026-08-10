<?php
/**
 * Front page — one-pager composition (constitution section order).
 *
 * @package Pedro_Ribeiro
 */

get_header();
?>
<main>
	<?php
	get_template_part( 'template-parts/hero' );
	get_template_part( 'template-parts/sobre' );
	get_template_part( 'template-parts/projetos' );
	get_template_part( 'template-parts/experiencia' );
	get_template_part( 'template-parts/now' );
	get_template_part( 'template-parts/escritos' );
	get_template_part( 'template-parts/contato' );
	?>
</main>
<?php
get_footer();
