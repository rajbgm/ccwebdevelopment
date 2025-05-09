<?php
/**
 * Class WSAL_UserSessions_View_Settings.
 *
 * @package wsal
 */

use WSAL\Helpers\Settings_Helper;
use WSAL\Helpers\User_Sessions_Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings view for the user sessions extension.
 *
 * @package wsal
 */
class WSAL_UserSessions_View_Settings {

	/**
	 * View slug.
	 *
	 * @var string
	 */
	public static $slug = 'settings';

	/**
	 * Legacy - added because of php8 deprecation remove
	 *
	 * @var [type]
	 *
	 * @since 4.5.0
	 */
	public $wsal;

	/**
	 * Constructor.
	 *
	 * @param WpSecurityAuditLog $plugin Plugin instance.
	 */
	public function __construct( WpSecurityAuditLog $plugin ) {
		$this->wsal = $plugin;

		$this->register_usersessions_tab();
	}

	/**
	 * Returns a title to use for this tab/page.
	 *
	 * @method get_title
	 *
	 * @since  4.1.0
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Settings', 'wp-security-audit-log' );
	}

	/**
	 * Registers this tab to the main page and setups the allowed tabs array.
	 *
	 * @method register_usersessions_tab
	 *
	 * @since  4.1.0
	 */
	public function register_usersessions_tab() {
		add_filter(
			'wsal_usersessions_views_nav_header_items',
			function( $tabs ) {
				$tabs[ self::$slug ] = array(
					'title' => $this->get_title(),
				);
				return $tabs;
			},
			10,
			1
		);
		add_filter(
			'wsal_usersessions_views_allowed_tabs',
			function( $allowed ) {
				$allowed[] = self::$slug;
				return $allowed;
			},
			10,
			1
		);
	}

	/**
	 * Render this page or tab html contents.
	 *
	 * @method render
	 *
	 * @since  4.1.0
	 */
	public function render() {
		if ( isset( $_POST[ 'wsal_usersessions_updated_' . self::$slug ] ) && 'true' == $_POST[ 'wsal_usersessions_updated_' . self::$slug ] ) { // phpcs:ignore
			$saved = $this->maybe_save_form();
			// if the form was saved show a notification to tell users.
			// NOTE: since this uses WP default notification classes WP hoists
			// this to the top of the page regardless of where it renders out.
			if ( $saved ) {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Settings have been saved', 'wp-securitu-audit-log' ); ?></p>
				</div>
				<?php
			}
		}

		$form_data = $this->get_form_data();
		?>
		<form method="POST">
			<input type="hidden" name="wsal_usersessions_updated_<?php echo esc_attr( self::$slug ); ?>" value="true" />
			<?php wp_nonce_field( 'wsal_usersessions_' . self::$slug, '_wpnonce' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><label for="wsal_usersessions_core_cleanup_cron"><?php esc_html_e( 'Cleanup expired session data', 'wp-security-audit-log' ); ?></label></th>
						<td>
							<input
								name="wsal_usersessions_core_cleanup_cron"
								id="wsal_usersessions_core_cleanup_cron"
								type="checkbox"
								<?php echo checked( $form_data['core_cleanup_cron_enabled'] ); ?>
							/>
							<span><?php echo esc_html_e( 'The plugin will delete the data about expired users sessions from the WordPress database.', 'wp-security-audit-log' ); ?></span>
						</td>
					</tr>
					<tr>
						<th><label for="wsal_usersessions_real_time_cleanup"><?php esc_html_e( 'Check for existing users sessions when users access the login page directly', 'wp-security-audit-log' ); ?></label></th>
						<td>
							<input
								name="wsal_usersessions_real_time_cleanup"
								id="wsal_usersessions_real_time_cleanup"
								type="checkbox"
								<?php echo checked( $form_data['usersessions_real_time_cleanup'] ); ?>
							/>
							<span><?php echo esc_html_e( 'Whenever a user accesses the login page they will be asked to authenticate, even when they have an existing session in the same browser. This is how WordPress works. If you enable this setting, the plugin will check for existing sessions, therefore users who have a session can resume the previous session, thus not consuming another session (and avoid being locked out). However, this type of realtime check requires an extensive amount of resources, thus might have an impact on websites with a large number of users..', 'wp-security-audit-log' ); ?></span>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save', 'wp-security-audit-log' ); ?>">
			</p>
		</form>
		<?php
	}

	/**
	 * Saves data from the form on this page if nonce and permission checks pass.
	 *
	 * @method maybe_save_form
	 *
	 * @since  4.1.0
	 */
	public function maybe_save_form() {
		// bail if nonce check fails.
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wsal_usersessions_' . self::$slug ) ) {
			return;
		}
		// bail early if current user can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$core_cleanup_cron_enabled = ( isset( $_POST['wsal_usersessions_core_cleanup_cron'] ) ) ? filter_input( INPUT_POST, 'wsal_usersessions_core_cleanup_cron', FILTER_VALIDATE_BOOLEAN ) : false;
		Settings_Helper::set_option_value( 'wsal_usersessions_core_cleanup_cron_enabled', $core_cleanup_cron_enabled );

		$usersessions_real_time_cleanup = ( isset( $_POST['wsal_usersessions_real_time_cleanup'] ) ) ? filter_input( INPUT_POST, 'wsal_usersessions_real_time_cleanup', FILTER_VALIDATE_BOOLEAN ) : false;
		Settings_Helper::set_option_value( 'usersessions_real_time_cleanup_enabled', $usersessions_real_time_cleanup );
		// return that we updated settings.
		return true;
	}

	/**
	 * Loads the form data into a class property for use on the page.
	 *
	 * @method get_form_data
	 *
	 * @since  4.1.0
	 *
	 * @return array
	 */
	public function get_form_data() {
		return array(
			'core_cleanup_cron_enabled'      => User_Sessions_Helper::is_core_session_cleanup_enabled(),
			'usersessions_real_time_cleanup' => User_Sessions_Helper::is_usersessions_real_time_cleanup_enabled(),
		);
	}
}
