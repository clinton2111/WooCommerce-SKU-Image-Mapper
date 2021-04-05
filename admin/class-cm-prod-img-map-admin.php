<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codemarketing.com
 * @since      1.0.0
 *
 * @package    Cm_Prod_Img_Map
 * @subpackage Cm_Prod_Img_Map/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cm_Prod_Img_Map
 * @subpackage Cm_Prod_Img_Map/admin
 * @author     Clinton D <3950377+clinton2111@users.noreply.github.com>
 */
class Cm_Prod_Img_Map_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

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
		 * defined in Cm_Prod_Img_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cm_Prod_Img_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cm-prod-img-map-admin.css', array(), $this->version, 'all' );

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
		 * defined in Cm_Prod_Img_Map_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cm_Prod_Img_Map_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cm-prod-img-map-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'ProgressBar.js ', plugin_dir_url( __FILE__ ) . '../node_modules/progressbar.js/dist/progressbar.min.js' );

	}

	public function add_options_page() {
		add_options_page(
			'WooCommerce Image Mapper',
			'WC Img Map',
			'manage_options',
			'cm_img_map', // page URL slug
			array( $this, 'display_options_page' )
		);
	}

	public function display_options_page() {
		include_once 'partials/cm-prod-img-map-admin-options-form.php';
	}


	function register_rest_routes() {
		register_rest_route( 'cm_img_map/api', '/begin_mapping', array(
			'methods'  => 'POST',
			'callback' => array( $this, 'beginMapping' ),
		) );
		register_rest_route( 'cm_img_map/api', '/mapping_progress', array(
			'methods'  => 'GET',
			'callback' => array( $this, 'getProgressUpdate' ),
		) );
	}

	function getProgressUpdate() {
		ob_start();
		session_start();
		session_write_close();
		$output             = array();
		$output['progress'] = $_SESSION['progress'];
		echo json_encode( $output );
	}

	function does_file_exists( $filename ) {
		global $wpdb;

		return intval( $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'" ) );
	}

	function updateProgress( $value ) {
		$this->wcLogger( 'progress-update', $value );
		session_start();
		$_SESSION['progress'] = $value;
		session_write_close();
	}

	function beginMapping() {
		try {
			$placeholder_id = get_option( 'placeholder_img_attachment_id' );

			$all_ids = get_posts( array(
				'post_type'   => 'product',
				'numberposts' => - 1,
				'post_status' => 'publish',
				'fields'      => 'ids',
			) );

			$total = count( $all_ids );
			$this->wcLogger( 'progress-update', '----------------------------------------------------' );
			foreach ( $all_ids as $index => $id ) {
				$product = wc_get_product( $id );

				$sku = $product->get_sku();
				$attach_id = $this->does_file_exists( $sku . '.jpg' );
				set_post_thumbnail( $id, $attach_id != 0 ? $attach_id : $placeholder_id );

				$progress = round( $index / $total, 2 );
				$this->updateProgress( $progress );
			}
			$this->updateProgress( round( 1, 2 ) );

			wp_send_json_success( 'All Products Have Been Updated' );
		} catch ( Exception $e ) {
			$this->wcLogger( 'progress-update', $e->getMessage() );
			wp_send_json_success( $e->getMessage() );
		}

	}

	function wcLogger( $identifier, $message ) {
		$logger = new WC_Logger();
		$logger->add( $identifier, $message );
	}

	public function media_selector_print_scripts() {

		$placeholder_attachment_post_id = get_option( 'placeholder_img_attachment_id', 0 );

		?>
		<script type='text/javascript'>

			jQuery(document).ready(function ($) {

				// Uploading files
				let file_frame;
				let wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				let set_to_post_id = <?php echo $placeholder_attachment_post_id; ?>; // Set this

				jQuery('#upload_image_button').on('click', function (event) {

					event.preventDefault();

					// If the media frame already exists, reopen it.
					if (file_frame) {
						// Set the post ID to what we want
						file_frame.uploader.uploader.param('post_id', set_to_post_id);
						// Open frame
						file_frame.open();
						return;
					} else {
						// Set the wp.media post id so the uploader grabs the ID we want when initialised
						wp.media.model.settings.post.id = set_to_post_id;
					}

					// Create the media frame.
					file_frame = wp.media.frames.file_frame = wp.media({
						title: 'Select a image to upload',
						button: {
							text: 'Use this image',
						},
						multiple: false	// Set to true to allow multiple files to be selected
					});

					// When an image is selected, run a callback.
					file_frame.on('select', function () {
						// We set multiple to false so only get one image from the uploader
						attachment = file_frame.state().get('selection').first().toJSON();

						// Do something with attachment.id and/or attachment.url here
						$('#image-preview').attr('src', attachment.url).css('width', 'auto');
						$('#image_attachment_id').val(attachment.id);

						// Restore the main post ID
						wp.media.model.settings.post.id = wp_media_post_id;
					});

					// Finally, open the modal
					file_frame.open();
				});

				// Restore the main ID when the add media button is pressed
				jQuery('a.add_media').on('click', function () {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
			});

		</script>
		<?php

	}
}
