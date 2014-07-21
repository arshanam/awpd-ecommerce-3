<?php

/*
* Includes redirects for members-only blog.
*
*
*
*/

class Awpd_Ha_3_Redirects{

  function __construct(){
    add_action( 'plugins_loaded', array( $this, 'check_for_admin' ) );
  }

  /**
   * Checks to see if user is admin.
   *
   * @since 1.0
   *
   * @uses current_user_can() Checks whether the current user has the specified capability.
   * @return null Bail if the current user has the requisite capability.
   */

  public function check_for_admin(){

    if ( !is_admin() && ! defined( 'DOING_AJAX' ) ) {
      $this->disallow_intruders_and_members();
    } else {
      return; // Bail
    }
  }

  /**
   * Contains hooks for hiding all admin elements.
   *
   * dashboard_redirect - Handles redirecting disallowed users.
   * hide_menus         - Hides the admin menus.
   * hide_toolbar_items - Hides various Toolbar items on front and back-end.
   *
   * @since 1.0
   */
  public function disallow_intruders_and_members() {
    //add_action( 'admin_init',     array( $this, 'dashboard_redirect' ) );
    //add_action( 'admin_head',     array( $this, 'hide_menus' ) );
    //add_action( 'admin_bar_menu', array( $this, 'hide_toolbar_items' ), 999 );
  }

  /**
   * Hide menus other than profile.php.
   *
   * @since 1.1
   */
  public function hide_menus() {

    remove_menu_page( 'index.php' );                  //Dashboard
    remove_menu_page( 'edit.php' );                   //Posts
    remove_menu_page( 'upload.php' );                 //Media
    remove_menu_page( 'edit.php?post_type=page' );    //Pages
    remove_menu_page( 'edit-comments.php' );          //Comments
    remove_menu_page( 'themes.php' );                 //Appearance
    remove_menu_page( 'plugins.php' );                //Plugins
    remove_menu_page( 'users.php' );                  //Users
    remove_menu_page( 'tools.php' );                  //Tools
    remove_menu_page( 'options-general.php' );        //Settings
  }

  /**
   * Dashboard Redirect.
   *
   * @since 0.1
   *
   * @see wp_redirect() Used to redirect disallowed users to chosen URL.
   */
  function dashboard_redirect() {
    /** @global string $pagenow */
    global $pagenow;

    if ( 'profile.php' != $pagenow || ! $this->settings['enable_profile'] ) {
      wp_redirect( $this->settings['redirect_url'] );
      exit;
    }
  }

}

new Awpd_Ha_3_Redirects();
