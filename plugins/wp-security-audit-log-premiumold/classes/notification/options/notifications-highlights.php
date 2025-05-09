<?php
/**
 * Build-in notification settings of the plugin
 *
 * @package wsal
 *
 * @since 5.1.1
 */

use WSAL\Helpers\WP_Helper;
use WSAL\Views\Notifications;
use WSAL\Helpers\Settings_Helper;
use WSAL\Helpers\Settings\Settings_Builder;
use WSAL\Extensions\Helpers\Notification_Helper;

$built_in_notifications = (array) Settings_Helper::get_option_value( Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME, array() );

$defaults = '';
if ( Notifications::is_default_mail_set() ) {
	$current_default_mail = Notifications::get_default_mail();
	$defaults            .= esc_html__( ' Currently default email is set to: ', 'wp-security-audit-log' ) . $current_default_mail;
} else {
	$defaults .= Notification_Helper::no_default_email_is_set();
}

if ( Notifications::is_default_twilio_set() ) {
	$current_default_twilio = Notifications::get_default_twilio();
	$defaults              .= esc_html__( ' Currently default phone is set to: ', 'wp-security-audit-log' ) . $current_default_twilio;
} else {
	$defaults .= Notification_Helper::no_default_phone_is_set();
}

if ( Notifications::is_default_slack_set() ) {
	$current_default_twilio = Notifications::get_default_slack();
	$defaults              .= esc_html__( ' Currently default slack channel is set to: ', 'wp-security-audit-log' ) . $current_default_twilio;
} else {
	$defaults .= Notification_Helper::no_default_slack_is_set();
}

$notifications = array();
foreach ( $built_in_notifications as $name => $value ) {
	$notifications[ 'notification_' . $name ] = $value;
}
unset( $built_in_notifications );

Settings_Builder::set_current_options( array_merge( $notifications, Notifications::get_global_notifications_setting() ) );

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Activity log highlights', 'wp-security-audit-log' ),
		'id'            => 'built-in-notification-settings-tab',
		'type'          => 'tab-title',
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Daily Activity log highlights email', 'wp-security-audit-log' ),
		'id'            => 'daily-summary-notification-settings',
		'type'          => 'header',
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Send me a summary of what happens every day. ', 'wp-security-audit-log' ),
		'id'            => 'notification_daily_summary_notification',
		'toggle'        => '#notification_daily_email_address-item, #notification_daily_send_now_ajax-item, #notification_daily_send_empty_summary_emails-item',
		'type'          => 'checkbox',
		'default'       => false,
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	Notification_Helper::email_settings_array( 'notification_daily_email_address', Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME )
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__(
			'Send empty summary emails ',
			'wp-security-audit-log'
		),
		'id'            => 'notification_daily_send_empty_summary_emails',
		'type'          => 'checkbox',
		'default'       => false,
		'hint'          => esc_html__( 'Do you want to receive an email even if there are no event IDs that match the criteria for the periodic reports? ', 'wp-security-audit-log' ),
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'id'            => 'send_daily_notification_nonce',
		'type'          => 'hidden',
		'default'       => \wp_create_nonce( Notifications::BUILT_IN_SEND_NOW_NONCE_NAME ),
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

if ( isset( $notifications['notification_daily_email_address'] ) && ! empty( $notifications['notification_daily_email_address'] ) ) {

	Settings_Builder::build_option(
		array(
			'add_label'     => true,
			'id'            => 'notification_daily_send_now_ajax',
			'type'          => 'button',
			'default'       => esc_html__( 'Send test report now (one day data)', 'wp-security-audit-log' ),
			'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
		)
	);
}
// ---- WEEKLY summary notifications

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Weekly Activity log highlights email', 'wp-security-audit-log' ),
		'id'            => 'weekly-summary-notification-settings',
		'type'          => 'header',
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Send me a summary of what happens every week. ', 'wp-security-audit-log' ),
		'id'            => 'notification_weekly_summary_notification',
		'toggle'        => '#notification_weekly_email_address-item, #notification_weekly_send_now_ajax-item, #notification_weekly_send_empty_summary_emails-item',
		'type'          => 'checkbox',
		'default'       => true,
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	Notification_Helper::email_settings_array( 'notification_weekly_email_address', Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME )
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__(
			'Send empty summary emails ',
			'wp-security-audit-log'
		),
		'id'            => 'notification_weekly_send_empty_summary_emails',
		'type'          => 'checkbox',
		'default'       => false,
		'hint'          => esc_html__( 'Do you want to receive an email even if there are no event IDs that match the criteria for the periodic reports? ', 'wp-security-audit-log' ),
		'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
	)
);

if ( isset( $notifications['notification_weekly_email_address'] ) && ! empty( $notifications['notification_weekly_email_address'] ) ) {

	Settings_Builder::build_option(
		array(
			'add_label'     => true,
			'id'            => 'notification_weekly_send_now_ajax',
			'type'          => 'button',
			'default'       => esc_html__( 'Send test report now (one day data)', 'wp-security-audit-log' ),
			'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
		)
	);
}

// Sections include.

// phpcs:disable
/* @premium:start */
// phpcs:enable
Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Include these in the highlights email', 'wp-security-audit-log' ),
		'id'            => 'notification-summary-default-settings',
		'type'          => 'header',
		'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'User logins: ', 'wp-security-audit-log' ),
		'id'            => 'notification_summary_user_logins',
		'type'          => 'checkbox',
		'default'       => true,
		'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
	)
);


Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Failed logins: ', 'wp-security-audit-log' ),
		'id'            => 'notification_summary_failed_logins',
		'type'          => 'checkbox',
		'toggle'        => '#notification_wrong_password-item, #notification_summary_wrong_username-item',
		'default'       => true,
		'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
	)
);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Wrong password: ', 'wp-security-audit-log' ),
			'id'            => 'notification_wrong_password',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Login attempt with wrong username: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_wrong_username',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Password changes: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_password_changes',
			'type'          => 'checkbox',
			'toggle'        => '#notification_summary_password_user_change_own_password-item, #notification_summary_password_user_change_other_password-item',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'User changed its own password: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_password_user_change_own_password',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'User changed other user password: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_password_user_change_other_password',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Plugins activity: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_plugins_activity',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Website system settings changes: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_system_activity',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);
	?>
<div id="notification_summary_content_wrap">
	<?php
	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Content changes: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_content_changes',
			'type'          => 'checkbox',
			'toggle'        => '#notification_summary_published_posts-item, #notification_summary_deleted_posts-item, #notification_summary_changed_posts-item, #notification_summary_status_changed_posts-item',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'List of published posts: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_published_posts',
			'type'          => 'checkbox',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'List of deleted and trashed posts: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_deleted_posts',
			'type'          => 'checkbox',
			'default'       => false,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'List of changes in posts: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_changed_posts',
			'type'          => 'checkbox',
			'default'       => false,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'List of status changed posts: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_status_changed_posts',
			'type'          => 'checkbox',
			'default'       => false,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);
	?>
</div>
<?php
if ( WP_Helper::is_multisite() ) {
	Settings_Builder::build_option(
		array(
			'title'         => esc_html__( 'Multisite Activity log highlights email: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_multisite_activity',
			'type'          => 'header',
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);

	Settings_Builder::build_option(
		array(
			'name'          => esc_html__( 'Send summary per individual site: ', 'wp-security-audit-log' ),
			'id'            => 'notification_summary_multisite_individual_site',
			'type'          => 'checkbox',
			'toggle'        => '#notification_summary_content_wrap',
			'default'       => true,
			'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
		)
	);
}

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Should the events of each section be included in the summary email?', 'wp-security-audit-log' ),
		'id'            => 'notification-summary-include-occurrences',
		'type'          => 'header',
		'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Include events', 'wp-security-audit-log' ),
		'id'            => 'notification_events_included',
		'type'          => 'checkbox',
		'default'       => false,
		'toggle'        => '#notification_summary_number_of_events_included-item',
		'hint'          => esc_html__( 'By default, only the total numbers of events per category are reported in the activity log highlight email. Use the below settings to also include some or all of the events from each category. IMPORTANT: The process of including all events in the email can be a very resource intensive process, and it all depends on how many events do you usually have in the activity log.', 'wp-security-audit-log' ),
		'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Number of events to include', 'wp-security-audit-log' ),
		'id'            => 'notification_summary_number_of_events_included',
		'type'          => 'radio',
		'options'       => array(
			'10' => esc_html__( 'Last 10 events', 'wp-security-audit-log' ),
			'1'  => esc_html__( 'All of the events', 'wp-security-audit-log' ),
		),

		'settings_name' => Notifications::NOTIFICATIONS_SETTINGS_NAME,
	)
);
// phpcs:disable
/* @premium:end */
// phpcs:enable
?>

<input type="hidden" name="<?php echo Notifications::NOTIFICATIONS_SETTINGS_NAME;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>[]" value="0" />
