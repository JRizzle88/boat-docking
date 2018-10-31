<?php

class Reservations_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {
        //require_once(ABSPATH . 'wp-admin/includes/template.php' );
		parent::__construct([
			'singular' => __( 'Reservation', 'boat-docking' ),
			'plural'   => __( 'Reservations', 'boat-docking' ),
			'ajax'     => false,
            'screen'      => 'interval-list',
		]);

	}

    /**
     * Retrieve customer’s data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_bd_submissions( $per_page = 20, $page_number = 1 ) {

      global $wpdb;

      $sql = "SELECT * FROM {$wpdb->prefix}bd_submissions";

      if ( ! empty( $_REQUEST['orderby'] ) ) {
        $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
        $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
      }

      $sql .= " LIMIT $per_page";

      $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


      $result = $wpdb->get_results( $sql, 'ARRAY_A' );

      return $result;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_bd_submission( $id ) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}bd_submissions",
            [ 'id' => $id ],
            [ '%d' ]
        );
    }

    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}bd_submissions";

        return $wpdb->get_var( $sql );
    }

    /** Text displayed when no customer data is available */
    public function no_items() {
        _e( 'No customers avaliable.', 'boat-docking' );
    }

    /**
    * Method for name column
    *
    * @param array $item an array of DB data
    *
    * @return string
    */
    function column_name( $item ) {

        // create a nonce
        $delete_nonce = wp_create_nonce( 'bd_delete_bd_submission' );

        $title = '<strong>' . sprintf( '<a href="?page=%s&id=%s&_wpnonce=%s">'.$item["name"].'</a>', esc_attr( 'bd-reservations_update' ), absint($item['id']), $delete_nonce )  . '</strong>';

        $actions = [
            //'delete' => sprintf( '<a href="?page=%s&id=%s&_wpnonce=%s">Delete</a>', esc_attr( 'bd-reservations_delete' ), absint($item['id']), $delete_nonce ),
            'edit' => sprintf( '<a href="?page=%s&id=%s&_wpnonce=%s">Edit</a>', esc_attr( 'bd-reservations_update' ), absint($item['id']), $delete_nonce )
        ];

        return $title . $this->row_actions($actions);
    }

    /**
     * Render a column when no column specific method exists.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            
            case 'email':
				return '<a href="mailto:'.$item[ $column_name ].'">'.$item[ $column_name ].'</a>';
            case 'notes':
			case 'boat_length':
            case 'name':
			case 'admin_notes':
				return stripslashes_deep($item[ $column_name ]);
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        //var_dump($item);
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'name'    => __( 'Name', 'boat-docking' ),
            'email' => __( 'Email', 'boat-docking' ),
            'boat_length'    => __( 'Boat Length', 'boat-docking' ),
            'notes'    => __( 'Notes', 'boat-docking' ),
			'admin_notes'    => __( 'Admin Notes', 'boat-docking' ),
        ];

        return $columns;
    }

    /**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array( 'name', true ),
			'email' => array( 'email', true ),
            'boat_length' => array( 'email', true ),
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'bd_submissions_per_page', 20 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_bd_submissions( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'bd_delete_bd_submission' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_customer( absint( $_GET['customer'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_bd_submission( $id );
			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}
}
