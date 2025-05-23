<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/webbuilder143/
 * @since      1.0.0
 *
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/public
 * @author     Web Builder 143 <webbuilder143@gmail.com>
 */
class Wb_Custom_Product_Tabs_For_Woocommerce_Public {

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
		
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wb-custom-product-tabs-for-woocommerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ).'js/wb-custom-product-tabs-for-woocommerce-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * 	Show custom tab on product details page
	 * 
	 *	@since 1.0.0
	 *	@since 1.1.4 	[Fix] Divi layout builder is not loading
	 *	@since 1.3.4 	Custom tab slug implemented.
	 */
	public function add_custom_tab($tabs)
	{
		global $product,$post;
		
		if(!is_product() || !is_object($product) || (is_object($product) && !is_a($product, 'WC_Product')))
		{
			return $tabs;
		}

		if ( ! is_array( $tabs ) ) {
		    $tabs = array();
		}

		$post_backup = $post;
		$wb_tabs=Wb_Custom_Product_Tabs_For_Woocommerce::get_product_tabs($product);
		$post = $post_backup;
		$wb_tabs = apply_filters('wb_cptb_alter_tabs', $wb_tabs, $product);

		$wb_inc=0;
		foreach($wb_tabs as $key=>$tab_data)
		{		
			if(trim($tab_data['title'])!="")
			{
				$wb_inc++;

				// Filter to alter tab content.
				$tab_data = apply_filters('wb_cptb_alter_tab_content', $tab_data, $product);

				$slug = isset( $tab_data['slug'] ) ? $tab_data['slug'] : '';

				if ( "" !== $slug ) { // Check for duplicates.
					$original_slug = $slug;
					$counter = $wb_inc;

					while ( array_key_exists( $slug, $tabs ) ) {
	                    $slug = $original_slug . '-' . $counter++;
	                }
	            } else {
	            	$original_slug = 'wb_cptb_';
	            	$slug = $original_slug . $wb_inc;
	            	$counter = $wb_inc;

	            	while ( array_key_exists( $slug, $tabs ) ) {
	                    $slug = $original_slug . $counter++;
	                }
	            }
			
				$tabs[ $slug ]=array(
					'title'=> trim($tab_data['title']),
					'priority'=>$tab_data['position'],
					'callback'=> array($this, 'set_tab_content'),
					'content'=>$tab_data['content']
				);
			}
		}
		
		return $tabs;
	}

	/**
	 *	Print custom tab HTML on product details page
	 * 
	 * 	@since 1.0.0
	 * 	@since 1.1.7 	New filter: `wb_cptb_hide_heading_in_tab_content` to hide the tab heading
	 */
	public function set_tab_content($key, $tab_data)
	{
		$hide_heading = Wb_Custom_Product_Tabs_For_Woocommerce::get_tab_heading_visibility();
	
		/**
		 * 	Hide tab heading in the tab content
		 * 	Default: false
		 * 	
		 * 	@since 1.1.7
		 * 	@since 1.2.4        The default value changed from `true` to `false`.
		 * 	@param bool  		True for hidden
		 * 	@param string|int  	Tab id/key
		 */
		$hide_heading = apply_filters('wb_cptb_hide_heading_in_tab_content', $hide_heading, $key);
		
		if(!$hide_heading)
		{
		?>
			<h2 class="wb_cptb_title"><?php echo esc_html( $tab_data['title'] ); ?></h2>
		<?php
		}
		?>
		<div class="wb_cptb_content">
			<?php echo do_shortcode( wp_kses_post( wpautop( stripslashes( $tab_data['content'] ) ) ) );?>
		</div>
		<?php
	}


	/**
	* 	Add YouTube embed shortcode
	* 
	* 	@since 1.1.5
	* 	@since 1.2.0   Dimensions to allow percentage values.
	*/
	public function add_youtube_embed_shortcode($atts)
	{
		$atts = shortcode_atts( array(
			'video_id' => '',
			'width'    => '560',
			'height'   => '315',
		), $atts, 'wb_cpt_youtube_shortcode');

		if("" === $atts['video_id'])
		{
			return '';
		}

		return '<iframe width="' . esc_attr($atts['width']) . '" height="' . esc_attr($atts['height']) . '" src="https://www.youtube.com/embed/' . esc_attr($atts['video_id']) . '"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
	}

	/**
	 * 	Show tab content by URL hash
	 * 
	 * 	@since 1.3.3
	 */
	public function activate_tab_by_url() {
	    if ( function_exists('is_product') && is_product() ) {
		?>
			<script type="text/javascript">
				function wb_cptb_activate_tab_by_url(){
					let hash  = window.location.hash; 
					if ( hash && hash.toLowerCase().indexOf( '#tab-wb_cptb_' ) === 0) {
						jQuery('.woocommerce-tabs.wc-tabs-wrapper .tabs.wc-tabs li a[href="' + hash + '"]').trigger('click');
					}
				}
				jQuery(document).ready(function(){
					wb_cptb_activate_tab_by_url();
				});
				jQuery(window).on('hashchange', function() {
					wb_cptb_activate_tab_by_url();
				});
			</script>
	    <?php
		}
	}
}
