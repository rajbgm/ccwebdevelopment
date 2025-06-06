<?php
/**
 * Class: View manager.
 *
 * Helper class to show the views in dashboard area.
 *
 * @package wsal
 */

declare(strict_types=1);

namespace WSAL\Helpers;

use WpSecurityAuditLog;
use WSAL\Controllers\Alert;
use WSAL\Views\Setup_Wizard;
use WSAL\Helpers\Settings_Helper;
use WSAL\Controllers\Alert_Manager;
use WSAL\ListAdminEvents\List_Events;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\WSAL\Helpers\View_Manager' ) ) {

	/**
	 * View Manager.
	 *
	 * This class includes all the views, initialize them and shows the active one.
	 * It creates also the menu items.
	 *
	 * @package wsal
	 */
	class View_Manager {

		/**
		 * Array of views.
		 *
		 * @var WSAL_AbstractView[]
		 */
		public static $views = array();

		/**
		 * Active view.
		 *
		 * @var WSAL_AbstractView|null
		 */
		protected static $active_view = false;

		/**
		 * Method: Constructor.
		 *
		 * @since  1.0.0
		 */
		public static function init() {

			// Skipped views array.
			$skip_views = array();

			$views = array(
				'WSAL_Views_AuditLog',
				'WSAL_Views_Help',
				'WSAL_Views_Settings',
				'WSAL_Views_ToggleAlerts',
				'\WSAL\Views\Premium_Features',
			);

            // phpcs:ignore
            /* @premium:start */
			// Array of views to skip for premium version.
			if ( wsal_freemius()->is_plan_or_trial__premium_only( 'starter' ) ) {
				// $skip_views[] = 'WSAL_Views_EmailNotifications';
				// $skip_views[] = 'WSAL_Views_Search';
				$skip_views[] = '\WSAL\Views\Premium_Features';
			}

			if ( wsal_freemius()->is_plan_or_trial__premium_only( 'professional' ) ) {
				// $skip_views[] = 'WSAL_Views_EmailNotifications';
				// $skip_views[] = 'WSAL_Views_Search';
				// $skip_views[] = 'WSAL_Views_ExternalDB';
				// $skip_views[] = 'WSAL_Views_LogInUsers';
				// $skip_views[] = 'WSAL_Views_Reports';
				$skip_views[] = '\WSAL\Views\Premium_Features';
			}
			if ( wsal_freemius()->is_plan_or_trial__premium_only( 'business', true ) ) {
				$skip_views[] = '\WSAL\Views\Premium_Features';
			}
			if ( wsal_freemius()->is_plan_or_trial__premium_only( 'enterprise', true ) ) {
				$skip_views[] = '\WSAL\Views\Premium_Features';
			}
			/* @premium:end */

			/**
			 * Skipped Views.
			 *
			 * Array of views which are skipped before plugin views are initialized.
			 *
			 * As of version 4.5 this no longer holds a list with files, but the name of the classes (namespaces must be included if they are in use)
			 *
			 * @param array $skip_views - Skipped views.
			 *
			 * @since 4.5.0
			 */
			$skip_views = apply_filters( 'wsal_skip_views', $skip_views );

			$views_to_load = array_diff( $views, $skip_views );

			foreach ( $views_to_load as $view ) {
				if ( is_subclass_of( $view, '\WSAL_AbstractView' ) ) {
					global $wsal_class;
					if ( null === $wsal_class ) {
						$wsal_class = WpSecurityAuditLog::get_instance();
					}
					self::$views[] = new $view( $wsal_class );
				}
			}

			// Stop Freemius from hiding the menu on sub sites under certain circumstances.
			add_filter(
				'fs_should_hide_site_admin_settings_on_network_activation_mode_wp-security-audit-log',
				array(
					__CLASS__,
					'bypass_freemius_menu_hiding',
				)
			);

			// Add menus.
			add_action( 'admin_menu', array( __CLASS__, 'add_admin_menus' ) );
			add_action( 'network_admin_menu', array( __CLASS__, 'add_admin_menus' ) );

			// Add plugin shortcut links.
			add_filter( 'plugin_action_links_' . WSAL_BASE_NAME, array( __CLASS__, 'add_plugin_shortcuts' ) );

			// Render header.
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'render_view_header' ) );

			// Render footer.
			add_action( 'admin_footer', array( __CLASS__, 'render_view_footer' ) );

			// Initialize setup wizard.
			if ( ! Settings_Helper::get_boolean_option_value( 'setup-complete', false )
				&& Settings_Helper::current_user_can( 'edit' )
			) {
				Setup_Wizard::init();
			}

			/* @premium:start */
			if ( wsal_freemius()->is__premium_only() ) {
				if ( ! Settings_Helper::get_boolean_option_value( 'disable-admin-bar-notif', true ) ) {
					add_action( 'admin_bar_menu', array( __CLASS__, 'live_notifications__premium_only' ), 1000, 1 );
					add_action( 'wp_ajax_wsal_adminbar_events_refresh', array( __CLASS__, 'wsal_adminbar_events_refresh__premium_only' ) );
				}
			}
			/* @premium:end */

			add_action( 'admin_head', array( __CLASS__, 'hide_freemius_sites_section' ) );

			// Check if WFCM is running by seeing if we have the version defined.
			if ( defined( 'WFCM_VERSION' ) && ( version_compare( \WFCM_VERSION, '1.6.0', '<' ) ) ) {
				add_action( 'admin_notices', array( __CLASS__, 'update_wfcm_notice' ) );
				add_action( 'network_admin_notices', array( __CLASS__, 'update_wfcm_notice' ) );
			}
		}

		/**
		 * Display notice if user is using older version of WFCM
		 *
		 * @since 5.0.0
		 */
		public static function update_wfcm_notice() {
			if ( defined( 'WFCM_VERSION' ) ) {
				if ( version_compare( \WFCM_VERSION, '1.6.0', '<' ) ) {
					echo '<div class="notice notice-success">
                        <p>' . esc_html__( 'WP Activity Log requires Website File Changes Monitor 1.6.0. Please upgrade that plugin.', 'wp-security-audit-log' ) . '</p>
                    </div>';
				}
			}
		}

		/**
		 * Add new view given class name.
		 *
		 * @param string $class Class name.
		 *
		 * @since 5.0.0
		 */
		public static function add_from_class( $class ) {
			if ( is_subclass_of( $class, '\WSAL_AbstractView' ) ) {
				global $wsal_class;
				self::$views[] = new $class( $wsal_class );
			} else {
				self::$views[] = $class;
			}
		}

		/**
		 * Order views by their declared weight.
		 *
		 * @since 5.0.0
		 */
		public static function reorder_views() {
			usort( self::$views, array( __CLASS__, 'order_by_weight' ) );
		}

		/**
		 * Get page order by its weight.
		 *
		 * @internal This has to be public for PHP to call it.
		 * @param \WSAL_AbstractView $a - First view.
		 * @param \WSAL_AbstractView $b - Second view.
		 *
		 * @return int
		 *
		 * @since 5.0.0
		 */
		public static function order_by_weight( $a, $b ) {

			$wa = \call_user_func( array( $a, 'get_weight' ) ); // $a->get_weight();
			$wb = \call_user_func( array( $b, 'get_weight' ) ); // $b->get_weight();
			switch ( true ) {
				case $wa < $wb:
					return -1;
				case $wa > $wb:
					return 1;
				default:
					return 0;
			}
		}

		/**
		 * WordPress Action
		 *
		 * @since 5.0.0
		 */
		public static function add_admin_menus() {
			self::reorder_views();

			if ( Settings_Helper::current_user_can( 'view' ) && count( self::$views ) ) {
				// Add main menu.
				$main_view_menu_slug = \call_user_func( array( self::$views[0], 'get_safe_view_name' ) );
				\call_user_func(
					array( self::$views[0], 'set_hook_suffix' ),
					add_menu_page(
						'WP Activity Log',
						'WP Activity Log' . self::get_updates_count_html(),
						'read', // No capability requirement.
						$main_view_menu_slug,
						array( __CLASS__, 'render_view_body' ),
						self::$views[0]->get_icon(),
						'2.5' // Right after dashboard.
					)
				);

				List_Events::add_screen_options( \call_user_func( array( self::$views[0], 'get_hook_suffix' ) ) );

				// Protected views to be displayed only to user with full plugin access.
				$protected_views = array(
					'wsal-togglealerts',
					'wsal-usersessions-views',
					'wsal-settings',
					'wsal-ext-settings',
					'wsal-rep-views-main',
					'wsal-np-notifications',
					'wsal-setup',
					'wsal-reports-new',
					'wsal-notifications',
				);

				// Check edit privileges of the current user.
				$has_current_user_edit_priv = Settings_Helper::current_user_can( 'edit' );

				// Add menu items.
				foreach ( self::$views as $view ) {
					if ( \call_user_func( array( $view, 'is_accessible' ) ) ) {
						$safe_view_name = \call_user_func( array( $view, 'get_safe_view_name' ) );
						if ( self::get_class_name_by_view( $safe_view_name ) ) {
							continue;
						}

						if ( in_array( $safe_view_name, $protected_views, true ) && ! $has_current_user_edit_priv ) {
							continue;
						}

						\call_user_func(
							array( $view, 'set_hook_suffix' ),
							add_submenu_page(
								\call_user_func( array( $view, 'is_visible' ) ) ? $main_view_menu_slug : null,
								\call_user_func( array( $view, 'get_title' ) ),
								\call_user_func( array( $view, 'get_name' ) ),
								'read', // No capability requirement.
								$safe_view_name,
								array( __CLASS__, 'render_view_body' )
							)
						);
					}
				}

                // phpcs:disable
                // phpcs:enable
			}
		}

		/**
		 * Returns the HTML that shows baboon notifications about the banners we have not confirmed.
		 *
		 * @return string
		 *
		 * @since 5.2.2
		 */
		public static function get_updates_count_html(): string {
			$count = Notices::get_number_of_notices();
			$count_html = '';

			if ( 0 < $count ) {
				$style = '<style>
					#wsal-notices-menu .update-count {
						position: absolute !important;
						top: 2px !important;
						right: 4px !important;
						min-width: 20px !important;
						margin-right: 2px !important;
						line-height: 1.2rem !important;
						background: #d63638 !important;
						border-radius: 50% !important;
						display: inline-block !important;
						vertical-align: top !important;
						z-index: 26 !important;
						font-weight: bold !important;
					}
					#wsal-notices-menu.update-plugins {
						display: inline !important;
						background: none !important;
					}
				</style>';

				$count_html = $style ;/*. sprintf(
					' <span id="wsal-notices-menu" class="update-plugins"><span class="update-count">%d</span></span>',
					\number_format_i18n( $count )
				);*/
			}

			return $count_html;
		}

		/**
		 * WordPress Filter
		 *
		 * @param array $old_links - Array of old links.
		 *
		 * @since 5.0.0
		 */
		public static function add_plugin_shortcuts( $old_links ) {
			self::reorder_views();

			$new_links = array();
			foreach ( self::$views as $view ) {
				if ( \call_user_func( array( $view, 'has_plugin_shortcut_link' ) ) ) {
					$new_links[] = '<a href="' . add_query_arg( 'page', \call_user_func( array( $view, 'get_safe_view_name' ) ), \network_admin_url( 'admin.php' ) ) . '">' . \call_user_func( array( $view, 'get_name' ) ) . '</a>';

					if ( 1 === count( $new_links ) && ! wsal_freemius()->is__premium_only() ) {
						// Trial link.
						$trial_link  = 'https://melapress.com/wordpress-activity-log/pricing/?utm_source=plugin&utm_medium=link&utm_campaign=wsal';
						$new_links[] = '<a style="font-weight:bold; color:#049443 !important" href="' . $trial_link . '" target="_blank">' . __( 'Get Premium!', 'wp-security-audit-log' ) . '</a>';
					}
				}
			}
			return array_merge( $new_links, $old_links );
		}

		/**
		 * Returns page id of current page (or false on error).
		 *
		 * @return int
		 *
		 * @since 5.0.0
		 */
		protected static function get_backend_page_index() {
			// Get current view via $_GET array.
			$current_view = ( isset( $_GET['page'] ) ) ? \sanitize_text_field( \wp_unslash( $_GET['page'] ) ) : '';

			if ( isset( $current_view ) ) {
				foreach ( self::$views as $i => $view ) {
					if ( \call_user_func( array( $view, 'get_safe_view_name' ) ) === $current_view ) {
						return $i;
					}
				}
			}
			return false;
		}

		/**
		 * Returns the current active view or null if none.
		 *
		 * @return WSAL_AbstractView|null
		 *
		 * @since 5.0.0
		 */
		public static function get_active_view() {
			if ( false === self::$active_view ) {
				self::$active_view = null;

				// Get current view via $_GET array.
				$current_view = ( isset( $_GET['page'] ) ) ? \sanitize_text_field( \wp_unslash( $_GET['page'] ) ) : '';

				if ( isset( $current_view ) ) {
					foreach ( self::$views as $view ) {
						if ( \call_user_func( array( $view, 'get_safe_view_name' ) ) === $current_view ) {
							self::$active_view = $view;
						}
					}
				}
			}
			return self::$active_view;
		}

		/**
		 * Render header of the current view.
		 *
		 * @since 5.0.0
		 */
		public static function render_view_header() {
			$view = self::get_active_view();
			if ( $view ) {
				\call_user_func( array( $view, 'header' ) );
			}
		}

		/**
		 * Render footer of the current view.
		 *
		 * @since 5.0.0
		 */
		public static function render_view_footer() {
			$view = self::get_active_view();
			if ( $view ) {
				\call_user_func( array( $view, 'footer' ) );
			}

			global $pagenow;
			if ( 'admin.php' === $pagenow && ( isset( $_GET['page'] ) && 'wsal-auditlog-pricing' === $_GET['page'] ) ) {
				?>
				<style>
					.fs-full-size-wrapper {
						margin: 0px 0 -65px -20px !important;
					}
	
					#root .fs-app-header .fs-page-title h2, #fs_pricing_wrapper .fs-app-header .fs-page-title h2 {
						font-size: 23px;
						font-weight: 400;
						margin: 0;
						padding: 9px 0 4px 20px;
						line-height: 1.3;
					}
	
					@media only screen and (max-width: 768px) {
						#root #fs_pricing_wrapper .fs-app-main .fs-section--plans-and-pricing .fs-section--packages .fs-packages-menu, #fs_pricing_wrapper #fs_pricing_wrapper .fs-app-main .fs-section--plans-and-pricing .fs-section--packages .fs-packages-menu {
							padding: 5px;
							display: flex;
							width: 100%;
							margin: 0 auto;
						}
					}
				</style>
				<?php
			}
		}

		/**
		 * Render content of the current view.
		 *
		 * @since 5.0.0
		 */
		public static function render_view_body() {
			$view = self::get_active_view();

			if ( $view && $view instanceof \WSAL_AbstractView ) {
				?>
				<div class="wrap">
					<?php
						$view->render_icon();
						$view->render_title();
						$view->render_content();
					?>
				</div>
				<?php
			} else {
				?>
				<div class="wrap">
					<?php
						$view::render_icon();
						$view::render_title();
						$view::render_content();
					?>
				</div>
				<?php
			}
		}

		/**
		 * Returns view instance corresponding to its class name.
		 *
		 * @param string $class_name View class name.
		 *
		 * @return WSAL_AbstractView|bool The view or false on failure.
		 *
		 * @since 5.0.0
		 */
		public static function find_by_class_name( $class_name ) {
			foreach ( self::$views as $view ) {
				if ( $view instanceof $class_name ) {
					return $view;
				}
			}
			return false;
		}

		/**
		 * Method: Returns class name of the view using view name.
		 *
		 * @param  string $view_slug - Slug of view.
		 * @since  1.0.0
		 */
		private static function get_class_name_by_view( $view_slug ) {
			$not_show = false;
			switch ( $view_slug ) {
				case 'wsal-loginusers':
					if ( class_exists( 'WSAL_UserSessions_Plugin' ) ) {
						$not_show = true;
					}
					break;
				case 'wsal-search':
					if ( class_exists( 'WSAL_SearchExtension' ) ) {
						$not_show = true;
					}
					break;
				case 'wsal-externaldb':
					if ( class_exists( 'WSAL_Ext_Plugin' ) ) {
						$not_show = true;
					}
					break;
				default:
					break;
			}
			return $not_show;
		}

		/* @premium:start */
		/**
		 * Add WSAL to WP-Admin menu bar.
		 *
		 * @since 3.2.4
		 *
		 * @param WP_Admin_Bar $admin_bar - Instance of WP_Admin_Bar.
		 */
		public static function live_notifications__premium_only( $admin_bar ) {
			if ( Settings_Helper::current_user_can( 'view' ) && is_admin() ) {
				$adn_updates = Settings_Helper::get_option_value( 'admin-bar-notif-updates', 'page-refresh' );
				$event       = Alert_Manager::get_admin_bar_event( 'page-refresh' === $adn_updates );

				if ( $event && isset( $event['alert_id'] ) ) {
					$code = Alert::get_alert(
						(int) $event['alert_id']
					);
					if ( $code ) {
						$admin_bar->add_node(
							array(
								'id'    => 'wsal-menu',
								'title' => 'LIVE: ' . $code['desc'] . ' from ' . $event['client_ip'],
								'href'  => \WpSecurityAuditLog::get_plugin_admin_url_page(),
								'meta'  => array( 'class' => 'wsal-live-notif-item' ),
							)
						);
					}
				}
			}
		}

		/**
		 * WP-Admin bar refresh event handler.
		 *
		 * @since 3.2.4
		 */
		public static function wsal_adminbar_events_refresh__premium_only() {
			if ( ! Settings_Helper::current_user_can( 'view' ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => __( 'Access Denied.', 'wp-security-audit-log' ),
					)
				);
				die();
			}

			if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'wsal-common-js-nonce' ) ) {
				echo wp_json_encode(
					array(
						'success' => false,
						'message' => __( 'Nonce verification failed.', 'wp-security-audit-log' ),
					)
				);
				die();
			}

			$event = Alert_Manager::get_admin_bar_event( true );
			$code  = false;
			if ( is_array( $event ) && isset( $event['alert_id'] ) ) {
				$code = Alert::get_alert( (int) $event['alert_id'] );
			}

			if ( $code ) {
				echo wp_json_encode(
					array(
						'success' => true,
						'message' => 'LIVE: ' . $code['desc'] . ' from ' . $event['client_ip'],
					)
				);
			}

			die();
		}
		/* @premium:end */

		/**
		 * Hide Freemius sites section on the account page
		 * of a multisite WordPress network.
		 *
		 * @since 5.0.0
		 */
		public static function hide_freemius_sites_section() {
			global $pagenow;

			// Return if not multisite.
			if ( ! is_multisite() ) {
				return;
			}

			// Return if multisite but not on the network admin.
			if ( ! is_network_admin() ) {
				return;
			}

			// Return if the pagenow is not on admin.php page.
			if ( 'admin.php' !== $pagenow ) {
				return;
			}

			// Get page query parameter.
            $page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : false; // phpcs:ignore

			if ( 'wsal-auditlog-account' === $page ) {
				echo '<style type="text/css">#fs_sites {display:none;}</style>';
			}
		}

		/**
		 * Bypasses Freemius hiding menu items.
		 *
		 * @param bool $should_hide Should allow Freemium to hide menu items.
		 *
		 * @return bool
		 *
		 * @since 5.0.0
		 */
		public static function bypass_freemius_menu_hiding( $should_hide ) {
			return false;
		}

		/**
		 * Builds a relative asset path that takes SCRIPT_DEBUG constant into account.
		 *
		 * @param string $path                 Path relative to the plugin folder.
		 * @param string $filename             Filename base (.min is optionally appended to this).
		 * @param string $extension            File extension.
		 * @param bool   $use_minified_version If true, the minified version of the file is used.
		 *
		 * @return string
		 *
		 * @since 5.0.0
		 */
		public static function get_asset_path( $path, $filename, $extension, $use_minified_version = true ) {
			$result = $path . $filename . '.';
			if ( $use_minified_version && SCRIPT_DEBUG ) {
				$result .= 'min.';
			}
			$result .= $extension;

			return $result;
		}

		/**
		 * Returns all the registered views
		 *
		 * @return array
		 *
		 * @since 5.0.0
		 */
		public static function get_views(): array {
			return self::$views;
		}
	}
}
