<?php
/**
 * Template part: Projetos (flex layout `projects`).
 *
 * Sub fields: project_tag, project_title, projetos repeater
 * (tags, title, desc, role, stack_list). No link fields in synced schema.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$project_tag   = get_sub_field( 'project_tag' );
$project_title = get_sub_field( 'project_title' );
$project_list  = get_sub_field( 'project_list' );

if ( empty( $project_list ) ) {
	return;
}
?>
<section id="projetos" class="section reveal">
	<div class="page-shell">
		<?php if ( $project_tag ) : ?>
			<p class="section__label"><?php echo esc_html( $project_tag ); ?></p>
		<?php endif; ?>
		<?php if ( $project_title ) : ?>
			<h2 class="section__title"><?php echo esc_html( $project_title ); ?></h2>
		<?php endif; ?>
		<ul class="cases">
			<?php foreach ( $project_list as $case ) : ?>
				<li class="case">
					<?php if ( ! empty( $case['tags'] ) ) : ?>
						<div class="case__meta">
							<?php foreach ( $case['tags'] as $tag ) : ?>
								<span><?php echo esc_html( $tag['tag'] ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<?php if ( $case['title'] !== '' ) : ?>
						<h3 class="case__title"><?php echo esc_html( $case['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( $case['desc'] !== '' ) : ?>
						<div class="case__summary"><?php echo wp_kses_post( $case['desc'] ); ?></div>
					<?php endif; ?>
					<?php if ( $case['role'] !== '' ) : ?>
						<p class="case__role">
							<strong><?php echo esc_html__( 'Papel:', 'pedro-ribeiro' ); ?></strong>
							<?php echo esc_html( ' ' . $case['role'] ); ?>
						</p>
					<?php endif; ?>
					<?php if ( ! empty( $case['stack_list'] ) ) : ?>
						<ul class="case__skills" aria-label="<?php echo esc_attr__( 'Skills neste caso', 'pedro-ribeiro' ); ?>">
							<?php foreach ( $case['stack_list'] as $skill ) : ?>
								<li><?php echo esc_html( $skill['stack'] ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
