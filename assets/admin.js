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

        $forms.each(function(i, form){

            $form = $(form);

            form_id = $form.data('form-id');

            settings[form_id] = {
                type: $form.find('.cf-popup-form-type').val(),
                delay: $form.find('.cf-popup-form-delay').val(),
                before: $form.find('.cf-popup-form-before').val(),
                after: $form.find('.cf-popup-form-after').val()
            };

        });

        var x = 1;

    })

});




