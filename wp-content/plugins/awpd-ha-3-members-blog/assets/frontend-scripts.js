jQuery(document).ready(function($) {

  console.log('front end scripts!!!!');

  $( 'body' ).on( 'submit', '#awpd-ha-3-entry-form', function( e ){

    e.preventDefault();

    var form        = $( '#awpd-ha-3-entry-form' );
    var action      = $( form ).attr( 'action' );
    var title       = $( form ).find( '#awpd-ha-3-entry-title' ).val();
    var content     = $( 'textarea#awpd-ha-3-entry-item-content' ).val();
    var post_id     = $( form ).find( '#awpd-ha-3-entry-submit' ).data('post_id');
    var responsediv = $( form ).find( '#awpd_ha_3_ajax_response' );

    var data = {
      action: action,
      title: title,
      content: content,
      post_id: post_id,
      security: AWPDHA3.awpd_ha_3_ajax_nonce
    }
      console.log('posting data', data);

    $.post( AWPDHA3.ajaxurl, data, function( response ){

      if ( response.data.success === true ){
        $( responsediv ).append( '<p class="response-message success">'+response.data.message+'</p>' );
        $( responsediv ).find( '.response-message' ).delay( '4000' ).fadeOut( '4000' );
        clear_form( form );

        if ( response.data.updated === true ){
          var list_wrapper = $(form).parents('.awpd-ha-3-single-entry');
          var task_wrapper = $(list_wrapper).find( '.entry-wrapper' );

          $(task_wrapper).find('.entry-title').empty().append( response.data.entry_title );
          $(task_wrapper).find('.entry-description').empty().append( response.data.entry_description );

          $(task_wrapper).show();
          $(form).remove();
        } else {
          $('#awpd-ha-3-entry-list').prepend( response.data.html );
        }

      } // if success true

      if ( response.data.success === false ){
        $( responsediv ).append( '<p class="response-message error">'+response.data.message+'</p>' );
        $( responsediv ).find( '.response-message' ).delay( '4000' ).fadeOut( '4000' );
      } // if success false
    });

  });

  /**
  * Clears our form for us
  *
  * @since 1.0
  * @author Heather Anderson, though in all fairness most of
  * these functions are from Curtis [shortcode-slinger] McHale
  *
  * @param object  form      required        jquery form object
  */
  function clear_form( form ){
    $( form ).find( 'input[type="text"], textarea' ).val( '' );
  } // clear_form

  /**
  * Getting our edit form for todo tasks
  */
  $( '.awpd-ha-3-edit-button.edit' ).click( function(e){

    e.preventDefault();

    var button       = $(this);
    var post_id      = $(button).attr( 'href' );
    var list_wrapper = $(button).parents( '.awpd-ha-3-single-entry' );
    var form_holder  = $(list_wrapper).find( '.form-holder' );
    var entry_wrapper = $(list_wrapper).find( '.entry-wrapper' );

    var data = {
      action: 'awpd_ha_3_edit_entry',
      post_id: post_id,
      security: AWPDHA3.awpd_ha_3_ajax_nonce
    }
    console.log('going out?', data);

    $.post( AWPDHA3.ajaxurl, data, function( response ) {

      console.log('anything?', response);
      if ( response.data.success === true ) {
        $( entry_wrapper ).hide();
        $( list_wrapper ).find(form_holder).empty().append( response.data.message );
      }

      if ( response.data.success === false ) {
        $( list_wrapper ).append( '<p class="error response-message">' + response.data.message + '</p>' );
        $( responsediv ).find( '.response-message' ).delay('2000').fadeOut('4000').remove();
      }

    }); // post

  }); // click

  /**
    * Deleting that task
    */
    $( '.awpd-ha-3-edit-button.delete' ).click( function(e){

      e.preventDefault();

      var button       = $(this);
      var post_id      = $(button).attr( 'href' );

      var data = {
        action: 'awpd_ha_3_delete_entry',
        post_id: post_id,
        security: AWPDHA3.awpd_ha_3_ajax_nonce
      }
      console.log('going out?', data);

      $.post( AWPDHA3.ajaxurl, data, function( response ) {
        console.log('response', response);

        if ( response.data.success === true ) {
          $( entry_wrapper ).hide();
          $( list_wrapper ).find(form_holder).empty().append( 'Deleted' );
        }

      });
    });

});
