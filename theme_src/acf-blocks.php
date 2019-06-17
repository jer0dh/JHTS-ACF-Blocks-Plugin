<?php

/**
 *
 * Plugin Name:      <%= pkg.templateName %>
 * Plugin URI:       <%= pkg.templateUri %>
 * Description:      <%= pkg.description %>
 * Version:          <%= pkg.version %>
 * Author:           <%= pkg.author %>
 * Author URI:       <%= pkg.authorUri %>
 * License:          GPL-2.0+
 * License URI:      http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:      <%= pkg.name %>
 * Domain Path:      /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


define( 'ACF_BLOCKS_VERSION', '<%= pkg.version %>' );
define( 'ACF_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );


class Acf_Blocks_Plugin {

	private static $instance = null;

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct() {


		if ( self::$instance === null ) {
			// This plugin requires ACF to be loaded
			add_action( 'plugins_loaded', array( $this, 'load_acf_hooks' ) );


			self::$instance = $this;

			return self::$instance;

		} else {
			return self::$instance;
		}
	}

	public function load_acf_hooks() {

		if ( function_exists( 'acf_register_block_type' ) ) {
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			//Language file
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

			//Add other hooks and filters
			//	add_action( 'init', array( $this, '' ) );


			// Register front end scripts so they can be enqueued if needed
			// could have some overlap with theme code...use if needed
			//	add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// scripts to run in editor
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Some helper functions that could be placed in the class later

			require_once ACF_BLOCKS_PATH . 'includes/helper-functions.php';
			require_once ACF_BLOCKS_PATH . 'includes/image-functions.php';


			add_action( 'acf/init', array( $this, 'register_acf_block_types' ) );

		}
	}

	public function register_acf_block_types() {

		// register a basic content block.
		acf_register_block_type( array(
			'name'            => 'jhts_basic_content',
			'title'           => __( 'Basic Content' ),
			'description'     => __( 'A section of content' ),
			'render_template' => ACF_BLOCKS_PATH . 'template-parts/blocks/basic_content/basic_content.php',
			'category'        => 'formatting',
			'icon'            => 'admin-comments',
			'keywords'        => array( 'jhts blocks' ),  //up to 3 strings can be added - helpful when searching in editor
		//	'mode'            => 'preview',
			'alignment'       => 'full',
			'supports'        => array( 'align' => true, 'anchor' => true )
		) );


		// register a Raw HTML Placeholder block.
		acf_register_block_type( array(
			'name'            => 'jhts_raw_html',
			'title'           => __( 'Raw HTML' ),
			'description'     => __( 'A block that does not strip out anything.  Use with caution' ),
			'render_template' => ACF_BLOCKS_PATH . 'template-parts/blocks/raw_html/raw_html.php',
			'category'        => 'formatting',
			'icon'            => 'admin-comments',
			'keywords'        => array( 'jhts blocks' ),
			'mode'            => 'preview',
			'alignment'       => 'full',
			'supports'        => array( 'align' => true, 'anchor' => true )
		) );


	}

	/**
	 * scripts()
	 * Register scripts so they can be enqueued only when needed
	 */
	public function enqueue_scripts() {

//		wp_enqueue_script( 'acf-blocks-scripts', plugins_url( '/js/dist/scripts.js', __FILE__ ), array( 'jquery', 'simple-favorites' ), '1.0.0', true );

//		wp_localize_script( 'acf-blocks-scripts', 'fav_loc_fwp', $local_vars );
	}

	/**
	 * admin scripts()
	 * Editor/Admin scripts
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_script( 'acf-block-admin', plugins_url( '/js/admin-scripts.js', __FILE__ ), array( 'jquery' ), ACF_BLOCKS_VERSION, true );
	}


	/**
	 * Activate Plugin
	 */
	public static function activate() {
		// Do nothing
	} // END public static function activate

	/**
	 * Deactivate the plugin
	 */
	public static function deactivate() {
		// Do nothing
	} // END public static function deactivate

	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'acf-blocks',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}

if ( class_exists( 'Acf_Blocks_Plugin' ) ) {

	Acf_Blocks_Plugin::get_instance();
}
