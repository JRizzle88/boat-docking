<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://joshdaleriley.com
 * @since      1.0.0
 *
 * @package    Boat_Docking
 * @subpackage Boat_Docking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boat_Docking
 * @subpackage Boat_Docking/admin
 * @author     Joshua Riley <jdaleriley@gmail.com>
 */
class Boat_Docking_Admin {

	public $bd_submissions_obj;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->bd_submissions_obj = new Reservations_List();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/boat-docking-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/boat-docking-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function get_settings_data() {
		return (object)[
			'display_options_section' => (object)[
				'id' => 'display_options_section',
				'title' => 'Display Options',
				'page' => 'bd_display',
				'description' => 'Tune up the Boat form.',
				'elements' => [
					[
						'name' => 'show_labels',
						'id' => 'show_labels',
						'label' => 'Labels',
						'type' => 'checkbox',
						'class' => 'array-class',
						'hint' => 'Show labels on the front end form.'
					],
					[
						'name' => 'show_placeholders',
						'id' => 'show_placeholders',
						'label' => 'Placeholders',
						'type' => 'checkbox',
						'class' => 'array-class-2',
						'hint' => 'Show placeholders on the front end form.'
					],
					[
						'name' => 'custom_css',
						'id' => 'custom_css',
						'label' => 'Custom CSS',
						'type' => 'textarea',
						'class' => '',
						'hint' => 'Add your custom styling.'
					],
					[
						'name' => 'custom_js',
						'id' => 'custom_js',
						'label' => 'Custom JavaScript',
						'type' => 'textarea',
						'class' => '',
						'hint' => 'Add your custom javascript.'
					]
				]
			],
			'color_options_section' => (object)[
				'id' => 'color_options_section',
				'title' => 'Color Options',
				'page' => 'bd_colors',
				'description' => 'Style the form to match your theme.',
				'elements' => [
					[
						'name' => 'primary_color',
						'id' => 'primary_color',
						'label' => 'Primary',
						'type' => 'color',
						'placeholder' => '#000000',
						'class' => '',
						'hint' => 'Set the primary color for your theme.'
					],
					[
						'name' => 'secondary_color',
						'id' => 'secondary_color',
						'label' => 'Secondary',
						'type' => 'color',
						'placeholder' => '#000000',
						'class' => '',
						'hint' => 'Set the secondary color for your theme.'
					]
				]
			],
			'email_options_section' => (object)[
				'id' => 'email_options_section',
				'title' => 'Email Options',
				'page' => 'db_email',
				'description' => 'Configure the communication options.',
				'elements' => [
					[
						'name' => 'email_addresses',
						'id' => 'email_addresses',
						'label' => 'Email Addresses',
						'type' => 'text',
						'placeholder' => '',
						'class' => '',
						'hint' => 'Input email addresses to be notified of bookings here, seperated by semi colons. I.e <code>bob@email.com;jim@email.com</code>'
					]
				]
			],
			'submission_options_section' => (object)[
				'id' => 'submission_options_section',
				'title' => 'Submission Options',
				'page' => 'db_submission',
				'description' => 'Configure the submission options.',
				'elements' => [
					[
						'name' => 'redirect_page',
						'id' => 'redirect_page',
						'label' => 'Redirect Page',
						'type' => 'wppage',
						'placeholder' => '',
						'class' => '',
						'hint' => 'Select the page to redirect to on successful submission'
					]
				]
			],
			'usage_options_section' => (object)[
				'id' => 'usage_options_section',
				'title' => 'Usage Options',
				'page' => 'db_usage',
				'description' => '',
				'elements' => [
					[
						'name' => '',
						'id' => '',
						'type' => 'html',
						'label' => '',
						'html' => '
		                    <h2>General Usage</h2>
		                    <p>Default Form: <code>[boat-docking-form type="default"]</code></p>
		                    <p>Inline Form: <code>[boat-docking-form type="inline"]</code></p>
		                    <h3>Extra Parameters</h3>
		                    <p>Title: <code>[boat-docking-form type="default" title="Reserve Your Dock!"]</code></p>
		                    <p>Class: <code>[boat-docking-form type="default" class="custom-boat-form"]</code></p>
		                    <p>Action: <code>[boat-docking-form type="default" action="http://customform.com/form"]</code></p>
		                    <h3>Custom Login</h3>
		                    <p>Login Form: <code>[boat-docking-login]</code></p>
						',
					]
				]
			]
		];
	}

	function add_boatdocking_options_page() {
	    add_menu_page(
	        'Boat Docking Reservations',
	        'Boat Docking',
	        'manage_options',
	        'boat-docking',
	        array($this, 'boatdocking_options_content'),
			'dashicons-sos'
	    );

		add_submenu_page(
			'boat-docking', // parent slug
			'Dock Reservations',
			'Submissions',
			'manage_options',
			'bd-reservations',
			array($this, 'boatdocking_reservations_content')
		);

		add_submenu_page(
			null, // hidden
			'Update Reservation', // page title
			'Update', // menu title
			'manage_options', // capability
			'bd-reservations_update', // menu slug
			array($this, 'boatdocking_reservations_update') // function/callback
		);

		add_submenu_page(
			null, // hidden
			'Create Reservation', // page title
			'Create', // menu title
			'manage_options', // capability
			'bd_reservations_create', // menu slug
			array($this, 'boatdocking_reservations_create') // function/callback
		);

		add_submenu_page(
			null, // hidden
			'Delete Reservation', // page title
			'Delete', // menu title
			'manage_options', // capability
			'bd_reservations_delete', // menu slug
			array($this, 'boatdocking_reservations_delete') // function/callback
		);
	}

	function boatdocking_upgrade() {
	    include 'upgrade-check.php';
		boat_docking_upgradecheck();
	}

	function boatdocking_options_content() {
	    include 'partials/boat-docking-admin-display.php';
	}

	function boatdocking_reservations_content() {
		include 'partials/boat-docking-admin-reservations.php';
	}

	function boatdocking_reservations_update() {
		include 'partials/boat-docking-admin-reservations-update.php';
	}

	function boatdocking_reservations_create() {
		include 'partials/boat-docking-admin-reservations-create.php';
	}

	function boatdocking_reservations_delete() {
		include 'partials/boat-docking-admin-reservations-delete.php';
	}

	function initialize_boatdocking_api() {
		$url_prefix = 'bd/api/v1';
		$routes = [
		        [
		            'url' => '/reservations',
		            'method' => 'GET',
		            'callback' => 'get_all_reservations'
		        ]
		];
		//include 'partials/boat-docking-admin-api.php';
		// foreach($routes as $route) {
	    register_rest_route( $url_prefix, '/reservations', array(
			'methods' => 'GET',
			'callback' => array($this, 'get_all_reservations'),
	        'args' => array(
				'id' => array(
					'validate_callback' => function($param, $request, $key) {
						return is_numeric( $param );
					}
				),
			),
		) );
	    //}
	}

	function get_all_reservations() {
        global $wpdb;

        $table_name = $wpdb->prefix . "bd_submissions";
        $posts = $wpdb->get_results("SELECT id, user_id, name, email, boat_length from $table_name");

		if (empty($posts)) {
			return new WP_Error('Nothing found.', 'There are no reservations.', array('status' => 404));
		}
		// result
		return $posts;
	}


	function initialize_boatdocking_settings() {
		// build the interface from the data defined at the beginning of the class
		$this->build_settings_fields($this->get_settings_data());
	}


	private function build_settings_fields($settings) {
		if(!empty($settings)) {
			foreach ($settings as $setting) {
				if (!empty($setting)) {
					if (!empty($setting->page) && !empty($setting->elements)) {
						if (get_option($setting->page) == false) {
				    	    add_option($setting->page);
				    	}
						// add settings section
						add_settings_section(
					        $setting->id, // ID used to identify this section and with which to register options
					        $setting->title, // Title to be displayed on the administration page
					        array($this, 'options_description'), // Callback used to render the description of the section
					        $setting->page // Page on which to add this section of options
					    );

						// add the settings fields
						foreach ($setting->elements as $element) {
							// add settings fields
							add_settings_field(
								$element['id'],
								$element['label'],
								array($this, 'options_callback'),
								$setting->page,
								$setting->id,
								array($element)
							);
							// register the settings fields
							register_setting(
						        $setting->page,
						        $element['id']
						    );
						}
					}
				}
			}
		}
	}


	function options_description($args) {
		if (isset($args) && isset($args['id']) && !empty($this->settings_data)) {
			echo $this->settings_data->{$args['id']}->description;
		}
	}

	function options_callback($args) {
		// Load all relevant arguments
		$type = $args[0]['type'];
		$id = $args[0]['id'];
		$name = !empty($args[0]['name']) ? $args[0]['name'] : $args[0]['id'];
		$hint = !empty($args[0]['hint']) ? $args[0]['hint'] : '';
		$class = !empty($args[0]['class']) ? $args[0]['class'] : '';
		$placeholder = !empty($args[0]['placeholder']) ? $args[0]['placeholder'] : '';
		$value = get_option($id);
		// Set $html to nothing to start with
		$html = "";
		// Create form elemet that is requested in the data
		if ($type == "checkbox") {
		    $html .= '<input type="checkbox" id="' . $id . '" name="' . $name . '" placeholder="' . $placeholder . '" value="1" class="' . $class . '" ' . checked(1, $value, false) . '/>';
		    $html .= '<label for="' . $id . '"> '  . $hint . '</label>';
		} elseif ($type == "text") {
	    	$html .= '<input type="text" id="' . $id . '" name="' . $name . '" placeholder="' . $placeholder . '" value="' . $value . '" />';
	    	$html .= '<label for="' . $id . '"> '  . $hint . '</label>';
		} elseif ($type == "color") {
	    	$html .= '<input type="text" id="' . $id . '" name="' . $name . '" placeholder="' . $placeholder . '" value="' . $value . '" style="border-left: 10px solid ' . $value . ';" />';
	    	$html .= '<label for="' . $id . '"> '  . $hint . '</label>';
		} elseif ($type == "textarea") {
		    $html .= '<label for="' . $id . '"> '  . $hint . '</label><br>';
		    $html .= '<textarea id="' . $id . '" cols="80" rows="10" name="' . $name . '" value="' . $value . '">' . $value . '</textarea>';
		} elseif ($type == "wppage") {
	    	$html .= wp_dropdown_pages(array(
	    		'echo' => false,
	    		'id' => $id,
	    		'name' => $name,
	    		'selected' => $value
	    	));
	    	$html .= '<label for="' . $id . '"> '  . $hint . '</label>';
		} elseif ($type == "html") {
			$html .= !empty($args[0]['html']) ? $args[0]['html'] : '';
		}
		// Render the HTML
		echo $html;
	}
}
