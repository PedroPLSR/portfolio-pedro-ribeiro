<?php
/**
 * Footer.
 *
 * @package Pedro_Ribeiro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<footer class="site-footer">
		<div class="page-shell site-footer__inner">
			<?php if ( function_exists( 'pedro_ribeiro_nav_location_has_items' ) && pedro_ribeiro_nav_location_has_items( 'footer' ) ) : ?>
				<nav class="site-footer__nav" aria-label="<?php echo esc_attr__( 'Rodapé', 'pedro-ribeiro' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'depth'          => 1,
							'container'      => false,
							'menu_class'     => 'site-footer__list',
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>
			<p>
				&copy; <span data-year><?php echo esc_html( gmdate( 'Y' ) ); ?></span>
				<?php echo esc_html( 'Pedro Ribeiro' ); ?>
			</p>
		</div>
	</footer>
</div><!-- .site-atmosphere -->
<?php wp_footer(); ?>
<script>
	(function () {
		var el = document.querySelector('[data-year]');
		if (el) el.textContent = String(new Date().getFullYear());
	})();
</script>
</body>
</html>
