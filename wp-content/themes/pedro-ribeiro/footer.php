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
		<div class="page-shell">
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
