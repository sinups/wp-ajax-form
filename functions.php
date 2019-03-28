<?php

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

?>
