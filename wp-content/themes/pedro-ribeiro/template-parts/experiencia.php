<?php
/**
 * Template part: Experiência / Formação.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$entries = array(
	array(
		'period' => 'Atual',
		'title'  => 'Desenvolvedor full-stack',
		'org'    => 'Index Digital',
		'note'   => 'Sites e produtos digitais com WordPress e Laravel — temas, CMS, front e integrações para clientes institucionais e imobiliários.',
	),
	array(
		'period' => 'Graduação',
		'title'  => 'Ciência da Computação',
		'org'    => 'UNIFOR — Universidade de Fortaleza',
		'note'   => '',
	),
	array(
		'period' => 'Pós-graduação',
		'title'  => 'Pós-graduação em Computação',
		'org'    => 'UNIFOR — Universidade de Fortaleza',
		'note'   => 'Continuação da formação técnica alinhada a engenharia de software e sistemas web.',
	),
);
?>
<section id="experiencia" class="section reveal">
	<div class="page-shell">
		<p class="section__label"><?php echo esc_html__( 'Experiência & formação', 'pedro-ribeiro' ); ?></p>
		<h2 class="section__title"><?php echo esc_html__( 'Percurso', 'pedro-ribeiro' ); ?></h2>
		<ol class="timeline">
			<?php foreach ( $entries as $entry ) : ?>
				<li class="timeline__item">
					<span class="timeline__period"><?php echo esc_html( $entry['period'] ); ?></span>
					<h3 class="timeline__title"><?php echo esc_html( $entry['title'] ); ?></h3>
					<p class="timeline__org"><?php echo esc_html( $entry['org'] ); ?></p>
					<?php if ( ! empty( $entry['note'] ) ) : ?>
						<p class="timeline__note"><?php echo esc_html( $entry['note'] ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
