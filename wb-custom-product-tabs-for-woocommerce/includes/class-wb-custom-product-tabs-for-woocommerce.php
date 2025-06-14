<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/webbuilder143/
 * @since      1.0.0
 *
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/includes
 * @author     Web Builder 143 <webbuilder143@gmail.com>
 */
class Wb_Custom_Product_Tabs_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wb_Custom_Product_Tabs_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WB_CUSTOM_PRODUCT_TABS_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = WB_CUSTOM_PRODUCT_TABS_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.4.3';
		}
		$this->plugin_name = 'wb-custom-product-tabs-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wb-custom-product-tabs-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wb-custom-product-tabs-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wb-custom-product-tabs-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wb-custom-product-tabs-for-woocommerce-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-wb-custom-product-tabs-for-woocommerce-feedback.php';

		$this->loader = new Wb_Custom_Product_Tabs_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wb_Custom_Product_Tabs_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wb_Custom_Product_Tabs_For_Woocommerce_i18n();

		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wb_Custom_Product_Tabs_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/* product page tab */
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'product_data_tabs' );
		
		/* product page tab content */
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'product_data_panels' );
		
		/* save tab content */
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'process_product_meta',10,2);
		
		/**
		* @since 1.0.2 
		* register global tabs as custom post type 
		*/
		$this->loader->add_action( 'init', $plugin_admin, 'register_global_tabs',10,2);

		/**
		* @since 1.0.2 
		* register meta box for global tab custom post type
		*/
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_meta_box',10,2);

		/**
		* @since 1.0.2 
		* save meta box data for global tab custom post type
		*/
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_box_data', 10, 2);

		/**
		* @since 1.0.9 
		* Global tabs, Add new product links on plugins page
		*/
		$this->loader->add_filter('plugin_action_links_'.plugin_basename(WB_TAB_PLUGIN_FILENAME), $plugin_admin, 'plugin_action_links');

		/**
		* @since 1.1.0 
		* Nickname column in global tabs listing page
		*/
		$this->loader->add_filter('manage_'.WB_TAB_POST_TYPE.'_posts_columns', $plugin_admin, 'add_nickname_column');
		$this->loader->add_action('manage_'.WB_TAB_POST_TYPE.'_posts_custom_column' , $plugin_admin, 'add_nickname_column_data', 10, 2);

		/**
		 * Product categories/tags columns in global tabs listing page
		 * 
		 * @since 1.1.3
		 */
		$this->loader->add_filter('manage_'.WB_TAB_POST_TYPE.'_posts_columns', $plugin_admin, 'add_product_cat_tag_column');
		$this->loader->add_action('manage_'.WB_TAB_POST_TYPE.'_posts_custom_column' , $plugin_admin, 'add_product_cat_tag_column_data', 10, 2);

		/**
		 * YouTube Embed option in tab content editor
		 * 
		 * @since 1.1.5
		 */
		$this->loader->add_action('media_buttons', $plugin_admin, 'add_youtube_embed_button');


		/**
		 * YouTube Embed popup HTML
		 * 
		 * @since 1.1.5
		 */
		$this->loader->add_action('in_admin_header', $plugin_admin, 'add_youtube_embed_and_tab_edit_popup');


		/**
		 * Change log in upgrade notice
		 * 
		 * @since 1.1.5
		 */
		$this->loader->add_action('in_plugin_update_message-wb-custom-product-tabs-for-woocommerce/wb-custom-product-tabs-for-woocommerce.php', $plugin_admin, 'changelog_in_upgrade_notice', 10, 2 );

		/**
		* Review banner on global tabs page.
		* 	
		* @since 1.1.13
		*/
		$this->loader->add_action( 'admin_head-edit.php', $plugin_admin, 'global_tabs_page_review_banner' );


		/**
		* Alter the options in the tab content editor.
		* 	
		* @since 1.2.3
		*/
		$this->loader->add_action( 'mce_buttons', $plugin_admin, 'alter_editor_buttons', 10, 2 );
		

		/**
		* Add global tabs to polylang custom post type list.
		* 	
		* @since 1.2.4
		*/
		$this->loader->add_filter( 'pll_get_post_types', $plugin_admin, 'add_global_tabs_to_pll_post_type_list', 11 );


		/**
		* Register settings.
		* 	
		* @since 1.3.0
		*/
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings', 11 );

		/**
		* General settings menu.
		* 	
		* @since 1.3.0
		*/
		$this->loader->add_filter( 'admin_menu', $plugin_admin, 'settings_menu', 11 );


		/**
		* Review seeking banner.
		* 	
		* @since 1.4.0
		*/
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'review_banner', 10 );
		$this->loader->add_action( 'wp_ajax_wb_tabs_review_banner_dismiss', $plugin_admin, 'review_banner_ajax', 10 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'review_banner_non_ajax', 10 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wb_Custom_Product_Tabs_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter('woocommerce_product_tabs', $plugin_public, 'add_custom_tab');
		

		/**
		 * Shortcode to embed YouTube videos
		 * 
		 * @since 1.1.5
		 */
		add_shortcode('wb_cpt_youtube_embed_shortcode', array($plugin_public, 'add_youtube_embed_shortcode'));

		/**
		 * Show tab content by URL hash
		 * 
		 * @since 1.3.3
		 */
		$this->loader->add_action('wp_footer', $plugin_public, 'activate_tab_by_url');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wb_Custom_Product_Tabs_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Get product term IDs array
	 *
	 * @since     1.0.2
	 * @param     int 	   	product id
	 * @param     string 	term name
	 * @return    array    term IDs array
	 */
	public static function _get_product_term_ids( $product_id, $term ) {
		$terms = get_the_terms( $product_id, $term );
		$terms = ( $terms && is_array( $terms ) ? $terms : array() );

		/**
		 * 	Alter product term ids.
		 * 
		 * 	@since 1.2.4
		 * 	@param array    Term ids array.
		 * 	@param int      Product id.
		 * 	@param string   Term name.
		 * 	@return array    Term ids array.
		 */
		$term_ids = apply_filters( 'wb_cptb_product_term_ids', array_column( $terms, 'term_id' ), $product_id, $term );

		return is_array( $term_ids ) ? $term_ids : array();
	}


	/**
	 * Get custom tabs of a product
	 *
	 * @since     1.0.2
	 * @since     1.2.4 				Added compatibility for brands.
	 * @since     1.3.4 				Custom tab slug implemented.
	 * @param     WC_Product object   	$product  
	 * @param     boolean   			$sort  			Sort the product based on tab position
	 * @return    array     			Product tabs
	 */
	public static function get_product_tabs($product, $sort=false)
	{
		/** 
		*	Taking custom tabs 
		*/
		$product_id=(method_exists($product, 'get_id')===true ? $product->get_id() : $product->ID);

		$wb_tabs = $product->get_meta('wb_custom_tabs', true);	
		$wb_tabs = ( is_array( $wb_tabs ) ? $wb_tabs : array() );

		/* 
		*	Taking global tabs 
		*/

		/* Taking global tabs assigned via products. */
		$query = new WP_Query( array(
	        'post_type' => WB_TAB_POST_TYPE,
	        'posts_per_page' => -1,
	        'meta_query' => array(
	            array(
	                'key' => '_wb_tab_products',
	                'value' => esc_sql( "i:$product_id;" ),
	                'compare' => 'LIKE'
	            )
	        )
	    ));

	    $tab_ids = array(); // This is to prevent duplicate tabs while executing the second query.

	    if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				$tab_position = self::_get_global_tab_position( $post_id );		
				$tab_nickname = self::_get_global_tab_nickname( $post_id );		
				$tab_slug = self::_get_global_tab_slug( $post_id );		
				$wb_tabs[] = array(
					'title'=>get_the_title(), 
					'content'=>get_the_content(), 
					'tab_type'=>'global', 
					'position'=>$tab_position, 
					'nickname'=>$tab_nickname, 
					'slug'=>$tab_slug, 
					'tab_id'=>$post_id
				);
				$tab_ids[] = $post_id;
			}
		}


		/* Taking categories */
		$cat_id_arr=self::_get_product_category_ids($product_id);

		/* Taking tags */
		$tag_id_arr=self::_get_product_term_ids($product_id, 'product_tag');

		/* Taking brands */
		$brand_id_arr=self::_get_product_brand_ids($product_id);

		$tax_query = array(
			'relation'=>'OR',
	        array(
	            'taxonomy'=>'product_cat',
	            'field'=>'ID',
	            'terms'=>$cat_id_arr,
	            'include_children' => apply_filters('wb_cptb_include_child_category_tabs', false),
	        ),
	        array(
	            'taxonomy'=>'product_tag',
	            'field'=>'ID',
	            'terms'=>$tag_id_arr,
	        ),
	        array(
	            'taxonomy'=>'product_brand',
	            'field'=>'ID',
	            'terms'=>$brand_id_arr,
	            'include_children' => apply_filters('wb_cptb_include_child_brand_tabs', false),
	        ),
	    );

		$tax_query_not_exists = array(
			'relation'=>'AND',
	        array(
	            'taxonomy'=>'product_cat',
	            'operator' => 'NOT EXISTS',
	        ),
	        array(
	            'taxonomy'=>'product_tag',
	            'operator' => 'NOT EXISTS',
	        ),
	        array(
	            'taxonomy'=>'product_brand',
	            'operator' => 'NOT EXISTS',
	        ),
	    );


		// Add compatibility for thirdparty brand plugins.
	    $brand_taxonamies = self::_get_thirdparty_brand_taxonamies();

	    foreach ( $brand_taxonamies as $brand_taxonamy ) {
	    	if ( ! is_string( $brand_taxonamy ) ) {
	    		continue;
	    	}

	    	$brand_id_arr = self::_get_product_term_ids( $product_id, $brand_taxonamy );

	    	if ( ! empty( $brand_id_arr ) ) {
		    	$tax_query[] = array(
		            'taxonomy' 	=> $brand_taxonamy,
		            'field' 	=> 'ID',
		            'terms' 	=> $brand_id_arr,
		        );
		    }
			
			if ( taxonomy_exists( $brand_taxonamy ) ) {
				$tax_query_not_exists[] = array(
					'taxonomy' 	=> $brand_taxonamy,
					'operator' 	=> 'NOT EXISTS',
				);
			}
	    }
	    

		$query = new WP_Query(
			array(
				'post_type' => WB_TAB_POST_TYPE,
				'posts_per_page' => -1,
				'tax_query' => $tax_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			)
		 );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				if ( ! in_array( $post_id, $tab_ids ) ) { // Not already added.

					$tab_position=self::_get_global_tab_position($post_id);		
					$tab_nickname=self::_get_global_tab_nickname($post_id);
					$tab_slug = self::_get_global_tab_slug( $post_id );		
					$wb_tabs[] = array( 
						'title'=>get_the_title(), 
						'content'=>get_the_content(), 
						'tab_type'=>'global', 
						'position'=>$tab_position, 
						'nickname'=>$tab_nickname,
						'slug' => $tab_slug,
						'tab_id'=>$post_id
					);
				}
			}
		}

		if ( ! self::is_hide_not_assigned_global_tabs() ) {
			
			// Get global tabs not assigned with any category, tags, brands, etc.
			$query_not_exists = new WP_Query(
				array(
					'post_type' => WB_TAB_POST_TYPE,
					'posts_per_page' => -1,
					'tax_query' => $tax_query_not_exists, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				)
			);

			if ( $query_not_exists->have_posts() ) {
				while ( $query_not_exists->have_posts() ) {
					$query_not_exists->the_post();
					$post_id = get_the_ID();
					
					// Only add if the tab is not assigned to any product.
					$tab_products = self::_get_global_tab_products( $post_id );
					if ( empty( $tab_products ) ) {
						$tab_position=self::_get_global_tab_position($post_id);		
						$tab_nickname=self::_get_global_tab_nickname($post_id);
						$tab_slug = self::_get_global_tab_slug( $post_id );	
						$wb_tabs[]= array( 
							'title' => get_the_title(), 
							'content' => get_the_content(), 
							'tab_type' => 'global', 
							'position' => $tab_position, 
							'nickname' => $tab_nickname,
							'tab_slug' => $tab_slug, 
							'tab_id' => $post_id
						);
					}		
				}
			}
		}

		wp_reset_postdata();

		/* 
		*	If sort is true then sort the tabs based on Tabs position 
		*/
		if($sort)
		{
			$position_arr=array_column($wb_tabs, 'position');
			array_multisort($position_arr, SORT_ASC, $wb_tabs);
		}

		return $wb_tabs;
	}

	/**
	 * 	Get global tab position.
	 * 
	 * 	@param int $id Tab ID.
	 * 	@return int Tab position.
	 */
	public static function _get_global_tab_position( $id ) {
		$tab_position=get_post_meta($id, '_wb_tab_position', true);
		return absint( "" === $tab_position ? self::get_default_tab_position() : $tab_position );
	}

	public static function _get_global_tab_nickname( $id ) {
		$tab_nickname=get_post_meta($id, '_wb_tab_nickname', true);
		return $tab_nickname===false ? '' : $tab_nickname;
	}

	/**
	 * 	Get global tab slug.
	 * 
	 * 	@since 1.3.4
	 * 	@param int $id Tab ID.
	 * 	@return string Tab slug.
	 */
	public static function _get_global_tab_slug( $id ) {
		$tab_slug = get_post_meta( $id, '_wb_tab_slug', true );
		return ( $tab_slug === false ? '' : $tab_slug );
	}

	/**
	 * Get product category IDs array
	 *
	 * @since     1.1.9
	 * @param     int 	   	product id
	 * @return    int[]    	category IDs array
	 */
	private static function _get_product_category_ids($product_id)
	{
		$category_ids = array();
		if(apply_filters('wb_cptb_include_parent_category_tabs', true)) 
		{
			$category_ids = wc_get_product_cat_ids($product_id);
		}else
		{
			$category_ids = wc_get_product_term_ids($product_id, 'product_cat');
		}

		return $category_ids;
	}


	/**
	 * 	Get third party brand taxonamies.
	 * 
	 *  @since  1.2.4
	 * 	@param  string[] Brand taxonamies
	 * 	@return string[] Brand taxonamies
	 */
	public static function _get_thirdparty_brand_taxonamies() {
		
		/**
		 * 	Alter third party brand taxonamies.
		 * 
		 *  @since  1.2.4
		 * 	@param  string[] Brand taxonamies
		 * 	@return string[] Brand taxonamies
		 */
		$brand_taxonomy_arr = apply_filters( 'wb_cptb_thirdparty_brand_taxonamies', array( 'pwb-brand' ) ) ;

		return is_array( $brand_taxonomy_arr ) ? $brand_taxonomy_arr : array();
	}


	/**
	 * 	Default tab position.
	 * 	
	 * 	@since 1.3.0
	 * 	@return int Default tab position.
	 */
	public static function get_default_tab_position() {
		return get_option('wb_cptb_default_tab_position', 1);
	}

	/**
	 * 	Get tab heading visibility.
	 * 
	 * 	@since 1.3.0
	 * 	@return bool Tab heading visibility.
	 */
	public static function get_tab_heading_visibility() {
		return wc_string_to_bool( get_option('wb_cptb_hide_tab_heading', 0) );
	}

	/**
	 * 	Is hide global tabs if not assigned with any category, tags, brands, etc.
	 * 
	 * 	@since 1.3.0
	 * 	@return bool True for hide from all products if not assigned with any category, tags, brands, etc.	
	 */
	public static function is_hide_not_assigned_global_tabs() {
		return wc_string_to_bool( get_option('wb_cptb_global_tabs_behavior', 1) );
	}


	/**
	 * 	Get global tab products.
	 * 
	 * 	@since  1.3.1
	 * 	@param 	int 		$id 	Tab ID.
	 * 	@return array 		Array of product ids associated with the tab.
	 */
	public static function _get_global_tab_products( $id ) {
		$products = get_post_meta( $id, '_wb_tab_products', true );
		return is_array( $products ) ? $products :  array();
	}


	/**
	 * Get product brand IDs array
	 *
	 * @since     1.4.0
	 * @param     int 	   	product id
	 * @return    int[]    	brand IDs array
	 */
	private static function _get_product_brand_ids( $product_id ) {
		$brand_ids = array();
		if ( apply_filters('wb_cptb_include_parent_brand_tabs', true) ) {
			$brand_ids = self::_get_product_all_brand_ids( $product_id );
		} else {
			$brand_ids = wc_get_product_term_ids( $product_id, 'product_brand' );
		}

		return $brand_ids;
	}


	/**
	 * Get all product brands for a product by ID, including hierarchy
	 *
	 * @since  1.4.0
	 * @param  int $product_id Product ID.
	 * @return array
	 */
	private static function _get_product_all_brand_ids( $product_id ) {
		$product_brands = wc_get_product_term_ids( $product_id, 'product_brand' );

		foreach ( $product_brands as $product_brand ) {
			$product_brands = array_merge( $product_brands, get_ancestors( $product_brand, 'product_brand' ) );
		}

		return $product_brands;
	}
}
