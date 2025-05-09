<?php
/**
 * Responsible for the Showing the list of the events collected.
 *
 * @package    WSAL
 * @subpackage helpers
 *
 * @since 5.2.1
 *
 * @copyright  2025 Melapress
 * @license    https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 *
 * @see       https://wordpress.org/plugins/wp-2fa/
 */

declare(strict_types=1);

namespace WSAL\Extensions\Notifications;

use WSAL\Helpers\Settings_Helper;
use WSAL\Views\Notifications;
use WSAL\Helpers\DateTime_Formatter_Helper;
use WSAL\Entities\Custom_Notifications_Entity;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/template.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table-compat.php';
	require_once ABSPATH . 'wp-admin/includes/list-table.php';
}

/*
 * Base list table class
 */
if ( ! class_exists( '\WSAL\Extensions\Notifications\Custom_Notifications' ) ) {
	/**
	 * Responsible for rendering base table for manipulation.
	 *
	 * @since 5.2.1
	 */
	class Custom_Notifications extends \WP_List_Table {
// phpcs:disable
/* @premium:start */
// phpcs:enable
		public const SCREEN_OPTIONS_SLUG = 'wsal_custom_notifications_view';
		public const SEARCH_INPUT        = 'cnspp';

		/**
		 * Current screen.
		 *
		 * @var \WP_Screen
		 *
		 * @since 5.2.1
		 */
		protected $wp_screen;

		/**
		 * The table to show.
		 *
		 * @var WSAL\Entities\Custom_Notifications_Entity
		 *
		 * @since 5.2.1
		 */
		private static $table;

		/**
		 * Name of the table to show.
		 *
		 * @var string
		 *
		 * @since 5.2.1
		 */
		private static $table_name;

		/**
		 * How many.
		 *
		 * @var int
		 *
		 * @since 5.2.1
		 */
		protected $count;

		/**
		 * How many records to show per page - that is a fall back option, it will try to extract that first from the stored user data, then from the settings and from here as a last resort.
		 *
		 * @var int
		 *
		 * @since 5.2.1
		 */
		protected $records_per_page = 10;

		/**
		 * Holds the array with all of the column names and their representation in the table header.
		 *
		 * @var array
		 *
		 * @since 5.2.1
		 */
		private static $columns = array();

		/**
		 * Events Query Arguments.
		 *
		 * @since 5.2.1
		 *
		 * @var array
		 */
		private static $query_args;

		/**
		 * Holds the DB connection (if it is external), null otherwise.
		 *
		 * @var \wpdb
		 *
		 * @since 5.2.1
		 */
		private static $wsal_db = null;

		/**
		 * Holds the current query arguments.
		 *
		 * @var array
		 *
		 * @since 5.2.1
		 */
		private static $query_occ = array();

		/**
		 * The url hash of that view.
		 *
		 * @var string
		 *
		 * @since 5.2.1
		 */
		private static $hash_url = '#wsal-options-tab-custom-notifications';

		/**
		 * Holds the current query order.
		 *
		 * @var array
		 *
		 * @since 5.2.1
		 */
		private static $query_order = array();

		/**
		 * Default class constructor.
		 *
		 * @param stdClass $query_args Events query arguments.
		 *
		 * @since 5.2.1
		 */
		public function __construct( $query_args ) {
			self::$query_args = $query_args;

			parent::__construct(
				array(
					'singular' => 'custom-notification',
					'plural'   => 'custom-notifications',
					'ajax'     => true,
					'screen'   => $this->get_wp_screen(),
				)
			);

			self::$columns = self::manage_columns( array() );

			self::$wsal_db = null;

			self::$table_name = Custom_Notifications_Entity::get_table_name( self::$wsal_db );
			self::$table      = Custom_Notifications_Entity::class;
		}

		/**
		 * Returns the current wsal_db connection.
		 *
		 * @return \wpdb
		 *
		 * @since 5.2.1
		 */
		public static function get_wsal_db() {
			return self::$wsal_db;
		}

		/**
		 * Displays the search box.
		 *
		 * @since 5.2.1
		 *
		 * @param string $text     The 'submit' button label.
		 * @param string $input_id ID attribute value for the search input field.
		 */
		public function search_box( $text, $input_id ) {
			if ( empty( $_REQUEST[ self::SEARCH_INPUT ] ) && ! $this->has_items() ) {
				return;
			}

			$input_id = $input_id . '-search-input';
			?>
			<p class="search-box" style="position:relative">
				<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo \esc_html( $text ); ?>:</label>

				<input type="search" id="<?php echo esc_attr( $input_id ); ?>" class="wsal_search_input" name="<?php echo \esc_attr( self::SEARCH_INPUT ); ?>" value="<?php echo \esc_attr( self::escaped_search_input() ); ?>" />

				<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>

				<script>
					var form = document.getElementById('wsal_form');
					var submit_button = document.getElementById('search-submit');

					submit_button.addEventListener("click", function() {
						var input = document.createElement('input');
						input.type = 'hidden';
						input.name = '<?php echo \esc_attr( self::SEARCH_INPUT ); ?>';
						input.value = document.getElementById('<?php echo esc_attr( $input_id ); ?>').value;
						form.appendChild(input);
					}, true);
				</script>
			</p>
			<?php
		}

		/**
		 * Returns the search query string escaped
		 *
		 * @return string
		 *
		 * @since 5.2.1
		 */
		public static function escaped_search_input() {
			return isset( $_REQUEST[ self::SEARCH_INPUT ] ) ? \esc_sql( \sanitize_text_field( \wp_unslash( $_REQUEST[ self::SEARCH_INPUT ] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		/**
		 * Adds columns to the screen options screed.
		 *
		 * @param array $columns - Array of column names.
		 *
		 * @since 5.2.1
		 */
		public static function manage_columns( $columns ): array {
			$admin_fields = array(
				'cb'                    => '<input type="checkbox" />', // to display the checkbox.
				'notification_title'    => __( 'Name', 'wp-security-audit-log' ),
				'notification_username' => __( 'Username', 'wp-security-audit-log' ),
				'notification_email'    => __( 'Sent to email', 'wp-security-audit-log' ),
				'notification_phone'    => __( 'Sent to phone', 'wp-security-audit-log' ),
				'notification_slack'    => __( 'Sent to slack', 'wp-security-audit-log' ),
				'notification_status'   => __( 'Status', 'wp-security-audit-log' ),
				'created_on'            => __( 'Creation Time', 'wp-security-audit-log' ),
			);

			$screen_options = $admin_fields;

			$table_columns = array();

			return \array_merge( $table_columns, $screen_options, $columns );
		}

		/**
		 * Returns the table name.
		 *
		 * @since 5.2.1
		 */
		public static function get_table_name(): string {
			return self::$table_name;
		}

		/**
		 * Returns the the wp_screen property.
		 *
		 * @since 5.2.1
		 */
		private function get_wp_screen() {
			if ( empty( $this->wp_screen ) ) {
				$this->wp_screen = get_current_screen();
			}

			return $this->wp_screen;
		}

		/**
		 * Prepares the list of items for displaying.
		 *
		 * Query, filter data, handle sorting, and pagination, and any other data-manipulation required prior to rendering
		 *
		 * @since 5.2.1
		 */
		public function prepare_items() {
			$columns               = $this->get_columns();
			$hidden                = array();
			$sortable              = $this->get_sortable_columns();
			$this->_column_headers = array( $columns, $hidden, $sortable );

			$this->handle_table_actions();

			$this->fetch_table_data();

			$hidden = get_user_option( 'manage' . $this->get_wp_screen()->id . 'columnshidden', false );
			if ( ! $hidden ) {
				$hidden = array();
			}

			$this->_column_headers = array( self::$columns, $hidden, $sortable );
			// phpcs:ignore
			// usort( $items, [ &$this, 'usort_reorder' ] ); // phpcs:ignore

			// Set the pagination.
			$this->set_pagination_args(
				array(
					'total_items' => $this->count,
					'per_page'    => $this->get_screen_option_per_page(),
					'total_pages' => ceil( $this->count / $this->get_screen_option_per_page() ),
				)
			);
		}

		/**
		 * Returns the currently hidden column headers for the current user
		 *
		 * @return array
		 *
		 * @since 5.2.1
		 */
		public static function get_hidden_columns() {
			return array_filter(
				(array) get_user_option( 'managetoplevel_page_wsal-auditlogcolumnshidden', false )
			);
		}

		/**
		 * Get a list of columns. The format is:
		 * 'internal-name' => 'Title'.
		 *
		 * @since 5.2.1
		 *
		 * @return array
		 */
		public function get_columns() {
			return self::$columns;
		}

		/**
		 * Get a list of sortable columns. The format is:
		 * 'internal-name' => 'orderby'
		 * or
		 * 'internal-name' => array( 'orderby', true ).
		 *
		 * The second format will make the initial sorting order be descending
		 *
		 * @since 5.2.1
		 *
		 * @return array
		 */
		protected function get_sortable_columns() {
			$first6_columns   = array_keys( self::get_column_names() );
			$sortable_columns = array();

			unset( $first6_columns[0], $first6_columns[9] ); // id column.
			// data column.

			/*
			 * Actual sorting still needs to be done by prepare_items.
			 * specify which columns should have the sort icon.
			 *
			 * The second bool param sets the colum sort order - true ASC, false - DESC or unsorted.
			 */
			foreach ( $first6_columns as $value ) {
				$sortable_columns[ $value ] = array( $value, false );
			}

			return $sortable_columns;
		}

		/**
		 * Text displayed when no user data is available.
		 *
		 * @since 5.2.1
		 *
		 * @return void
		 */
		public function no_items() {
			\esc_html_e( 'No custom notiications found', 'wp-security-audit-log' );
		}

		/**
		 * Fetch table data from the WordPress database.
		 *
		 * @since 5.2.1
		 *
		 * @return array
		 */
		public function fetch_table_data() {

			$search_sql = '';

			$search_string = self::escaped_search_input();

			if ( '' !== $search_string ) {
				global $wpdb;

				$search_string = $wpdb->esc_like( $search_string );

				$search_sql = 'AND (';
				foreach ( array_keys( self::$table::get_fields() ) as $value ) {
					$search_sql .= $value . ' LIKE "%' . $search_string . '%" OR ';
				}

				$search_sql = \rtrim( $search_sql, ' OR ' ) . ') ';
			}

			// Set query order arguments.
			$order_by = (string) isset( self::$query_args['order_by'] ) ? self::$query_args['order_by'] : false;
			$order    = (string) isset( self::$query_args['order'] ) ? self::$query_args['order'] : false;

			$events = self::$table::load_array_ordered_by( (string) $order_by, \strtoupper( (string) $order ), $search_sql );

			$this->items = $events;

			return $this->items;
		}

		/**
		 * Returns the current query
		 *
		 * @return array
		 *
		 * @since 5.2.1
		 */
		public static function get_query_occ(): array {
			return self::$query_occ;
		}

		/**
		 * Render a column when no column specific method exists.
		 *
		 * Use that method for common rendering and separate columns logic in different methods. See below.
		 *
		 * @param array  $item        - Array with the current row values.
		 * @param string $column_name - The name of the currently processed column.
		 *
		 * @return mixed
		 *
		 * @since 5.2.1
		 */
		public function column_default( $item, $column_name ) {
			return self::format_column_value( $item, $column_name );
		}

		/**
		 * Render a column when no column specific method exists.
		 *
		 * Use that method for common rendering and separate columns logic in different methods. See below.
		 *
		 * @param array  $item        - Array with the current row values.
		 * @param string $column_name - The name of the currently processed column.
		 *
		 * @return mixed
		 *
		 * @since 5.2.1
		 */
		public static function format_column_value( $item, $column_name ) {
			switch ( $column_name ) {
				case 'notification_title':
					// row actions to edit record.
					$query_args_view_data = array(
						'page'                   => ( isset( $_REQUEST['page'] ) ) ? \sanitize_text_field( \wp_unslash( $_REQUEST['page'] ) ) : Notifications::get_safe_view_name(),
						'action'                 => 'edit',
						'_wpnonce'               => \wp_create_nonce( 'bulk-custom-notifications' ),
						self::$table_name . '[]' => absint( $item['id'] ),
					);
					$admin_page_url       = \network_admin_url( 'admin.php' );
					$view_data_link       = \esc_url( \add_query_arg( $query_args_view_data, $admin_page_url ) );
					$actions['view_data'] = '<a href="' . $view_data_link . '#wsal-options-tab-custom-notification-edit">' . \esc_html__( 'Edit', 'wp-security-audit-log' ) . '</a>';

					$query_args_view_data['action']   = 'delete';
					$query_args_view_data['_wpnonce'] = \wp_create_nonce( 'bulk-custom-notifications' );
					$delete_data_link                 = \esc_url( \add_query_arg( $query_args_view_data, $admin_page_url ) );
					$actions['delete']                = '<a href="' . $delete_data_link . self::$hash_url . '">' . \esc_html__( 'Delete', 'wp-security-audit-log' ) . '</a>';

					$query_args_view_data['action']   = 'duplicate';
					$query_args_view_data['_wpnonce'] = \wp_create_nonce( 'bulk-custom-notifications' );
					$duplicate_data_link              = \esc_url( \add_query_arg( $query_args_view_data, $admin_page_url ) );
					$actions['duplicate']             = '<a href="' . $duplicate_data_link . self::$hash_url . '">' . \esc_html__( 'Duplicate', 'wp-security-audit-log' ) . '</a>';

					$style = ' style=""';

					if ( ! (bool) $item['notification_status'] ) {
						$style = ' style="color: red;"';
					}

					$query_args_view_data['action']   = 'change_status';
					$query_args_view_data['_wpnonce'] = \wp_create_nonce( 'bulk-custom-notifications' );
					$run_now_link                     = \esc_url( \add_query_arg( $query_args_view_data, $admin_page_url ) );
					$actions['change_status']         = '<a href="' . $run_now_link . self::$hash_url . '">' . ( ( $item['notification_status'] ) ? \esc_html( 'Disable', 'wp-security-audit-log' ) : \esc_html( 'Enable', 'wp-security-audit-log' ) ) . '</a>';

					return '<span' . $style . '>' . $item['notification_title'] . '</span>' . ( new \WP_List_Table() )->row_actions( $actions );
				case 'notification_status':
					return ( $item['notification_status'] ) ? __( 'Enabled', 'wp-security-audit-log' ) : __( 'Disabled', 'wp-security-audit-log' );
				case 'notification_phone':
					return ( $item['notification_phone'] ) ? $item['notification_phone'] : __( 'Default phone if set', 'wp-security-audit-log' );
				case 'notification_slack':
					return ( $item['notification_slack'] && ! empty( $item['notification_slack'] ) ) ? $item['notification_slack'] : __( 'Default channel if set', 'wp-security-audit-log' );
				case 'notification_email':
					return ( $item['notification_email'] ) ? $item['notification_email'] : __( 'Default address if set', 'wp-security-audit-log' );
				case 'created_on':
					return $item['created_on']
						? DateTime_Formatter_Helper::get_formatted_date_time( $item['created_on'], 'datetime', true, true )
						: '<i>' . __( 'Unknown', 'wp-security-audit-log' ) . '</i>';
				default:
					return isset( $item[ $column_name ] )
						? esc_html( $item[ $column_name ] )
						: 'Column "' . esc_html( $column_name ) . '" not found';
			}
		}

		/**
		 * Get value for checkbox column.
		 *
		 * The special 'cb' column
		 *
		 * @param object $item - A row's data.
		 *
		 * @return string Text to be placed inside the column < td > .
		 *
		 * @since 5.2.1
		 */
		protected function column_cb( $item ) {
			return sprintf(
				'<label class="screen-reader-text" for="' . self::$table_name . '_' . $item['id'] . '">' . sprintf(
					// translators: The column name.
					__( 'Select %s' ),
					'id'
				) . '</label>'
				. '<input type="checkbox" name="' . self::$table_name . '[]" id="' . self::$table_name . '_' . $item['id'] . '" value="' . $item['id'] . '" />'
			);
		}

		/**
		 * Returns an associative array containing the bulk actions.
		 *
		 * @since 5.2.1
		 *
		 * @return array
		 */
		public function get_bulk_actions() {
			$actions = array();
			if ( Settings_Helper::current_user_can( 'view' ) ) {
				/**
				 * On hitting apply in bulk actions the url paramas are set as
				 * ?action=bulk-download&paged=1&action2=-1.
				 *
				 * Action and action2 are set based on the triggers above or below the table
				 */
				$actions = array(
					'delete'  => __( 'Delete', 'wp-security-audit-log' ),
					'disable' => __( 'Disable', 'wp-security-audit-log' ),
					'enable'  => __( 'Enable', 'wp-security-audit-log' ),
				);
			}

			return $actions;
		}

		/**
		 * Process actions triggered by the user.
		 *
		 * @since 5.2.1
		 */
		public function handle_table_actions() {
			if ( ! isset( $_REQUEST[ self::$table_name ] ) || ( isset( $_REQUEST['action2'] ) && -1 === (int) $_REQUEST['action2'] ) ) {
				return;
			}

			/**
			 * Note: Table bulk_actions can be identified by checking $_REQUEST['action'] and $_REQUEST['action2'].
			 *
			 * Action - is set if checkbox from top-most select-all is set, otherwise returns -1
			 * Action2 - is set if checkbox the bottom-most select-all checkbox is set, otherwise returns -1
			 */

			// check for individual row actions.
			$the_table_action = $this->current_action();

			if ( 'view_data' === $the_table_action ) {
				if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
					$this->graceful_exit();
				}
				$nonce = \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) );
				// verify the nonce.
				if ( ! wp_verify_nonce( $nonce, 'view_data_nonce' ) ) {
					$this->invalid_nonce_redirect();
				} elseif ( isset( $_REQUEST[ self::$table_name . '_id' ] ) ) {
					$this->page_view_data( absint( $_REQUEST[ self::$table_name . '_id' ] ) );
					// $this->graceful_exit();
				}
			}

			// check for table bulk actions.
			if ( ( ( isset( $_REQUEST['action'] ) && 'delete' === $_REQUEST['action'] ) || ( isset( $_REQUEST['action2'] ) && 'delete' === $_REQUEST['action2'] ) ) && Settings_Helper::current_user_can( 'view' ) ) {
				if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
					$this->graceful_exit();
				}
				$nonce = \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) );
				// verify the nonce.
				/**
				 * Note: the nonce field is set by the parent class
				 * wp_nonce_field( 'bulk-' . $this->_args['plural'] );.
				 */
				if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
					$this->invalid_nonce_redirect();
				} elseif ( isset( $_REQUEST[ self::$table_name ] ) && \is_array( $_REQUEST[ self::$table_name ] ) ) {
					foreach ( \wp_unslash( $_REQUEST[ self::$table_name ] ) as $id ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						self::$table::delete_by_id( (int) $id, self::$wsal_db );
					}
				}
				?>
				<script>
					window.location = "<?php echo \remove_query_arg( array( 'action', '_wpnonce', self::$table_name, '_wp_http_referer', 'action2' ) ) . \esc_attr( self::$hash_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
				</script>
				<?php
			}

			// check for table bulk actions.
			if ( ( ( isset( $_REQUEST['action'] ) && 'duplicate' === $_REQUEST['action'] ) || ( isset( $_REQUEST['action2'] ) && 'duplicate' === $_REQUEST['action2'] ) ) && Settings_Helper::current_user_can( 'view' ) ) {
				if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
					$this->graceful_exit();
				}
				$nonce = \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) );
				// verify the nonce.
				/**
				 * Note: the nonce field is set by the parent class
				 * wp_nonce_field( 'bulk-' . $this->_args['plural'] );.
				 */
				if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
					$this->invalid_nonce_redirect();
				} elseif ( isset( $_REQUEST[ self::$table_name ] ) && \is_array( $_REQUEST[ self::$table_name ] ) ) {
					foreach ( \wp_unslash( $_REQUEST[ self::$table_name ] ) as $id ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						self::$table::duplicate_by_id( (int) $id, self::$wsal_db );
					}
				}
				?>
				<script>
					jQuery('body').addClass('has-overlay');
					window.location = "<?php echo \remove_query_arg( array( 'action', '_wpnonce', self::$table_name, '_wp_http_referer', 'action2' ) ) . \esc_attr( self::$hash_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
				</script>
				<?php
			}

			// check for table bulk actions.
			if ( ( ( isset( $_REQUEST['action'] ) && 'disable' === $_REQUEST['action'] ) || ( isset( $_REQUEST['action2'] ) && 'disable' === $_REQUEST['action2'] ) ) && Settings_Helper::current_user_can( 'view' ) ) {
				if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
					$this->graceful_exit();
				}
				$nonce = \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) );
				// verify the nonce.
				/**
				 * Note: the nonce field is set by the parent class
				 * wp_nonce_field( 'bulk-' . $this->_args['plural'] );.
				 */
				if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
					$this->invalid_nonce_redirect();
				} elseif ( isset( $_REQUEST[ self::$table_name ] ) && \is_array( $_REQUEST[ self::$table_name ] ) ) {
					foreach ( \wp_unslash( $_REQUEST[ self::$table_name ] ) as $id ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						self::$table::disable_enable_by_id( (int) $id, false, self::$wsal_db );
					}
				}
			}

			// check for table bulk actions.
			if ( ( ( isset( $_REQUEST['action'] ) && 'enable' === $_REQUEST['action'] ) || ( isset( $_REQUEST['action2'] ) && 'enable' === $_REQUEST['action2'] ) ) && Settings_Helper::current_user_can( 'view' ) ) {
				if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
					$this->graceful_exit();
				}
				$nonce = \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) );
				// verify the nonce.
				/**
				 * Note: the nonce field is set by the parent class
				 * wp_nonce_field( 'bulk-' . $this->_args['plural'] );.
				 */
				if ( ! \wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
					$this->invalid_nonce_redirect();
				} elseif ( isset( $_REQUEST[ self::$table_name ] ) && \is_array( $_REQUEST[ self::$table_name ] ) ) {
					foreach ( \wp_unslash( $_REQUEST[ self::$table_name ] ) as $id ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						self::$table::disable_enable_by_id( (int) $id, true, self::$wsal_db );
					}
				}
			}

			// check for table bulk actions.
			if ( ( ( isset( $_REQUEST['action'] ) && 'change_status' === $_REQUEST['action'] ) || ( isset( $_REQUEST['action2'] ) && 'change_status' === $_REQUEST['action2'] ) ) && Settings_Helper::current_user_can( 'view' ) ) {
				if ( ! isset( $_REQUEST['_wpnonce'] ) ) {
					$this->graceful_exit();
				}
				$nonce = \sanitize_text_field( \wp_unslash( $_REQUEST['_wpnonce'] ) );
				// verify the nonce.
				/**
				 * Note: the nonce field is set by the parent class
				 * wp_nonce_field( 'bulk-' . $this->_args['plural'] );.
				 */
				if ( ! \wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
					$this->invalid_nonce_redirect();
				} elseif ( isset( $_REQUEST[ self::$table_name ] ) && \is_array( $_REQUEST[ self::$table_name ] ) ) {
					foreach ( \wp_unslash( $_REQUEST[ self::$table_name ] ) as $id ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						self::$table::disable_enable_by_id( (int) $id );
					}
				}
				?>
				<script>
					window.location = "<?php echo \remove_query_arg( array( 'action', '_wpnonce', self::$table_name, '_wp_http_referer', 'action2' ) ) . \esc_attr( self::$hash_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>";
				</script>
				<?php
			}
		}

		/**
		 * Stop execution and exit.
		 *
		 * @since 5.2.1
		 *
		 * @return void
		 */
		public function graceful_exit() {
			exit;
		}

		/**
		 * Die when the nonce check fails.
		 *
		 * @since 5.2.1
		 *
		 * @return void
		 */
		public function invalid_nonce_redirect() {
			wp_die(
				'Invalid Nonce',
				'Error',
				array(
					'response'  => 403,
					'back_link' => esc_url( \network_admin_url( 'users.php' ) ),
				)
			);
		}

		/**
		 * Returns the records to show per page.
		 *
		 * @return int
		 *
		 * @since 5.2.1
		 */
		public function get_records_per_page() {
			return $this->records_per_page;
		}

		/**
		 * Get the screen option per_page.
		 *
		 * @return int
		 *
		 * @since 5.2.1
		 */
		private function get_screen_option_per_page() {
			$this->get_wp_screen();
			$option = $this->wp_screen->get_option( 'per_page', 'option' );
			if ( ! $option ) {
				$option = str_replace( '-', '_', "{$this->wp_screen->id}_per_page" );
			}

			$per_page = (int) get_user_option( $option );
			if ( empty( $per_page ) || $per_page < 1 ) {
				$per_page = $this->wp_screen->get_option( 'per_page', 'default' );
				if ( ! $per_page ) {
					$per_page = $this->get_records_per_page();
				}
			}

			return $per_page;
		}

		/**
		 * Returns the columns array (with column name).
		 *
		 * @return array
		 *
		 * @since 5.2.1
		 */
		private static function get_column_names() {
			return self::$columns;
		}

		/**
		 * Adds a screen options to the current screen table.
		 *
		 * @param \WP_Hook $hook - The hook object to attach to.
		 *
		 * @return void
		 *
		 * @since 5.2.1
		 */
		public static function add_screen_options( $hook ) {
			$screen_options = array( 'per_page' => __( 'Records per page', 'wp-security-audit-log' ) );

			$result = array();

			array_walk(
				$screen_options,
				function ( &$a, $b ) use ( &$result ) {
					$result[ self::SCREEN_OPTIONS_SLUG . '_' . $b ] = $a;
				}
			);
			$screen_options = $result;

			foreach ( $screen_options as $key => $value ) {
				add_action(
					"load-$hook",
					function () use ( $key, $value ) {
						$option = 'per_page';
						$args   = array(
							'label'   => $value,
							'default' => (int) Settings_Helper::get_option_value( 'items-per-page', 10 ),
							'option'  => $key,
						);
						add_screen_option( $option, $args );
					}
				);
			}
		}

		/**
		 * Form table per-page screen option value.
		 *
		 * @since 5.2.1
		 *
		 * @param bool   $keep   Whether to save or skip saving the screen option value. Default false.
		 * @param string $option The option name.
		 * @param int    $value  The number of rows to use.
		 *
		 * @return mixed
		 */
		public static function set_screen_option( $keep, $option, $value ) {
			if ( false !== \strpos( $option, self::SCREEN_OPTIONS_SLUG . '_' ) ) {
				return $value;
			}

			return $keep;
		}

		/**
		 * Table navigation.
		 *
		 * @param string $which - Position of the nav.
		 */
		public function extra_tablenav( $which ) {
			// If the position is not top then render.

			// Show site alerts widget.
			// NOTE: this is shown when the filter IS NOT true.
		}

		/**
		 * Prints column headers, accounting for hidden and sortable columns.
		 *
		 * @since 5.2.1
		 *
		 * @param bool $with_id Whether to set the ID attribute or not.
		 */
		public function print_column_headers( $with_id = true ) {
			list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

			$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotValidated, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$current_url = remove_query_arg( 'paged', $current_url );

			// When users click on a column header to sort by other columns.
			if ( isset( $_GET['orderby'] ) ) {
				$current_orderby = \sanitize_text_field( \wp_unslash( $_GET['orderby'] ) );
				// In the initial view there's no orderby parameter.
			} else {
				$current_orderby = '';
			}

			// Not in the initial view and descending order.
			if ( isset( $_GET['order'] ) && 'desc' === $_GET['order'] ) {
				$current_order = 'desc';
			} else {
				// The initial view is not always 'asc', we'll take care of this below.
				$current_order = 'asc';
			}

			if ( ! empty( $columns['cb'] ) ) {
				static $cb_counter = 1;
				$columns['cb']     = '<label class="label-covers-full-cell" for="cb-select-all-' . $cb_counter . '">' .
				'<span class="screen-reader-text">' .
					/* translators: Hidden accessibility text. */
					__( 'Select All' ) .
				'</span>' .
				'</label>' .
				'<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
				++$cb_counter;
			}

			foreach ( $columns as $column_key => $column_display_name ) {
				$class          = array( 'manage-column', "column-$column_key" );
				$aria_sort_attr = '';
				$abbr_attr      = '';
				$order_text     = '';

				if ( in_array( $column_key, $hidden, true ) ) {
					$class[] = 'hidden';
				}

				if ( 'cb' === $column_key ) {
					$class[] = 'check-column';
				} elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ), true ) ) {
					$class[] = 'num';
				}

				if ( $column_key === $primary ) {
					$class[] = 'column-primary';
				}

				if ( isset( $sortable[ $column_key ] ) ) {
					$orderby       = isset( $sortable[ $column_key ][0] ) ? $sortable[ $column_key ][0] : '';
					$desc_first    = isset( $sortable[ $column_key ][1] ) ? $sortable[ $column_key ][1] : false;
					$abbr          = isset( $sortable[ $column_key ][2] ) ? $sortable[ $column_key ][2] : '';
					$orderby_text  = isset( $sortable[ $column_key ][3] ) ? $sortable[ $column_key ][3] : '';
					$initial_order = isset( $sortable[ $column_key ][4] ) ? $sortable[ $column_key ][4] : '';

					/*
					 * We're in the initial view and there's no $_GET['orderby'] then check if the
					 * initial sorting information is set in the sortable columns and use that.
					 */
					if ( '' === $current_orderby && $initial_order ) {
						// Use the initially sorted column $orderby as current orderby.
						$current_orderby = $orderby;
						// Use the initially sorted column asc/desc order as initial order.
						$current_order = $initial_order;
					}

					/*
					 * True in the initial view when an initial orderby is set via get_sortable_columns()
					 * and true in the sorted views when the actual $_GET['orderby'] is equal to $orderby.
					 */
					if ( $current_orderby === $orderby ) {
						// The sorted column. The `aria-sort` attribute must be set only on the sorted column.
						if ( 'asc' === $current_order ) {
							$order          = 'desc';
							$aria_sort_attr = ' aria-sort="ascending"';
						} else {
							$order          = 'asc';
							$aria_sort_attr = ' aria-sort="descending"';
						}

						$class[] = 'sorted';
						$class[] = $current_order;
					} else {
						// The other sortable columns.
						$order = strtolower( (string) $desc_first );

						if ( ! in_array( $order, array( 'desc', 'asc' ), true ) ) {
							$order = $desc_first ? 'desc' : 'asc';
						}

						$class[] = 'sortable';
						$class[] = 'desc' === $order ? 'asc' : 'desc';

						/* translators: Hidden accessibility text. */
						$asc_text = __( 'Sort ascending.' );
						/* translators: Hidden accessibility text. */
						$desc_text  = __( 'Sort descending.' );
						$order_text = 'asc' === $order ? $asc_text : $desc_text;
					}

					if ( '' !== $order_text ) {
						$order_text = ' <span class="screen-reader-text">' . $order_text . '</span>';
					}

					// Print an 'abbr' attribute if a value is provided via get_sortable_columns().
					$abbr_attr = $abbr ? ' abbr="' . esc_attr( $abbr ) . '"' : '';

					$column_display_name = sprintf(
						'<a href="%1$s' . self::$hash_url . '-target">' .
						'<span>%2$s</span>' .
						'<span class="sorting-indicators">' .
							'<span class="sorting-indicator asc" aria-hidden="true"></span>' .
							'<span class="sorting-indicator desc" aria-hidden="true"></span>' .
						'</span>' .
						'%3$s' .
						'</a>',
						esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ),
						$column_display_name,
						$order_text
					);
				}

				$tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
				$scope = ( 'th' === $tag ) ? 'scope="col"' : '';
				$id    = $with_id ? "id='$column_key'" : '';

				if ( ! empty( $class ) ) {
					$class = "class='" . implode( ' ', $class ) . "'";
				}

				echo "<$tag $scope $id $class $aria_sort_attr $abbr_attr>$column_display_name</$tag>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Generates the table navigation above or below the table
		 *
		 * @param string $which - Holds info about the top and bottom navigation.
		 *
		 * @since 5.2.1
		 */
		public function display_tablenav( $which ) {
			if ( 'top' === $which ) {
				wp_nonce_field( 'bulk-' . $this->_args['plural'] );
			}
			?>
			<div class="tablenav <?php echo esc_attr( $which ); ?>">

					<?php if ( $this->has_items() ) : ?>
				<div class="alignleft actions bulkactions">
						<?php $this->bulk_actions( $which ); ?>
				</div>
						<?php
					endif;
					$this->extra_tablenav( $which );
					?>

				<br class="clear" />
			</div>
			<?php
		}
// phpcs:disable
/* @premium:end */
// phpcs:enable
	}
}
