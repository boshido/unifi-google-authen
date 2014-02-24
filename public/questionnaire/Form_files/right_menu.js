function read_right_menu_config() {
	var right_menu = $($('#right_menu').get(0));

	var field_name			= right_menu.find('.field_name').val();
	var field_type			= right_menu.find('.field_type').find('input').val();
	var field_label			= right_menu.find('.field_label').find('textarea').val();
	var field_size			= undefined;
	var field_value			= undefined;
	var field_require		= right_menu.find('.field_require').find('input[type=checkbox]').get(0).checked;
	var field_user_guide	= right_menu.find('.field_user_guide').find('textarea').val();

	switch(field_type) {
		case 'text':
			field_size	= right_menu.find('.field_size').find('select').val();
			field_value	= right_menu.find('.field_value_input').find('input').val();
			break;
		case 'paragraph_text':
			field_size	= right_menu.find('.field_size').find('select').val();
			field_value	= right_menu.find('.field_value_textarea').find('textarea').val();
			break;
		case 'multiple_choice':
			field_value	= new Array();
			right_menu.find('.field_value_choices').find('div.choice').each(function() {
				var value		= $(this).find('input[type=text]').val();
				var selected	= $(this).find('input[type=checkbox]').get(0).checked;

				field_value[field_value.length]	= {value: value, selected: selected};
			});
			break;
		case 'checkboxes':
			field_value	= new Array();
			right_menu.find('.field_value_choices').find('div.choice').each(function() {
				var value		= $(this).find('input[type=text]').val();
				var selected	= $(this).find('input[type=checkbox]').get(0).checked;

				field_value[field_value.length]	= {value: value, selected: selected};
			});
			break;
		case 'drop_down':
			field_value	= new Array();
			right_menu.find('.field_value_choices').find('div.choice').each(function() {
				var value		= $(this).find('input[type=text]').val();
				var selected	= $(this).find('input[type=checkbox]').get(0).checked;

				field_value[field_value.length]	= {value: value, selected: selected};
			});
			break;
		case 'likert':
			var choices = new Array();
			right_menu.find('.field_value_choices').find('div.choice').each(function() {
				var value		= $(this).find('input[type=text]').val();
				var selected	= $(this).find('input[type=checkbox]').get(0).checked;

				choices[choices.length]	= {value: value, selected: selected};
			});

			var statements = new Array();
			right_menu.find('.field_value_statements').find('div.choice').each(function() {
				var value		= $(this).find('input[type=text]').val();
				var selected	= $(this).find('input[type=checkbox]').get(0).checked;
				var name		= $(this).find('input[name=field_name]').val();

				if(name == null || name == '')
					name = undefined;

				statements[statements.length]	= {value: value, selected: selected, field_name: name};
			});

			field_value	= {choices: choices, statements: statements};
			break;
		case 'file_upload':
			break;
		case 'date':
			break;
		case 'time':
			break;
		case 'address':
			break;
		case 'number':
			break;
		default:
	}

	if(field_name == null || field_name == '')
		field_name = undefined;

	return {
		field_name:			field_name,
		field_type:			field_type,
		field_label:		field_label,
		field_size:			field_size,
		field_value:		field_value,
		field_require:		field_require,
		field_user_guide:	field_user_guide
	};
}

function write_right_menu(config) {
	var right_menu = $($('#right_menu').get(0));
	right_menu.find('.field_name').val(config.field_name == undefined ? '' : config.field_name);
	right_menu.find('.field_type').find('input').val(config.field_type);
	right_menu.find('.field_type').find('.field_type_div').html(get_label(config.field_type));
	right_menu.find('.field_label').find('textarea').val(config.field_label);

	right_menu.find('.field_size').hide();
	if(config.field_size != undefined)
		right_menu.find('.field_size').find('select').val(config.field_size);

	right_menu.find('.field_value_input').hide();
	right_menu.find('.field_value_textarea').hide();
	right_menu.find('.field_value_choices').hide();
	right_menu.find('.field_value_statements').hide();

	var choices = right_menu.find('.choices');
	choices.html('');

	switch(config.field_type) {
		case 'text':
			right_menu.find('.field_size').show();
			right_menu.find('.field_value_input').show();
			right_menu.find('.field_value_input').find('input').val(config.field_value);
			break;
		case 'paragraph_text':
			right_menu.find('.field_size').show();
			right_menu.find('.field_value_textarea').show();
			right_menu.find('.field_value_textarea').find('textarea').val(config.field_value);
			break;
		case 'multiple_choice':
			right_menu.find('.field_value_choices').show();
			write_choices(choices, config.field_value, true);
			break;
		case 'checkboxes':
			right_menu.find('.field_value_choices').show();
			write_choices(choices, config.field_value, false);
			break;
		case 'drop_down':
			right_menu.find('.field_value_choices').show();
			write_choices(choices, config.field_value, true);
			break;
		case 'likert':
			right_menu.find('.field_value_choices').show();
			write_choices(choices, config.field_value.choices, true);

			var statements = right_menu.find('.statements');
			statements.html('');
			right_menu.find('.field_value_statements').show();
			write_choices(statements, config.field_value.statements, true);
			break;
		case 'file_upload':
			break;
		case 'date':
			break;
		case 'time':
			break;
		case 'address':
			break;
		case 'number':
			break;
		default:
	}

	right_menu.find('.field_require').find('input[type=checkbox]').get(0).checked = config.field_require;
	right_menu.find('.field_user_guide').find('textarea').val(config.field_user_guide);
}


function reset_right_menu() {
	var select_element = $('.field_container').find('.select_element').get(0);
	if(select_element==null) {
		$('#right_menu:visible').hide();
	}else {
		if($('#form_menu').is(':visible')){
			$('#form_menu').hide();
			$('#right_menu').show();
		}else{
			$('#right_menu:hidden').show();
		}
	}
	var config = read_element_config(select_element);
	write_right_menu(config);
}
