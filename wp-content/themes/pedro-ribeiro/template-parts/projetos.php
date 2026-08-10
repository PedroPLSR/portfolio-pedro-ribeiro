<?php
/**
 * Template part: Projetos (hardcoded until Phase D ACF wiring).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cases = array(
	array(
		'meta'    => array( 'Correnem', 'WordPress' ),
		'title'   => 'Plataforma institucional e conteúdo Correnem',
		'summary' => 'Site institucional com estrutura de conteúdo administrável, atenção a performance e navegação clara para público e equipe interna.',
		'role'    => 'desenvolvimento front e tema WordPress.',
		'skills'  => array( 'WordPress', 'PHP', 'HTML / CSS', 'JavaScript', 'ACF' ),
		'url'     => 'https://correnem.com.br',
		'link'    => 'Site ao vivo',
	),
	array(
		'meta'    => array( 'Index Digital', 'Cliente institucional' ),
		'title'   => 'Site institucional com CMS editorial',
		'summary' => 'Entrega de presença digital para cliente institucional: páginas de produto, conteúdo editável e padrão visual consistente entre seções.',
		'role'    => 'full-stack no ecossistema WordPress (tema, campos e front).',
		'skills'  => array( 'WordPress', 'PHP', 'ACF', 'Tailwind', 'Vite' ),
		'url'     => '',
		'link'    => '',
	),
	array(
		'meta'    => array( 'Index Digital', 'Produto imobiliário' ),
		'title'   => 'Vitrine e fluxo de conteúdo imobiliário',
		'summary' => 'Experiência de listagem e detalhe de empreendimentos, com ênfase em hierarquia visual, dados estruturados e manutenção pelo time de conteúdo.',
		'role'    => 'front e integração com backend Laravel / WordPress conforme o projeto.',
		'skills'  => array( 'Laravel', 'WordPress', 'PHP', 'JavaScript', 'MySQL' ),
		'url'     => '',
		'link'    => '',
	),
);
?>
<section id="projetos" class="section reveal">
	<div class="page-shell">
		<p class="section__label"><?php echo esc_html__( 'Projetos', 'pedro-ribeiro' ); ?></p>
		<h2 class="section__title"><?php echo esc_html__( 'Casos recentes', 'pedro-ribeiro' ); ?></h2>
		<ul class="cases">
			<?php foreach ( $cases as $case ) : ?>
				<li class="case">
					<div class="case__meta">
						<?php foreach ( $case['meta'] as $meta_item ) : ?>
							<span><?php echo esc_html( $meta_item ); ?></span>
						<?php endforeach; ?>
					</div>
					<h3 class="case__title"><?php echo esc_html( $case['title'] ); ?></h3>
					<p class="case__summary"><?php echo esc_html( $case['summary'] ); ?></p>
					<p class="case__role">
						<strong><?php echo esc_html__( 'Papel:', 'pedro-ribeiro' ); ?></strong>
						<?php echo esc_html( ' ' . $case['role'] ); ?>
					</p>
					<ul class="case__skills" aria-label="<?php echo esc_attr__( 'Skills neste caso', 'pedro-ribeiro' ); ?>">
						<?php foreach ( $case['skills'] as $skill ) : ?>
							<li><?php echo esc_html( $skill ); ?></li>
						<?php endforeach; ?>
					</ul>
					<?php if ( ! empty( $case['url'] ) ) : ?>
						<div class="case__links">
							<a href="<?php echo esc_url( $case['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo esc_html( $case['link'] ); ?>
							</a>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
