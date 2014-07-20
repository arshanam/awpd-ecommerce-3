<?php
/*
Plugin Name: AWPD HA 3 Members Blog
Description: Plugin to Handle Memberships
Version: 1.0
Author: Heather Anderson
*/

// require pmp

// Add new role for bloggers
class Awpd_Ha_3_Members_Blog {

  function __construct(){

    register_activation_hook( __FILE__, array( $this, 'add_role_on_plugin_activation' ) );
    //register_activation_hook( __FILE__, array( $this, 'add_blog_post_types' ) );

    // add_action( 'init', array( $this, 'create_member_taxonomy' ) );
    add_action( 'init', array( $this, 'add_member_post_types' ) );
    add_action( 'pre_get_posts', array( $this, 'filter_posts_by_user' ) );

  }

















  public function add_role_on_plugin_activation(){

    add_role(
      'member_blogger',
      __( 'Member Blogger' ),
      array(
          'read'         => true,  // true allows this capability
          'edit_posts'   => true,
          'edit_published_posts'   => true,
          'publish_posts'   => true,
          'delete_posts' => false, // Use false to explicitly deny
          'delete_published_posts'   => true,
      )
    );

  }














  public function create_member_taxonomy() {


    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
      'name'              => _x( 'Member Post', 'taxonomy general name' ),
      'singular_name'     => _x( 'Member Post', 'taxonomy singular name' ),
      'search_items'      => __( 'Search Member Posts' ),
      'all_items'         => __( 'All Member Posts' ),
      'parent_item'       => __( 'Parent Member Post' ),
      'parent_item_colon' => __( 'Parent Member Post:' ),
      'edit_item'         => __( 'Edit Member Post' ),
      'update_item'       => __( 'Update Member Post' ),
      'add_new_item'      => __( 'Add New Member Post' ),
      'new_item_name'     => __( 'New Member Post Name' ),
      'menu_name'         => __( 'Member Post' ),
    );

    $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'member-posts' ),
    );

    register_taxonomy( 'member_posts', array( 'member-post' ), $args );

  }








// Add user category on user creation
// http://codex.wordpress.org/Plugin_API/Action_Reference/user_register

  /**
   * Register post type for journal entries.
   *
   * This is necessary to allow the admin user to also blog.
   *
   * @link http://codex.wordpress.org/Function_Reference/register_post_type
   */

  public function add_member_post_types(){

    $labels = array(
        'name'               => 'Member Post',
        'singular_name'      => 'Member Post',
        'menu_name'          => 'Member Posts',
        'name_admin_bar'     => 'Mer Posts',
        'add_new'            => 'New Member Post',
        'add_new_item'       => 'New Member Post',
        'new_item'           => 'New Member Post',
        'edit_item'          => 'Edit Member Post',
        'view_item'          => 'View Member Post',
        'all_items'          => 'All Member Posts',
        'search_items'       => 'Search Member Posts',
        'parent_item_colon'  => 'Parent Post',
        'not_found'          => 'No Member Posts Found',
        'not_found_in_trash' => 'Sorry dawg, no member posts in dis here tee-rash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-portfolio',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'member-post' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => 5,
        //'supports'           => array( 'title', 'editor' ),
        //'taxonomies'         => array( 'category' ),
    );

    register_post_type( 'member-post', $args );
    // register_taxonomy_for_object_type('member-post', 'category');
  }

// Limit posts by author to user category
  public function filter_posts_by_user( $query ){

    // global $wp_query;
    // $c_user = get_current_user();
    // For now
    $current_user = 2;

    $query -> set( 'post_type', 'member-post' );
    return $query;


  }


}

new Awpd_Ha_3_Members_Blog();


















