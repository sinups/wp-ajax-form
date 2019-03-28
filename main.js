jQuery(document).ready(function($) {



    var form__sender = $('.form-send-mail');
    form__sender.find('input[type="text"]').attr("name", "client_name").attr("id", "client_name").addClass('req-field');
    form__sender.find('input[type="tel"]').attr("name", "client_phone").attr("id", "client_phone").addClass('req-field');
    form__sender.find('input[type="email"]').attr("name", "client_email").attr("id", "client_email").addClass('req-field rf-mail');


    var form = $('.form-send-mail'),
        action = form.attr('action'),
        pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

    form.find('.req-field').addClass('empty-field');

    $(document).on('submit', '.form-send-mail', function(e) {
        var form = $(this);
        var formDetails = $(this);

        $.ajax({
            type: 'POST',
            url: action,
            data: formDetails.serialize(),
            beforeSend: function() {
                form.addClass('is-sending');
            },
            error: function(request, txtstatus, errorThrown) {
                console.log(request);
                console.log(txtstatus);
                console.log(errorThrown);
            },
            success: function() {
                form.removeClass('is-sending').addClass('is-sending-complete');
                $("#myModal_spasibo").modal('show');
            }
        });

        e.preventDefault();

    });

    $(document).on('click', '.form-send-mail input[type="submit"]', function(e) {
        var form = $(this).closest("form");
        form.find('.req-field').each(function() {
            var el = $(this);
            if (el.hasClass('rf-mail')) {
                if (pattern.test(el.val())) {
                    el.removeClass('empty-field');

                } else {
                    el.addClass('empty-field');

                }
            } else if (el.val() != '') {
                el.removeClass('empty-field');

            } else {
                el.addClass('empty-field');

            }
        });

        var errorNum = form.find('.empty-field').length;

        if (errorNum > 0) {
            form.find('.empty-field').addClass('rf-error');
            setTimeout(function() {
                form.find('.rf-error').removeClass('rf-error');
            }, 1000);
            e.preventDefault();
        }

    });


    $(document).on('click', '#modal_close_href', function() {

        form.find('#client_name').val('');

        form.find('#client_phone').val('');
        form.find('#client_email').val('');

    });

});