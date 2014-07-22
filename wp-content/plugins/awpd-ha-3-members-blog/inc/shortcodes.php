<?php

class Awpd_Ha_3_Shortcodes{

  function __construct(){

    add_shortcode( 'awpd_ha_3_list_entries', array( $this, 'list_entries' ) );

    add_shortcode( 'awpd_ha_3_add_entry', array( $this, 'add_entry_form' ) );

  } // __construct

  public function list_entries( $entries ){

    $current_user = wp_get_current_user();

    $html = '<section id="awpd-ha-3-entry-wrapper">';

    if ( current_user_can( 'read_entry' ) || is_admin() || $current_user -> ID === 1 ){

      $entries = $this->get_entries();

      $html .= $this->get_entry_html( $entries );

    } else {
      $html .= 'You are not allowed to see this';
    }

    $html .= '</section>';

    return $html;

  } // list_tasks

  /**
   * This gets the template for a single entry.
   *
   * @since 1.0
   * @author Heather Anderson
   * @access private
   *
   * @uses current_user_can()                 Returns true if current user has given cap
   * @uses $this->get_entry_html()             Returns our form HTML
   * @return string       $html               Our HTML form
   */
  private function get_entry_html( $entries ){

    $html = '';

    if ( is_wp_error( $entries ) || empty( $entries ) ){
      $html .= 'No entries';
    } else {

      $html .= '<ul id="awpd-ha-3-entry-list">';
        foreach( $entries as $e ){
          $html .= awpd_ha_3_get_single_entry( $e );
        }
      $html .= '</ul>';

    }

    return $html;

  }

  /**
   * This gets all the current entries
   *
   * @since 1.0
   * @author Heather Anderson
   * @access private
   *
   * @uses current_user_can()                 Returns true if current user has given cap
   * @uses $this->get_form_html()             Returns our form HTML
   * @return string       $html               Our HTML form
   */
  private function get_entries(){
    $c_user = get_current_user_id();

    $query_args = array(
      'author' => $c_user,
      'post_type' => 'member-post',
      'post_status' => array( 'publish, private' ),
      'posts_per_page' => -1 ,
    );

    $entries = get_posts( $query_args );

    return $entries;

  } // get_entries

  /**
   * This gives us the form for adding entries.
   *
   * @since 1.0
   * @author Heather Anderson
   * @access public
   *
   * @uses current_user_can()                 Returns true if current user has given cap
   * @uses $this->get_form_html()             Returns our form HTML
   * @return string       $html               Our HTML form
   */
  public function add_entry_form(){

    $html = '';

    $current_user = wp_get_current_user();
    if ( current_user_can( 'create_entry' ) || current_user_can( 'update_entry' ) || $current_user -> ID === 1 ){

      $html .= awpd_ha_3_get_form_html();

    } else {

      $html .= 'Sorry you are not allowed to add journal entries';
    }

    return $html;

  } // add_entry_form

} // Awpd_Ha_3_Shortcodes

new Awpd_Ha_3_Shortcodes();
