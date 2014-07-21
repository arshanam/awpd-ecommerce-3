<?php
/*
Plugin Name: AWPD HA 3 Members Blog
Description: Plugin to Handle Memberships
Version: 1.0
Author: Heather Anderson
*/

// require_once( 'inc/redirects.php' );
require_once( 'inc/shortcodes.php' );
require_once( 'inc/ajax-requests.php' );
require_once( 'inc/template-markup.php' );
// Add new role for bloggers
class Awpd_Ha_3_Members_Blog {

  function __construct(){

    register_activation_hook( __FILE__, array( $this, 'activate' ) );

    add_action( 'init', array( $this, 'add_member_post_types' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
    //add_action( 'pre_get_posts', array( $this, 'filter_posts_by_user' ) );
    //add_filter( 'login_redirect', array( $this, 'nonadmin_login_redirect' ) );

  }

  /**
   * Registers and enqueues scripts and styles
   *
   * @since 1.0
   * @author Heather Anderson
   * @access public
   */
  public function enqueue(){

    //wp_enqueue_style( 'bcit_todo_styles', plugins_url( '/bcit-todo-list/assets/frontend-styles.css' ), '', '1.0', 'all' );

    wp_enqueue_script( 'awpd_ha_3_frontend_scripts', plugins_url( '/awpd-ha-3-members-blog/assets/frontend-scripts.js' ), array( 'jquery' ), '1.0', true );
    wp_localize_script( 'awpd_ha_3_frontend_scripts', 'AWPDHA3', array(
      'ajaxurl'              => admin_url( 'admin-ajax.php' ),
      'awpd_ha_3_ajax_nonce' => wp_create_nonce( 'awpd_ha_3_ajax_nonce' ),
    ));

  } // enqueue













  public function add_member_role(){

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

  /**
   * Adds our custom caps for the members' journal entries
   *
   * @since 1.0
   * @author Heather Anderson
   *
   * @uses get_role()             Returns the role specified
   * @uses add_cap()              Adds the cap to the role object
   */
  public function create_caps(){

    $member_blogger = get_role( 'member_blogger' );
    $member_blogger->add_cap( 'create_entry' );
    $member_blogger->add_cap( 'read_entry' );
    $member_blogger->add_cap( 'update_entry' );
    $member_blogger->add_cap( 'delete_entry' );

  } // create_caps

  /**
   * Removes our custom caps for the members' journal entries
   *
   * @since 1.0
   * @author Heather Anderson
   *
   * @uses get_role()             Returns the role specified
   * @uses remove_cap()           Removes the cap to the role object
   */
  public function remove_caps(){

    $member_blogger = get_role( 'member_blogger' );
    $member_blogger->remove_cap( 'create_entry' );
    $member_blogger->remove_cap( 'read_entry' );
    $member_blogger->remove_cap( 'update_entry' );
    $member_blogger->remove_cap( 'delete_entry' );

  } // remove_caps










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
        'name_admin_bar'     => 'Member Posts',
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
    );

    register_post_type( 'member-post', $args );

  }

  // Limit posts by author to user category
  public function filter_posts_by_user( $query ){

    $c_user = get_current_user_id();

    // if( !is_admin () ) {

    //   $query-> set( 'author', $c_user );
    //   $query-> set( 'post_type', 'member-post' );
    //   $query-> set( 'post_status', array( 'publish, private' ) );
    //   $query-> set( 'posts_per_page', -1 );
    //   return $query;

    // } else {
    //   return $query;
    // }
    //remove_action( 'pre_get_posts', array( $this, 'filter_posts_by_user' ) );
  }

  /**
  * Redirect non-admins to homepage after logging in.
  *
  * @since   1.0
  */
  public function nonadmin_login_redirect( $redirect_to, $request, $user ){
    echo '<br/><br/><h1 style="color: teal;">REDIRECT: ' . $redirect_to . '</h1>';
    echo '<h1 style="color: teal;>REQUEST: ' . $request . '</h1>';
    echo '<h1 style="color: teal;>USER: ' . $user . '</h1>';

    //is there a user to check?
    global $user;
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
      //check for admins
      if ( in_array( 'administrator', $user->roles ) && ! defined( 'DOING_AJAX' )) {
        // redirect them to the default place
        return $redirect_to;
      } else {
        return home_url();
      }
    } else {
      return $redirect_to;
    }

  }

  public function activate(){

    $this->create_caps();

    $this->add_member_role();
    flush_rewrite_rules();

  } //activate

  public function deactivate(){

    $this->remove_caps();

    flush_rewrite_rules();

  } // deactivate


}

new Awpd_Ha_3_Members_Blog();


















