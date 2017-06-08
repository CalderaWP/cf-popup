/**
 * Created by christie on 5/27/17.
 */
jQuery( document ).ready(function( $ ) {

    $('#cf-popup-settings-form').on('submit', function(event) {

        event.preventDefault();

        var settings = {};
        var $forms = $('.cf-popup-form');
        var $form;
        var form_id;
        var exit_intent;
        var enabled;

        $forms.each(function(i, form){

            $form = $(form);

            form_id = $form.data('form-id');

            if ( 'exit-intent' === $form.find('.cf-popup-form-type').val() ) {
                exit_intent = 1;
            } else {
                exit_intent = 0;
            }

            if ( $form.find('.cf-popup-form-enabled').prop('checked') ) {
                enabled = 1;
            } else {
                enabled = 0;
            }

            settings[form_id] = {
                enabled: enabled,
                exit_intent: exit_intent,
                delay: $form.find('.cf-popup-form-delay').val(),
                before: $form.find('.cf-popup-form-before').val(),
                after: $form.find('.cf-popup-form-after').val()
            };

        });

        $.ajax({
            method: 'POST',
            url: CF_POPUP.api,
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', CF_POPUP.nonce );
            },
            data: {
                forms: settings
            },
            complete: function (r) {
                console.log(r);
            },
            error: function (r) {
                console.log(r);
            }

        })

    })

});




