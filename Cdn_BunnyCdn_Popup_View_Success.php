<?php
/**
 * File: Cdn_BunnyCdn_Popup_View_Success.php
 *
 * @since   X.X.X
 * @package W3TC
 */

namespace W3TC;

defined( 'W3TC' ) || die();

?>

<form class="w3tc_cdn_bunnycdn_form">
	<div class="metabox-holder">
		<?php Util_Ui::postbox_header( esc_html__( 'Succeeded', 'w3-total-cache' ) ); ?>

		<div style="text-align: center">
			<?php esc_html_e( 'Site was successfully configured.', 'w3-total-cache' ); ?><br />
		</div>

		<p class="submit">
			<input type="button"
				class="w3tc_cdn_bunnycdn_done w3tc-button-save button-primary"
				value="<?php esc_attr_e( 'Done', 'w3-total-cache' ); ?>" />
		</p>
		<?php Util_Ui::postbox_footer(); ?>
	</div>
</form>
