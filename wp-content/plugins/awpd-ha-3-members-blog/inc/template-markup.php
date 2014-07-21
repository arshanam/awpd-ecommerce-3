<?php

/**
 * Returns our form HTML
 *
 * @since 1.0
 * @author Heather Anderson
 *
 * @param int        $post_id        optional    The id of a post if we want to check it
 * @uses bcit_todo_get_response_section()       Returns response section
 * @return string           $html               Our HTML form
 */
function awpd_ha_3_get_form_html( $post_id = null ){

  $post_object = isset( $post_id ) ? get_post( absint( $post_id ) ) : '';
  $title = ! empty( $post_object ) ? $post_object->post_title : '';
  $entry = ! empty( $post_object ) ? $post_object->post_content : '';

  $html = '<form action="awpd_ha_3_add_entry" id="awpd-ha-3-entry-form" >';
    $html .= '<p>';
      $html .= '<label for="awpd-ha-3-entry-title">Title</label></br>';
      $html .= '<input id="awpd-ha-3-entry-title" type="text" value="'. esc_attr( $title ) .'" placeholder="Entry Name" />';
    $html .= '</p>';

    $html .= '<p>';
      $html .= '<label for="awpd-ha-3-entry-item-content">Entry</label>';
      $html .= '<textarea id="awpd-ha-3-entry-item-content" placeholder="Your journal entry">'. esc_textarea( $entry ) .'</textarea>';
    $html .= '</p>';

    $html .= '<input type="submit" id="awpd-ha-3-entry-submit" data-post_id="'. absint( $post_id ) .'" value="Save Entry">';
    if ( ! empty( $post_object ) ){
      $html .= '<input type="submit" id="awpd-ha-3-entry-cancel" value="Cancel" />';
    }

    $html .= awpd_ha_3_get_response_section();

  $html .= '</form>';

  return $html;
}

/**
 * Returns a response section
 *
 * @since 1.0
 * @author Heather Anderson
 *
 * @uses plugins_url()                      returns URL to plugins directory
 * @return string                           Returns HTML for our response section
 */
function awpd_ha_3_get_response_section(){

  $html = '<section id="awpd_ha_3_ajax_response">';
    //$html .= '<img src="'. plugins_url( '/awpd-ha-3-members-blog/assets/images/spinner.gif' ) .'" class="bcit-todo-ajax-spinner" />';
  $html .= '</section>';

  return $html;

} // get_response_section

  /**
 * Gets single task
 *
 * @since 1.0
 * @author Heather Anderson
 *
 * @uses plugins_url()                      returns URL to plugins directory
 * @return string                           Returns HTML for our response section
 */
function awpd_ha_3_get_single_entry( $entry ){

  $html = '<li class="awpd-ha-3-single-entry">';
    $html .= '<span class="entry-wrapper">';
      $html .= '<span class="entry-title">'. esc_attr( get_the_title( $entry->ID ) ) .'</span>';
      $html .= '<span class="entry-description">'. wp_kses_post( $entry->post_content ) .'</span>';
      $html .= '<a href="'. absint( $entry->ID ) .'" class="awpd-ha-3-button edit">Edit</a>';
      // $html .= '<img src="'. plugins_url( '/awpd-ha-3-members-blog/assets/images/spinner.gif' ).'" class="bcit-todo-ajax-spinner" />';
    $html .= '</span>';
    $html .= '<span class="form-holder"></span>';
  $html .= '</li>';

  return $html;

  }
