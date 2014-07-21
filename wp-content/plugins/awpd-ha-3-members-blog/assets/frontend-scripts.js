jQuery(document).ready(function($) {

  console.log('front end scripts!!!!');

  $( 'body' ).on( 'submit', '#awpd-ha-3-entry-form', function( e ){
    console.log('on submit...');

    e.preventDefault();

    var form        = $( '#awpd-ha-3-entry-form' );
    var action      = $( form ).attr( 'action' );
    var title       = $( form ).find( '#awpd-ha-3-entry-title' ).val();
    var content     = $( form ).find( '#awpd-ha-3-entry-item-content' ).val();
    var post_id     = $( form ).find( '#awpd-ha-3-entry-submit' ).data('post_id');
    var responsediv = $( form ).find( '#awpd_ha_3_ajax_response' );
    //var spinner     = $( form ).find( '.bcit-todo-ajax-spinner' );

    var data = {
      action: action,
      title: title,
      content: content,
      post_id: post_id,
      security: AWPDHA3.awpd_ha_3_ajax_nonce
    }
    console.log(data);
    $.post( AWPDHA3.ajaxurl, data, function( response ){

      // $( spinner ).hide();
      console.log(response);

      if ( response.data.success === true ){
        $( responsediv ).append( '<p class="response-message success">'+response.data.message+'</p>' );
        $( responsediv ).find( '.response-message' ).delay( '4000' ).fadeOut( '4000' );
        clear_form( form );

        if ( response.data.updated === true ){
          var list_wrapper = $(form).parents('.awpd-ha-3-single-entry');
          var task_wrapper = $(list_wrapper).find( '.entry-wrapper' );

          $(task_wrapper).find('.entry-title').empty().append( response.data.task_title );
          $(task_wrapper).find('.entry-description').empty().append( response.data.task_description );

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


  });

});
