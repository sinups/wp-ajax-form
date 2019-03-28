![version](https://img.shields.io/badge/version-1.0-red.svg?style=flat-square "Version Universal  Ajax Wp Form")
[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/sinups/)


## Отправляем форму Ajax’ом и сохраняем письма в админке WordPress :fire:
:earth_asia: Cайт : http://sinups.net

:boy:   Автор : [ A K ](https://www.instagram.com/webtheory/ "Instagram page")

## Шаблон формы обратной связи .
Файл : `form.php`.

`
<form action="<?php echo admin_url('admin-ajax.php?action=send_mail'); ?>" method="post" class="form-send-mail">
    <div class="form-group">
        <input type="text" placeholder="Имя*">
    </div>
    <div class="form-group">
        <input type="tel" placeholder="+7 (___) ___-__-__">
    </div>
    <div class="form-group">
        <input type="email" placeholder="E-mail*">
    </div>
    <div class="form-group">
        <input type="submit" class="btn-main" value="Получить каталог">
    </div>
</form>
`

## Создадим произвольный тип записи
Файл : `fucntion.php`.

`

add_action('init', 'cpt_mail_calback');

function cpt_mail_calback()
    {
    $labels = array(
        "name" => "заявки",
        "singular_name" => "Mail",
        "menu_name" => "заявки",
        "all_items" => "All mail",
        "add_new" => "Add New",
        "add_new_item" => "Add New",
        "edit" => "Edit",
        "edit_item" => "Edit",
        "new_item" => "New item",
        "view" => "View",
        "view_item" => "View item",
        "search_items" => "Search item",
        "not_found" => "No found",
        "not_found_in_trash" => "No found",
    );
    $args = array(
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "show_ui" => true,
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => true,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => true,
        "rewrite" => false,
        "query_var" => true,
        "menu_position" => 7,
        "menu_icon" => "dashicons-email-alt",
        "supports" => array(
            "title",
            "editor"
        ) ,
    );
    register_post_type("mail", $args);
    }
`


## Ajax обработчик формы
Файл : `fucntion.php`.

`
function send_mail()
    {
    /* Забираем отправленные данные */
    $client_name = $_POST['client_name'];
    $client_email = $_POST['client_email'];
    $client_phone = $_POST['client_phone'];
    /* Отправляем нам письмо */
    $emailTo = 'your-email@mail.ru , your-email-2@mail.ru';
    $subject = 'Заявка на обратный звонок';
    $headers = "Content-type: text/html; charset=\"utf-8\"";
    $mailBody = " <b>Имя:</b> $client_name <br/> <b>Телефон:</b> $client_email <br/> <b>E-mail:</b> $client_phone";
    wp_mail($emailTo, $subject, $mailBody, $headers);
    /* Создаем новый пост-письмо */
    $post_data = array(
        'post_title' => $client_name,
        'post_excerpt' => $client_phone,
        'post_content' => $client_phone . '<br/>' . $client_email,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'mail',
    );
    wp_insert_post($post_data);
    /* Завершаем выполнение ajax */
    die();
    }

add_action("wp_ajax_send_mail", "send_mail");
add_action("wp_ajax_nopriv_send_mail", "send_mail");
`


## Скрипт
Файл : `main.js`.
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

});`
`