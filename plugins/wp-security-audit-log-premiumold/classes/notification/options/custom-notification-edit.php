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
use WSAL\Helpers\Validator;
use WSAL\Views\Notifications;
use WSAL\Controllers\Slack\Slack;
use WSAL\Controllers\Twilio\Twilio;
use WSAL\Helpers\Settings\Settings_Builder;
use WSAL\Entities\Custom_Notifications_Entity;
use WSAL\Extensions\Helpers\Notification_Helper;

if ( isset( $_REQUEST['action'] ) && 'edit' === $_REQUEST['action'] && isset( $_REQUEST['_wpnonce'] ) && isset( $_REQUEST[ Custom_Notifications_Entity::get_table_name() ] ) && 0 < \absint( ( (array) $_REQUEST[ Custom_Notifications_Entity::get_table_name() ] )[0] ) ) {
	$notification_id = absint( ( (array) $_REQUEST[ Custom_Notifications_Entity::get_table_name() ] )[0] );

	$generated_reports_data = Custom_Notifications_Entity::load_array( 'id=%d', array( $notification_id ) );

	$settings                                   = array();
	$settings['custom_notification_title']      = $generated_reports_data[0]['notification_title'];
	$settings['custom_notification_email']      = $generated_reports_data[0]['notification_email'];
	$settings['custom_notification_email_bcc']  = $generated_reports_data[0]['notification_email_bcc'];
	$settings['custom_notification_phone']      = $generated_reports_data[0]['notification_phone'];
	$settings['custom_notification_slack']      = $generated_reports_data[0]['notification_slack'] ?? '';
	$settings['custom_notification_email_user'] = (bool) $generated_reports_data[0]['notification_email_user'];
	$settings['custom_notification_enabled']    = (bool) $generated_reports_data[0]['notification_status'];
	$settings['custom_notification_query']      = $generated_reports_data[0]['notification_query'];

	if ( Validator::validate_json( (string) $generated_reports_data[0]['notification_template'] ) ) {
		$generated_reports_data[0]['notification_template'] = json_decode( $generated_reports_data[0]['notification_template'], true );

		if ( isset( $generated_reports_data[0]['notification_template'] ) && is_array( $generated_reports_data[0]['notification_template'] ) && ! empty( $generated_reports_data[0]['notification_template'] ) ) {
			$settings['custom_notification_template_enabled'] = (bool) $generated_reports_data[0]['notification_template']['custom_notification_template_enabled'];
			$settings['email_custom_notifications_subject']   = ( isset( $generated_reports_data[0]['notification_template']['email_custom_notifications_subject'] ) ? $generated_reports_data[0]['notification_template']['email_custom_notifications_subject'] : Notification_Helper::get_default_email_subject() );
			$settings['email_custom_notifications_body']      = ( isset( $generated_reports_data[0]['notification_template']['email_custom_notifications_body'] ) ? $generated_reports_data[0]['notification_template']['email_custom_notifications_body'] : Notification_Helper::get_default_email_body() );
		}
	}

	if ( Validator::validate_json( (string) $generated_reports_data[0]['notification_sms_template'] ) ) {
		$generated_reports_data[0]['notification_sms_template'] = json_decode( $generated_reports_data[0]['notification_sms_template'], true );

		if ( isset( $generated_reports_data[0]['notification_sms_template'] ) && is_array( $generated_reports_data[0]['notification_sms_template'] ) && ! empty( $generated_reports_data[0]['notification_sms_template'] ) ) {
			$settings['custom_notification_sms_template_enabled'] = (bool) $generated_reports_data[0]['notification_sms_template']['custom_notification_sms_template_enabled'];
			$settings['sms_custom_notifications_body']            = ( isset( $generated_reports_data[0]['notification_sms_template']['sms_custom_notifications_body'] ) ? $generated_reports_data[0]['notification_sms_template']['sms_custom_notifications_body'] : Notification_Helper::get_default_sms_body() );
		}
	}

	if ( isset( $generated_reports_data[0]['notification_slack_template'] ) && Validator::validate_json( (string) $generated_reports_data[0]['notification_slack_template'] ) ) {
		$generated_reports_data[0]['notification_slack_template'] = json_decode( $generated_reports_data[0]['notification_slack_template'], true );

		if ( isset( $generated_reports_data[0]['notification_slack_template'] ) && is_array( $generated_reports_data[0]['notification_slack_template'] ) && ! empty( $generated_reports_data[0]['notification_slack_template'] ) ) {
			$settings['custom_notification_slack_template_enabled'] = (bool) $generated_reports_data[0]['notification_slack_template']['custom_notification_slack_template_enabled'];
			$settings['slack_custom_notifications_body']            = ( isset( $generated_reports_data[0]['notification_slack_template']['slack_custom_notifications_body'] ) ? $generated_reports_data[0]['notification_slack_template']['slack_custom_notifications_body'] : Notification_Helper::get_default_slack_body() );
		}
	}

	Settings_Builder::set_current_options( $settings );

	?>
	<input type="hidden" id="custom_notifications_id" name="custom-notifications[custom_notifications_id]" value="<?php echo \esc_attr( $notification_id ); ?>" />
	<?php
}

Settings_Builder::build_option(
	array(
		'title'         => esc_html__( 'Custom notification', 'wp-security-audit-log' ),
		'id'            => 'user-notification-settings-tab',
		'type'          => 'tab-title',
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Title: ', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_title',
		'type'          => 'text',
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);
?>
<div><?php echo esc_html__( ' Refer to the ', 'wp-security-audit-log' ) . '<a href="https://melapress.com/support/kb/wp-activity-log-getting-started-sms-email-notifications/#utm_source=plugin&amp;utm_medium=link&amp;utm_campaign=wsal" rel="nofollow">' . esc_html__( 'Notifications getting started documentation.', 'wp-security-audit-log' ) . '</a> ' . esc_html__( 'for a detailed guide on how to build your own notification triggers.', 'wp-security-audit-log' ); ?></div>
<?php
Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Query builder: ', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_query',
		'type'          => 'builder',
		'default'       => '',
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Notification enabled: ', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_enabled',
		'type'          => 'checkbox',
		'default'       => true,
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);


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

Settings_Builder::build_option(
	array(
		'id'            => 'custom_notification_defaults_info',
		'type'          => 'info',
		'hint'          => $defaults,
		'default'       => true,
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	Notification_Helper::email_settings_array( 'custom_notification_email', Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME )
);

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Send email to user in the event.', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_email_user',
		'type'          => 'checkbox',
		'default'       => false,
		'hint'          => esc_html__( 'Send the notification to user carrying out the activity.', 'wp-security-audit-log' ),
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);

Settings_Builder::build_option(
	Notification_Helper::email_settings_array( 'custom_notification_email_bcc', Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME, esc_html__( 'Email BCC address: ', 'wp-security-audit-log' ) )
);

if ( Twilio::is_set() ) {
	Settings_Builder::build_option(
		Notification_Helper::phone_settings_array( 'custom_notification_phone', Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME )
	);
} else {
	$exclude_objects_link = add_query_arg(
		array(
			'page' => 'wsal-notifications',
			'tab'  => 'exclude-objects',
		),
		\network_admin_url( 'admin.php' )
	) . '#wsal-options-tab-notification-settings';
	Settings_Builder::build_option(
		array(
			'type'          => 'error',
			'id'            => 'custom_notification_phone',
			'text'          => '<span class="extra-text">' . esc_html__( 'In order to use Phone numbers you have to enable and set your Twilio credentials in ', 'wp-security-audit-log' ) . '<a  href="' . $exclude_objects_link . '">' . esc_html__( 'settings.', 'wp-security-audit-log' ) . ' </a></span>',
			'settings_name' => Notifications::BUILT_IN_NOTIFICATIONS_SETTINGS_NAME,
		)
	);
}

if ( Slack::is_set() ) {

	Settings_Builder::build_option(
		Notification_Helper::slack_settings_array( 'custom_notification_slack', Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME )
	);
} else {
	Settings_Builder::build_option(
		Notification_Helper::slack_settings_error_array( 'custom_notification_slack', Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME )
	);
}

?>
<div class="wsal-section-title wsal-section-tabs header-settings-tabs">
	<a href="#custom-main-nav-settings" class="active"><?php esc_html_e( 'Email template', 'wp-security-audit-log' ); ?></a>
	<a href="#custom-top-nav-settings"><?php esc_html_e( 'SMS template', 'wp-security-audit-log' ); ?></a>
	<a href="#custom-slack-nav-settings"><?php esc_html_e( 'Slack template', 'wp-security-audit-log' ); ?></a>
</div>

<div id="custom-slack-nav-settings" class="top-main-nav-settings">
<?php

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Custom Slack template: ', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_slack_template_enabled',
		'type'          => 'checkbox',
		'default'       => false,
		'toggle'        => '#custom-notification-slack-template',
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);
?>

	<div id="custom-notification-slack-template">	
		<?php

		$mail_template_tags = '';

		foreach ( Notification_Helper::get_email_template_tags() as $tag_name => $desc ) {
			$mail_template_tags .= '<li>' . esc_html( $tag_name ) . ' — ' . esc_html( $desc ) . '</li>';
		}

		Settings_Builder::build_option(
			array(
				'name'          => esc_html__( 'Body', 'wp-security-audit-log' ),
				'id'            => 'slack_custom_notifications_body',
				'type'          => 'textarea',
				'default'       => Notification_Helper::get_default_slack_body(),
				'hint'          => '<b>' . esc_html__( 'Available template tags:', 'wp-security-audit-log' ) . '</b><ul>' . $mail_template_tags . '</ul>',
				'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
			)
		);

		?>
	</div><!-- #custom-notification-email-template /-->
</div><!-- #slack-nav-settings /-->


<div id="custom-main-nav-settings" class="top-main-nav-settings">
<?php

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Custom email template: ', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_template_enabled',
		'type'          => 'checkbox',
		'default'       => false,
		'toggle'        => '#custom-notification-email-template',
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);
?>

	<div id="custom-notification-email-template">
		<?php
		Settings_Builder::build_option(
			array(
				'name'          => esc_html__( 'Subject: ', 'wp-security-audit-log' ),
				'id'            => 'email_custom_notifications_subject',
				'type'          => 'text',
				'default'       => Notification_Helper::get_default_email_subject(),
				'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
			)
		);

		$mail_template_tags = '';

		foreach ( Notification_Helper::get_email_template_tags() as $tag_name => $desc ) {
			$mail_template_tags .= '<li>' . esc_html( $tag_name ) . ' — ' . esc_html( $desc ) . '</li>';
		}

		Settings_Builder::build_option(
			array(
				'name'          => esc_html__( 'Body', 'wp-security-audit-log' ),
				'id'            => 'email_custom_notifications_body',
				'type'          => 'editor',
				'default'       => Notification_Helper::get_default_email_body(),
				'hint'          => '<b>' . esc_html__( 'HTML is accepted. Available template tags:', 'wp-security-audit-log' ) . '</b><ul>' . $mail_template_tags . '</ul>',
				'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
			)
		);

		?>
	</div><!-- #custom-notification-email-template /-->

</div>

<div id="custom-top-nav-settings" class="top-main-nav-settings">
<?php

Settings_Builder::build_option(
	array(
		'name'          => esc_html__( 'Custom sms template: ', 'wp-security-audit-log' ),
		'id'            => 'custom_notification_sms_template_enabled',
		'type'          => 'checkbox',
		'default'       => false,
		'toggle'        => '#custom-notification-sms-template',
		'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
	)
);
?>
	<div id="custom-notification-sms-template">
		<?php
		$sms_template_tags = '';

		foreach ( Notification_Helper::get_sms_template_tags() as $tag_name => $desc ) {
			$sms_template_tags .= '<li>' . esc_html( $tag_name ) . ' — ' . esc_html( $desc ) . '</li>';
		}

		Settings_Builder::build_option(
			array(
				'name'          => esc_html__( 'Body', 'wp-security-audit-log' ),
				'id'            => 'sms_custom_notifications_body',
				'type'          => 'textarea',
				'default'       => Notification_Helper::get_default_sms_body(),
				'hint'          => '<b>' . esc_html__( 'Available template tags: :', 'wp-security-audit-log' ) . '</b><ul>' . $sms_template_tags . '</ul>',
				'settings_name' => Notifications::CUSTOM_NOTIFICATIONS_SETTINGS_NAME,
			)
		);
		?>
	</div><!-- #custom-notification-sms-template /-->
</div>
<?php
// phpcs:disable
/* @premium:end */
// phpcs:enable