<?php
/**
 * Advanced settings of the plugin
 *
 * @package wsal
 *
 * @since 5.0.0
 */

// phpcs:disable
/* @premium:start */
// phpcs:enable
use WSAL\Extensions\Notifications\Custom_Notifications;
use WSAL\Views\Notifications;
use WSAL\Helpers\Settings\Settings_Builder;

	Settings_Builder::build_option(
		array(
			'title'         => esc_html__( 'Custom notifications', 'wp-security-audit-log' ),
			'id'            => 'custom-notifications-tab',
			'type'          => 'tab-title',
			'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	$custom_notifications_list = new Custom_Notifications( \WSAL_Views_AuditLog::get_page_arguments() );
	$custom_notifications_list->prepare_items();

	?>
		<style>
			#periodic-report-viewer-content {
				margin-left: 5px;
				margin-right: 5px;
			}
		</style>
		<div id="periodic-report-viewer-content">
			
			
				<?php
				echo '<div style="clear:both; float:right">';
				$custom_notifications_list->search_box(
					__( 'Search', 'wp-security-audit-log' ),
					strtolower( $custom_notifications_list::get_table_name() ) . '-find'
				);
				echo '</div>';
				// Display the audit log list.
				$custom_notifications_list->display();
				?>
		</div>
		
<script>
	jQuery('li.wsal-tabs:not(.wsal-not-tab)').click(function () {
		jQuery('.wsal-save-button').show();
		jQuery('.create_custom_notification').hide();
	});

	jQuery( ".wsal-options-tab-custom-notifications, #wsal-options-tab-custom-notifications" ).on( "activated", function() {
		jQuery('.wsal-save-button').hide();
		jQuery('.create_custom_notification').show();
	});
</script>
<?php
// phpcs:disable
/* @premium:end */
// phpcs:enable