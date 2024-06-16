<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://abc.com
 * @since      1.0.0
 *
 * @package    Wp_Admin_Url_Change_Plugin
 * @subpackage Wp_Admin_Url_Change_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Admin_Url_Change_Plugin
 * @subpackage Wp_Admin_Url_Change_Plugin/admin
 * @author     Abhishek tripathi <abhisheksaurabh78663@gmail.com>
 */
class Wp_Admin_Url_Change_Plugin_Admin {

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

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Admin_Url_Change_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Admin_Url_Change_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-admin-url-change-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Admin_Url_Change_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Admin_Url_Change_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-admin-url-change-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function abhi_top_lvl_menu(){
 
		add_menu_page(
			'Change admin url', // page <title>Title</title>
			'Admin URL change', // link text
			'manage_options', // user capabilities
			'rudr_slider', // page slug
			array( $this,'abhi_slider_page_callback' ), // this function prints the page content
			'dashicons-images-alt2', // icon (from Dashicons for example)
			4 // menu position
		);
	}
	 
	public function abhi_slider_page_callback(){
		?>
		<div class="wrap">
			<h1><?php echo get_admin_page_title() ?></h1>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'rudr_slider_settings' ); // settings group name
					do_settings_sections( 'rudr_slider' ); // just a page slug
					submit_button(); // "Save Changes" button
				?>
			</form>
		</div>
	<?php
	}

	public function abhi_settings_fields(){

		// I created variables to make the things clearer
		$page_slug = 'rudr_slider';
		$option_group = 'rudr_slider_settings';
	
		// 1. create section
		add_settings_section(
			'rudr_section_id', // section ID
			'', // title (optional)
			'', // callback function to display the section (optional)
			$page_slug
		);
	
		// 2. register fields
		register_setting( $option_group, 'slider_on', 'rudr_sanitize_checkbox' );
		register_setting( $option_group, 'num_of_slides', 'absint' );
		register_setting( $option_group, 'adminurl', 'sanitize_text_field' );
	
		// 3. add fields
		add_settings_field(
			'slider_on', // 1. Field ID
			'Display slider',  // 2. Field title
			array( $this, 'rudr_checkbox'), // function to print the field // 3. Callback function to display the field
			$page_slug,
			'rudr_section_id' // section ID
		);
	
		add_settings_field(
			'num_of_slides',
			'Number of slides',
			array( $this, 'rudr_number'),
			$page_slug,
			'rudr_section_id',
			array(
				'label_for' => 'num_of_slides',
				'class' => 'hello', // for <tr> element
				'name' => 'num_of_slides' // pass any custom parameters
			),
			
		);
		add_settings_field(
			'adminurl',
			'Admin url',
			array( $this, 'rudr_text'),
			$page_slug,
			'rudr_section_id',
			array(                 // Additional arguments passed to the callback function
				'label_for' => 'adminurl', // Matches the ID of the field
				'name'      => 'adminurl'
			)
			
		
		);
	
	}
	// custom callback function to print field HTML
	public function rudr_number( $args ){
		printf(
			'<input type="number" id="%s" name="%s" value="%d" />',
			$args[ 'name' ],
			$args[ 'name' ],
			get_option( $args[ 'name' ], 2 ) // 2 is the default number of slides
		);
	}
	// custom callback function to print checkbox field HTML
	public function rudr_checkbox( $args ) {
		$value = get_option( 'slider_on' );
		// $url = get_option( 'admin_url', false );
		// ( $url ) ?  $url :  'hello';
		// var_dump($url);echo 'hi';
		?>
			<label>
				<input type="checkbox" name="slider_on" <?php checked( $value, 'on' ) ?> /> Yes
			</label><br>
			
		<?php
	}

	public function rudr_text( $args ) {
		//$value = get_option( 'slider_on' );
		$admin_url = get_option( 'adminurl', '' );
		// ( $url ) ?  $url :  'hello';
		//var_dump($url);echo 'hi';
		?>
			
			<label>
				<!-- <input type="text" name="admin_url" placeholder="Enter value " value= "<?php //echo get_option('admin_url'); ?> " />  -->

				<input type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="adminurl" value="<?php echo esc_attr( $admin_url ); ?>" placeholder="Enter Admin URL" />
			</label>
		<?php
	}

	// custom sanitization function for a checkbox field
	public function rudr_sanitize_checkbox( $value ) {
		
		return 'on' == $value ? 'yes' : 'no';
	}
	

}
