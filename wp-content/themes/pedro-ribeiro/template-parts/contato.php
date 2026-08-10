<?php
/**
 * Template part: Contato (links only — no form).
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email      = 'pedro.ribeiro@example.com';
$whatsapp   = 'https://wa.me/5585999999999';
$linkedin   = 'https://www.linkedin.com/in/pedro-ribeiro';
$github     = 'https://github.com/pedro-ribeiro';
$cv_url     = get_template_directory_uri() . '/public/curriculo.pdf';
$cv_path    = get_template_directory() . '/public/curriculo.pdf';
$has_cv     = file_exists( $cv_path );
?>
<section id="contato" class="section reveal">
	<div class="page-shell">
		<p class="section__label"><?php echo esc_html__( 'Contato', 'pedro-ribeiro' ); ?></p>
		<h2 class="section__title"><?php echo esc_html__( 'Vamos conversar', 'pedro-ribeiro' ); ?></h2>
		<p class="section__lead">
			<?php echo esc_html__( 'Sem formulário — escolha o canal. Currículo em PDF disponível para download.', 'pedro-ribeiro' ); ?>
		</p>
		<ul class="contact-list">
			<?php if ( $email ) : ?>
				<li>
					<a href="<?php echo esc_url( 'mailto:' . $email ); ?>">
						<span class="contact-list__label"><?php echo esc_html__( 'Email', 'pedro-ribeiro' ); ?></span>
						<span><?php echo esc_html( $email ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $whatsapp ) : ?>
				<li>
					<a href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="contact-list__label"><?php echo esc_html__( 'WhatsApp', 'pedro-ribeiro' ); ?></span>
						<span><?php echo esc_html__( 'Abrir conversa', 'pedro-ribeiro' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $linkedin ) : ?>
				<li>
					<a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="contact-list__label"><?php echo esc_html__( 'LinkedIn', 'pedro-ribeiro' ); ?></span>
						<span><?php echo esc_html( 'linkedin.com/in/pedro-ribeiro' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $github ) : ?>
				<li>
					<a href="<?php echo esc_url( $github ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="contact-list__label"><?php echo esc_html__( 'GitHub', 'pedro-ribeiro' ); ?></span>
						<span><?php echo esc_html( 'github.com/pedro-ribeiro' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( $has_cv ) : ?>
				<li>
					<a href="<?php echo esc_url( $cv_url ); ?>" download>
						<span class="contact-list__label"><?php echo esc_html__( 'Currículo', 'pedro-ribeiro' ); ?></span>
						<span><?php echo esc_html__( 'Baixar PDF', 'pedro-ribeiro' ); ?></span>
					</a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</section>
