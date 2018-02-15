<?php
/**
 * Plugin Name:   Astro Stakeholders
 * Description:   Contact Management Plugin.
 * Version:       0.1
 * Author:        Astro Web
 * Author URI:    http://astroweb.me
 * Text Domain:   astro-stakeholders
 * Domain Path:   /languages 
 */
if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

class Astro_Stakeholders_Plugin {
	/**
     * Initializes the plugin.
     */
	public function __construct() {
		// Setup
    	add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

    	// "STAKEHOLDER" Post Type and Taxonomies
		add_action( 'init', array( $this, 'stakeholder_post_type' ) );
		add_action( 'init', array( $this, 'stakeholder_taxonomies' ), 0 );

		// Change content output.
		add_filter ( 'the_content', array( $this, 'stakeholder_content' ) );
    }

    /**
     * Text Domain.
     */
    function load_textdomain() {
    	$plugin_dir = basename( dirname(__FILE__) ) . '/languages/';
		load_plugin_textdomain( 'astro-stakeholders', false, $plugin_dir );
	}

	/**
	 * "STAKEHOLDER" Post Type.
	 */
	function stakeholder_post_type() {
		$labels = array(
			'name'               => _x( 'Stakeholders', 'post type general name', 'astro-stakeholders' ),
			'singular_name'      => _x( 'Stakeholder', 'post type singular name', 'astro-stakeholders' ),
			'menu_name'          => _x( 'Stakeholders', 'admin menu', 'astro-stakeholders' ),
			'name_admin_bar'     => _x( 'Stakeholder', 'add new on admin bar', 'astro-stakeholders' ),
			'add_new'            => _x( 'Add New', 'stakeholder', 'astro-stakeholders' ),
			'add_new_item'       => __( 'Add New Stakeholder', 'astro-stakeholders' ),
			'new_item'           => __( 'New Stakeholder', 'astro-stakeholders' ),
			'edit_item'          => __( 'Edit Stakeholder', 'astro-stakeholders' ),
			'view_item'          => __( 'View Stakeholder', 'astro-stakeholders' ),
			'all_items'          => __( 'All Stakeholders', 'astro-stakeholders' ),
			'search_items'       => __( 'Search Stakeholders', 'astro-stakeholders' ),
			'parent_item_colon'  => __( 'Parent Stakeholders:', 'astro-stakeholders' ),
			'not_found'          => __( 'No stakeholders found.', 'astro-stakeholders' ),
			'not_found_in_trash' => __( 'No stakeholders found in Trash.', 'astro-stakeholders' )
		);

		$args = array(
			'labels'             => $labels,
	        'description'        => __( 'People, companies, organizations.', 'astro-stakeholders' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'stakeholder' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 20,
			'menu_icon'			 => 'dashicons-groups',
			'show_in_rest' 		 => true,
			'supports'           => array( 'title', 'author', 'thumbnail', 'revisions' ),
			'taxonomies'		 => array( 'stakeholder_type', 'stakeholder_relation' )
		);

		register_post_type( 'stakeholder', $args );
	}

	/**
	 * "STAKEHOLDER-TYPE" and "STAKEHOLDER-RELATION" Taxonomies for the "STAKEHOLDER" Post Type.
	 */
	function stakeholder_taxonomies() {

		// STAKEHOLDER TYPE
		$labels = array(
			'name'              => _x( 'Types', 'taxonomy general name', 'astro-stakeholders' ),
			'singular_name'     => _x( 'Type', 'taxonomy singular name', 'astro-stakeholders' ),
			'search_items'      => __( 'Search Types', 'astro-stakeholders' ),
			'all_items'         => __( 'All Types', 'astro-stakeholders' ),
			'parent_item'       => __( 'Parent Type', 'astro-stakeholders' ),
			'parent_item_colon' => __( 'Parent Type:', 'astro-stakeholders' ),
			'edit_item'         => __( 'Edit Type', 'astro-stakeholders' ),
			'update_item'       => __( 'Update Type', 'astro-stakeholders' ),
			'add_new_item'      => __( 'Add New Type', 'astro-stakeholders' ),
			'new_item_name'     => __( 'New Type Name', 'astro-stakeholders' ),
			'menu_name'         => __( 'Type', 'astro-stakeholders' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'stakeholder-type' ),
			'show_in_rest'      => true,
	    	'rest_base'         => 'stakeholder-type',
		);
		register_taxonomy( 'stakeholder-type', array( 'stakeholder' ), $args );
		

		// STAKEHOLDER-RELATION
		$labels = array(
			'name'              => _x( 'Relations', 'taxonomy general name', 'astro-stakeholders' ),
			'singular_name'     => _x( 'Relation', 'taxonomy singular name', 'astro-stakeholders' ),
			'search_items'      => __( 'Search Relations', 'astro-stakeholders' ),
			'all_items'         => __( 'All Relations', 'astro-stakeholders' ),
			'parent_item'       => __( 'Parent Relation', 'astro-stakeholders' ),
			'parent_item_colon' => __( 'Parent Relation:', 'astro-stakeholders' ),
			'edit_item'         => __( 'Edit Relation', 'astro-stakeholders' ),
			'update_item'       => __( 'Update Relation', 'astro-stakeholders' ),
			'add_new_item'      => __( 'Add New Relation', 'astro-stakeholders' ),
			'new_item_name'     => __( 'New Relation Name', 'astro-stakeholders' ),
			'menu_name'         => __( 'Relation', 'astro-stakeholders' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'stakeholder-relation' ),
			'show_in_rest'      => true,
	    	'rest_base'         => 'stakeholder-relation',
		);
		register_taxonomy( 'stakeholder-relation', array( 'stakeholder' ), $args );

	}

	/**
	 * Get a stakeholder's taxonomies.
	 *
	 * @param string  $taxonomy The slug for the desired taxnonomy.
	 * @param boolean $children Wether to include children terms or not.
	 *
	 * @return string 			The list of terms with their respective links.
	 */
	public function stakeholder_terms( $taxonomy, $children = false ) {
	    $terms = get_the_terms( get_the_ID(), $taxonomy );	

	    $terms_string = '';

	    $term_items = array();

		if ( $terms && ! is_wp_error( $terms ) ) {		 
		    foreach ( $terms as $term ) {
		    	if ( $children == true || $term->parent == 0 ) {
			    	$term_link = get_term_link( $term );
			    	$term_items[] = '<a class="' . esc_attr( $taxonomy ) . '-link" href="' . esc_url( $term_link ) . '">' . esc_html( $term->name ) . '</a>';
		    	}
		    }

		    $terms_string = join( ' | ', $term_items );
	    }

	    return $terms_string;
	}

	/**
	 * Get a stakeholder's meta.
	 *
	 * @param string  $meta The slug for the desired meta field.
	 *
	 * @return string 		The list of terms with their respective links.
	 */
	public function stakeholder_meta( $meta ) {
	    $meta_string = '';
	    
	    $raw_meta = get_post_meta( get_the_ID(), $meta, true );
	    if ( $raw_meta ) { 
	         $meta_string = '<span class="$meta">' . $raw_meta . '</span>';
	    }

	    return $meta_string;
	}


	/**
	 * Modify content output for the "STAKEHOLDER" Post Type.
	 */
	function stakeholder_content( $content ) {
		$output = $content;

		if ( in_the_loop() && get_post_type( get_the_ID() ) == 'stakeholder' ) {
			$output .= '<p class="stakeholder-types">' . $this->stakeholder_terms( 'stakeholder-type' ) . '</p>';
			$output .= '<p class="stakeholder-relations">' . $this->stakeholder_terms( 'stakeholder-relation' ) . '</p>';
			$output .= '<h2>' . __( 'Contact Information', 'astro-stakeholders' ) . '</h2>';
			$output .= '<strong>' . __( 'Website:', 'astro-stakeholders' ) . '</strong> <a href="' . get_post_meta( get_the_ID(), 'contact_information_website', true ) . '" target="_blank">' . get_post_meta( get_the_ID(), 'contact_information_website', true ) . '</a>';
			$output .= '<h2>' . __( 'Social Networks', 'astro-stakeholders' ) . '</h2>';
			
			// FACEBOOK
			$facebook_urls = get_post_meta( get_the_ID(), 'social_networks_facebook', true );
			if ( $facebook_urls ) {
				$urls = explode( ',', $facebook_urls );
				foreach ( $urls as $url ) {
					$output .= '<br><strong>' . __( 'Facebook:', 'astro-stakeholders' ) . '</strong> <a href="' . $url . '" target="_blank">' . $url . '</a>';
				}
		    	$output .= '<br><strong>' . __( 'Facebook Followers:', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'social_networks_facebook-likes' );
			}
			
		    // INSTAGRAM
			$instagram_urls = get_post_meta( get_the_ID(), 'social_networks_instagram', true );
			if ( $instagram_urls ) {
				$urls = explode( ',', $instagram_urls );
				foreach ( $urls as $url ) {
					$output .= '<br><strong>' . __( 'Instagram:', 'astro-stakeholders' ) . '</strong> <a href="' . $url . '" target="_blank">' . $url . '</a>';
				}
				$output .= '<br><strong>' . __( 'Instagram Followers:', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'social_networks_instagram-followers' );
			}

			// TWITTER
			$twitter_urls = get_post_meta( get_the_ID(), 'social_networks_twitter', true );
			if ( $twitter_urls ) {
				$urls = explode( ',', $twitter_urls );
				foreach ( $urls as $url ) {
					$output .= '<br><strong>' . __( 'Twitter:', 'astro-stakeholders' ) . '</strong> <a href="' . $url . '" target="_blank">' . $url . '</a>';
				}
			    $output .= '<br><strong>' . __( 'Twitter Followers:', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'social_networks_twitter-followers' );
			}

		    // YOUTUBE
			$youtube_urls = get_post_meta( get_the_ID(), 'social_networks_youtube', true );
  			if ( $youtube_urls ) {
  				$urls = explode( ',', $youtube_urls );
				foreach ( $urls as $url ) {
					$output .= '<br><strong>' . __( 'YouTube:', 'astro-stakeholders' ) . '</strong> <a href="' . $url . '" target="_blank">' . $url . '</a>';
		 	 	}
		 	 	$output .= '<br><strong>' . __( 'YouTube Subscribers:', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'social_networks_youtube-subscribers' );
			}

			$output .= '<h2>' . __( 'Score', 'astro-stakeholders' ) . '</h2>';
			$output .= '<strong>' . __( 'Importance (Us):', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'evaluation_importance-us-score' );
			$output .= '<br><strong>' . __( 'Importance (Them):', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'evaluation_importance-them-score' );
			$output .= '<br><strong>' . __( 'Presence:', 'astro-stakeholders' ) . '</strong> ' . $this->stakeholder_meta( 'evaluation_presence-score' );


			$output .= '<h3>' . $this->stakeholder_meta( 'evaluation_total-score' ) . '</h3> ';
			
	    }
	    
	    return $output;
	}
}
 
// Initialize the plugin
$astro_stakeholders_plugin = new Astro_Stakeholders_Plugin();

/**
 * Facebook PHP SDK
 */
require_once dirname( __FILE__ ) . '/vendor/autoload.php'; // change path as needed

/**
 * Plugin Functions
 */
require_once dirname( __FILE__ ) . '/inc/plugin-functions.php';

/**
 * Stakeholder Meta Boxes
 */
require_once dirname( __FILE__ ) . '/inc/stakeholder-meta-boxes.php';