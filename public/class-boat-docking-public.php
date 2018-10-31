<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://joshdaleriley.com
 * @since      1.0.0
 *
 * @package    Boat_Docking
 * @subpackage Boat_Docking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Boat_Docking
 * @subpackage Boat_Docking/public
 * @author     Joshua Riley <jdaleriley@gmail.com>
 */
class Boat_Docking_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/boat-docking-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/boat-docking-public.js', array( 'jquery' ), $this->version, false);
	}

	public function boatdocking_shortcodes() {
		// submission form
		add_action('init', array($this, 'boat_docking_form_process'));
	    add_shortcode('boat-docking-form', array($this, 'boat_docking_form_shortcode'));

	    // login
	    add_shortcode('boat-docking-login', array($this, 'boat_docking_login_shortcode'));
	}

	function boat_docking_form_process() {
		global $wpdb;

		$table_name = $wpdb->prefix . "bd_submissions";
		if(isset($_POST['create'])) {
		    $name = (isset($_POST["cname"]) && !empty($_POST["cname"])) ? $_POST["cname"] : null;
		    $email = (isset($_POST["email"]) && !empty($_POST["email"])) ? $_POST["email"] : null;
		    $length = (isset($_POST["boat_length"]) && !empty($_POST["boat_length"])) ? $_POST["boat_length"] : null;
		    $notes = (isset($_POST["notes"]) && !empty($_POST["notes"])) ? $_POST["notes"] : null;

			//$sql = $wpdb->prepare("INSERT INTO {$table_name} (name, email, boat_length, notes) VALUES ({$name},{$email},{$length},{$notes})");
	        //var_dump($name);

	        $insert = $wpdb->insert($table_name, //table
				array('user_id' => get_current_user_id(), 'name' => $name, 'email' => $email, 'boat_length' => $length, 'notes' => $notes), //data
				array('%s', '%s', '%s')
			);
			if ($insert) {
				// send confrimation email
				$multiple_recipients = array(
					get_option('email_addresses')
				);
				$subj = get_bloginfo('name') . ' - Booking Request';
				$body = "The following booking request has been made:\n";
				$body .= "Name: " . $name . "\n";
				$body .= "Email: " . $email . "\n";
				$body .= "Boat Length: " . $length . "\n";
				$body .= "Extra Notes: " . $notes;
				wp_mail( $multiple_recipients, $subj, $body );

				// redirect to success page
				wp_safe_redirect(get_permalink(get_option('redirect_page')));
			}
		}
	}


	function boat_docking_form_shortcode($atts, $content = null) {
		$output = '';

	    $params = shortcode_atts( array(
	        'type' => 'default',
			'class' => '',
	        'action' => '',
			'title' => ''
	    ), $atts );

		switch($params[ 'type' ]) {
			case 'default':
				$typeClass = "bd-form-default";
			break;
			case 'inline':
				$typeClass = "bd-form-inline";
			break;
		}

		$show_labels = get_option('show_labels');
		$show_placeholders = get_option('show_placeholders');

		if($show_placeholders) {
			$placeholder = [
				'name' => 'Full Name',
				'email' => 'Email Address',
				'boat_length' => 'Boat Length',
				'notes' => 'Notes',
			];
		} else {
			$placeholder = [
				'name' => '',
				'email' => '',
				'boat_length' => '',
				'notes' => ''
			];
		}

		$custom_css = get_option('custom_css');
		$custom_js = get_option('custom_js');
		// create the style override
		/*$output .= '
		<style>
			.boat-docking-form .bd-form-element { background:' . get_option('primary_color') . '; color:' . get_option('secondary_color') . '; }
			.boat-docking-form .bd-form-element::-webkit-input-placeholder { color: ' . get_option('secondary_color') . '; }
			.boat-docking-form .bd-form-element:-moz-placeholder { color: ' . get_option('secondary_color') . '; opacity: 1; }
			.boat-docking-form .bd-form-element::-moz-placeholder { color: ' . get_option('secondary_color') . '; opacity: 1; }
			.boat-docking-form .bd-form-element:-ms-input-placeholder { color: ' . get_option('secondary_color') . '; }
		</style>
		';*/
		$output .= '
		<style>
			'. $custom_css . '
		</style>
		';
	    $output .= '<div class="boat-docking-form">';
			$output .= (isset($params['title'])) ? '<h3>'.$params['title'].'</h3>' : '';
			$output .= '<form name="dock-reservation" onsubmit="return form_validation()" class="' . $params['class'] . ' '.$typeClass.'" action="" method="POST">';
				$output .= '<span class="field">';
					$output .= ($show_labels) ? '<label for="name">Name</label><br>' : '';
					$output .= '<input type="text" value="" name="cname" class="bd-form-element" placeholder="'.$placeholder["name"].'" />';
				$output .= '</span>';
				$output .= '<span class="field">';
					$output .= ($show_labels) ? '<label for="email">Email Address</label><br>' : '';
					$output .= '<input type="email" value="" name="email" class="bd-form-element" placeholder="'.$placeholder["email"].'" />';
				$output .= '</span>';
				$output .= '<span class="field">';
					$output .= ($show_labels) ? '<label for="name">Boat Length</label><br>' : '';
					$output .= '<input type="text" value="" name="boat_length" class="bd-form-element" placeholder="'.$placeholder["boat_length"].'" />';
				$output .= '</span>';
				$output .= '<span class="field">';
					$output .= ($show_labels) ? '<label for="name">Notes</label><br>' : '';
					$output .= '<textarea value="" name="notes" class="bd-form-element" placeholder="'.$placeholder["notes"].'"></textarea>';
				$output .= '</span>';
				$output .= '<span class="field">';
					$output .= '<input type="submit" name="create" class="bd-form-element" value="Submit" />';
				$output .= '</span>';
			$output .= '</form>';

			$output .= '<script type="text/javascript">' . $custom_js . '</script>';
			?>
			<script type="text/javascript">
				function form_validation() {
					/* Check the Name for blank submission*/
					var name = document.forms["dock-reservation"]["cname"].value;
					if (name == "" || name == null) {
						alert("Name field must be filled.");
						return false;
					}

					/* Check the Email for invalid format */
					var email = document.forms["dock-reservation"]["email"].value;
					var at_position = email.indexOf("@");
					var dot_position = email.lastIndexOf(".");
					if (at_position < 1 || dot_position < at_position + 2 || dot_position + 2 >= email.length) {
						alert("Given email address is not valid.");
						return false;
					}

					/* Check the Boat Length for blank submission*/
					var length = document.forms["dock-reservation"]["boat_length"].value;
					if (length == "" || length == null) {
						alert("Boat Length field must be filled.");
						return false;
					}

				}
			</script>
		<?php
		$output .= '</div>';
	    return $output;
	}

	function boat_docking_login_shortcode() {
		$args = array(
			'redirect' => home_url(),
			'id_username' => '',
			'id_password' => '',
		);
		if (!is_user_logged_in()) {
			/*echo '
			<style>
				.bd-login form input { background:' . get_option('primary_color') . '; color:' . get_option('secondary_color') . '; }
				.bd-login form input::-webkit-input-placeholder { color: ' . get_option('secondary_color') . '; }
				.bd-login form input:-moz-placeholder { color: ' . get_option('secondary_color') . '; opacity: 1; }
				.bd-login form input::-moz-placeholder { color: ' . get_option('secondary_color') . '; opacity: 1; }
				.bd-login form input:-ms-input-placeholder { color: ' . get_option('secondary_color') . '; }
			</style>
			';*/
			echo '<div class="bd-login" style="max-width:400px; margin:0 auto;">';
				wp_login_form($args);
			echo '</div>';
		}
	}

}
