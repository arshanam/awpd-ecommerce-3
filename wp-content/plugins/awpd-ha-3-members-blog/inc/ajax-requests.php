<?php

class Awpd_Ha_3_Ajax_Requests{

  function __construct(){

    add_action( 'wp_ajax_awpd_ha_3_add_entry', array( $this, 'process_entry' ) );
    add_action( 'wp_ajax_nopriv_awpd_ha_3_add_entry', array( $this, 'process_entry' ) );

    add_action( 'wp_ajax_awpd_ha_3_edit_entry', array( $this, 'get_entry_edit_form' ) );
    add_action( 'wp_ajax_nopriv_awpd_ha_3_edit_entry', array( $this, 'get_entry_edit_form' ) );

  } // __construct

  public function get_entry_edit_form(){
    $current_user = wp_get_current_user();
    if ( current_user_can( 'update_entry' ) || $current_user -> ID === 1 ){
      $args = array(
        'success' => true,
        'message' => awpd_ha_3_get_form_html( absint( $_POST['post_id'] ) ),
      );
    } else {
      $args = array(
        'success' => false,
        'message' => 'You are not allowed to edit entries',
      );
    }

    wp_send_json_success( $args );
  }

  /**
   * Process submitted entries
   *
   * @since 1.0
   * @author Heather Anderson
   *
   * @uses check_ajax_referer()                       Check our ajax nonce
   * @uses current_user_can()                         Returns true if current user has given cap
   * @uses $this->save_task()                         Saves our task and returns args
   * @uses wp_send_json_success()                     JSON encodes response and add 'success' as first param
   */
  public function process_entry(){

    check_ajax_referer( 'awpd_ha_3_ajax_nonce', 'security' );

    $save_me = false;

    // if ( isset( $_POST['title'] ) && ! empty( $_POST['title'] ) && current_user_can( 'create_entry' ) ){
    if ( isset( $_POST['title'] ) && ! empty( $_POST['title'] ) ){
      $save_me = true;
    }

    if ( true === $save_me ){

      $args = $this->save_entry( $_POST );

    } else {
      $args = array(
        'success' => false,
        'message' => 'Entry not saved',
      );
    }

    wp_send_json_success( $args );

  } // process_entry

  /**
   * Save my task and return arguements
   *
   * @since 1.0
   * @author Heather Anderson
   *
   * @param array         $posted_values      required        Array of values from $_POST
   * @uses esc_attr()                                         Keep things safe
   * @uses wp_kses_post()                                     Safety for 'post like' content
   * @uses get_current_user_id()                              Returns current user_id
   * @uses wp_insert_post()                                   Insert post to WP database
   * @uses is_wp_error()                                      Returns true if passed value is an WP error object
   * @return array        $args                               Success/fail args
   */
  private function save_entry( $posted_values ){
    ?><pre><?php print_r( $posted_values );?></pre><?php
    $post_content = isset( $posted_values['entry'] ) ? $posted_values['entry'] : '';

    $post_args = array(
      'post_title'   => esc_attr( $posted_values['title'] ),
      'post_type'    => 'member-post',
      'post_content' => wp_kses_post( $post_content ),
      'post_author'  => absint( get_current_user_id() ),
      'post_status'  => 'publish',
    );

    if ( isset( $posted_values['post_id'] ) && ! empty( $posted_values['post_id'] ) ){
      $post_args['ID'] = absint( $posted_values['post_id'] );
    }

    $post_id = wp_insert_post( $post_args );

    if ( ! is_wp_error( $post_id ) ){
      $args = array(
        'success' => true,
        'message' => 'Task saved',
      );

      if ( $post_id == $posted_values['post_id'] ){
        $args['updated']          = true;
        $args['entry_title']       = esc_attr( $posted_values['title'] );
        $args['entry_entry'] = esc_textarea( $posted_values['entry'] );
      } else {
        $entry         = get_post( $post_id );
        $args['html'] = awpd_ha_3_get_single_entry( $entry );
      }

    } else {
      $args = array(
        'success' => false,
        'message' => 'Had a post title but somethin went wrong saving the task',
      );
    }

    return $args;

  } // save_taks

} // Awpd_Ha_3_Ajax_Requests

new Awpd_Ha_3_Ajax_Requests();
