function lnw_console(message) {
	if(window.console && window.console.info)
		console.info(message);
}

function read_all_config() {
	var form_name			= $('#form_menu').find('.form_name').find('input').val();
	var form_title			= $('#form_menu').find('.form_title').find('input').val();
	var form_description	= $('#form_menu').find('.form_description').find('textarea').val();
	var form_captcha		= $('#form_menu').find('.form_captcha').find('input[type=checkbox]').attr('checked');
	var form_submit_button	= $('#form_menu').find('.form_submit_button').find('input[type=text]').val();
	var form_reset_button	= $('#form_menu').find('.form_reset_button').find('input[type=checkbox]').attr('checked') ? $('#form_menu').find('.form_reset_button').find('input[type=text]').val() : false;
	var form_theme			= $('#form_menu').find('.form_theme').find('select').val();

	var configs = new Array();
	//$('.field_container').find('> div').each(function() {
	$('.field_container').find('.field_element').each(function() {
		configs[configs.length] = read_element_config(this);
	});

	var form_config = {
		form_name:			form_name,
		form_title:			form_title,
		form_description:	form_description,
		form_captcha:		form_captcha,
		form_submit_button:	form_submit_button,
		form_reset_button:	form_reset_button,
		form_theme:			form_theme,
		field_configs:		configs
	};
	//lnw_console(form_config);

	return form_config;
}

function update_field_label(element) {
	var select_element = $($('.field_container').find('.select_element').get(0));
	select_element.find('.label').html($(element).val());
}

function select_form() {
	if($('#right_menu').is(':visible')) {
		$('#right_menu').hide();
		$('#form_menu').show();
	} else {
		$('#form_menu:hidden').show();
	}
	//$('.field_container').find('> div').each(function() {
	$('.field_container').find('.field_element').each(function() {
		if($(this).hasClass('select_element'))
			$(this).removeClass('select_element');
	});
	change_tab('setting');
}

function write_form(form_config, reset_form_flag) {
	//lnw_console(form_config);

	$('#form_menu').find('.form_name').find('input').val(form_config.form_name);
	$('#form_menu').find('.form_title').find('input').val(form_config.form_title);
	$('#form_menu').find('.form_description').find('textarea').val(form_config.form_description);
	$('#form_menu').find('.form_captcha').find('input[type=checkbox]').attr('checked', form_config.form_captcha);
	$('#form_menu').find('.form_submit_button').find('input[type=text]').val(form_config.form_submit_button);
	if(form_config.form_reset_button) {
		$('#form_menu').find('.form_reset_button').find('input[type=checkbox]').attr('checked', true);
		$('#form_menu').find('.form_reset_button').find('input[type=text]').val(form_config.form_reset_button);
	} else {
		$('#form_menu').find('.form_reset_button').find('input[type=checkbox]').attr('checked', false);
	}
	if(form_config.form_theme != undefined) {
		//theme_now = form_config.form_theme;
		$('#form_menu').find('.form_theme').find('select').val(form_config.form_theme);
	}

	if(reset_form_flag == undefined || reset_form_flag) {
		reset_form();
	}

	var i;
	for(i = 0; i < form_config.field_configs.length; i++) {
		var field_config = form_config.field_configs[i];
		add_element(field_config.field_type, field_config);
	}
}

function reset_form() {
	var form_config = read_all_config();

	$('#header_h1').find('.form_name').html(form_config.form_name);
	$('.lnwform-unit .header').find('.form_title').html(form_config.form_title);
	$('.lnwform-unit .header').find('.form_description').html(form_config.form_description);
	//$('#header').css('background-color', $('#form_menu').find('.form_formbg_color').find('input').css('background-color'));
	//$('#footer').css('background-color', $('#form_menu').find('.form_formbg_color').find('input').css('background-color'));
	//$('#header').css('color', $('#form_menu').find('.form_formtext_color').find('input').css('background-color'));
	//$('#footer').css('color', $('#form_menu').find('.form_formtext_color').find('input').css('background-color'));
	//$('#mainContent').css('color', $('#form_menu').find('.form_text_color').find('input').css('background-color'));
	$('.lnwform-unit .container').find('.button_container').find('.submit_button').val(form_config.form_submit_button);
	if($('#form_menu').find('.form_reset_button').find('input[type=checkbox]').attr('checked'))
		$('.lnwform-unit .container').find('.button_container').find('.reset_button').css({display: ''});
	else $('.lnwform-unit .container').find('.button_container').find('.reset_button').css({display: 'none'});
	$('.lnwform-unit .container').find('.button_container').find('.reset_button').val($('#form_menu').find('.form_reset_button').find('input[type=text]').val());

	if(theme_now != form_config.form_theme) {
		theme_now = form_config.form_theme;
		//$('body').get(0).innerHTML += '<link rel="stylesheet" type="text/css" href="' + base_url('system/application/views/templates/lnwform/css/jquery-ui/' + theme_now + '/jquery-ui-1.7.2.custom.css') + '" />';
		//$('head').get(0).innerHTML += '<link rel="stylesheet" type="text/css" href="' + base_url('system/application/views/templates/lnwform/css/jquery-ui/' + theme_now + '/jquery-ui-1.7.2.custom.css') + '" />';
		//lnw_console(theme_now);

		form_config.field_configs = new Array();
		write_form(form_config, false);
		init();
	}
	//$('#form_menu').find('.form_theme').find('select').val(theme_now);
}


function change_tab(action) {
	if(action == 'setting') {
		$('.left_plate_tab_left').removeClass('selected');
		$('.left_plate_tab_right').addClass('selected');
		$('#add_field_plate').hide();
		$('#setting_plate').fadeIn();
		$('#empty_state_plate').hide();
	} else if(action == 'add') {
		$('.left_plate_tab_left').addClass('selected');
		$('.left_plate_tab_right').removeClass('selected');
		$('#setting_plate').hide();
		$('#add_field_plate').hide();
		$('#add_field_plate').fadeIn();
		$('#empty_state_plate').hide();
	}
}

function check_state() {
	if($('.field_container').find('.select_element').length == 0){
		$('#add_field_plate').hide();
		$('#setting_plate').hide();
		$('#empty_state_plate').show();
	}
}
