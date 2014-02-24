function read_element_config(element) {
	element = $(element);

	return $.parseJSON(element.find('.input_container').attr('config'));

	var field_name			= undefined;
	var field_type			= element.attr('field_type');
	var field_label			= element.find('.label').html();
	var field_size			= undefined;
	var field_value			= undefined;
	var field_require		= (element.find('input.field_require').val() == '1');
	var field_user_guide	= element.find('input.field_user_guide').val();

	switch(field_type) {
		case 'text':
			field_name	= element.find('input[type=text]').attr('name');
			field_size	= element.find('input[type=text]').get(0).className;
			field_value	= element.find('input[type=text]').val();
			break;
		case 'paragraph_text':
			field_name	= element.find('textarea').attr('name');
			field_size	= element.find('textarea').get(0).className;
			field_value	= element.find('textarea').val();
			break;
		case 'multiple_choice':
			field_name	= element.find('.input_container').find('input:first').attr('name');
			field_value	= new Array();
			element.find('.input_container').find('input').each(function() {
				//var value		= $.trim($(this).parent().html().replace(/<([^>]*)>/, ''));
				var value		= $(this).parent().find('span').html();
				var selected	= this.checked;

				field_value[field_value.length]	= {value: value, selected: selected};
			});
			break;
		case 'checkboxes':
			field_name	= element.find('.input_container').find('input:first').attr('name');
			field_value	= new Array();
			element.find('.input_container').find('input').each(function() {
				//var value		= $.trim($(this).parent().html().replace(/<([^>]*)>/, ''));
				var value		= $(this).parent().find('span').html();
				var selected	= this.checked;

				field_value[field_value.length]	= {value: value, selected: selected};
			});
			break;
		case 'drop_down':
			field_name	= element.find('.input_container select').attr('name');
			field_value	= new Array();
			element.find('.input_container').find('option').each(function() {
				var value		= $(this).html();
				var selected	= this.selected;

				field_value[field_value.length]	= {value: value, selected: selected};
			});
			break;
		case 'likert':
			var choices = new Array();
			element.find('.input_container').find('td[choice]').each(function() {
				var value		= $(this).html();
				//var selected	= this.selected;
				var selected	= false;

				choices[choices.length]	= {value: value, selected: selected};
			});

			var statements = new Array();
			element.find('.input_container').find('td[statement]').each(function() {
				var value		= $(this).html();
				//var selected	= this.selected;
				var selected	= false;
				var name		= $(this).parents('tr:first').find('input:first').attr('name');

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

function select_element(element) {
	//$('.field_container').find('> div').each(function() {
	$('.field_container').find('.field_element').each(function() {
		if($(this).hasClass('select_element'))
			$(this).removeClass('select_element');
	});
	$(element).addClass('select_element');
	$('.field_element_header').removeClass('field_element_header_selected');
	//reset_right_menu();
	change_tab ('setting');
}



function add_element(field_type, config) {
	var field_type	= field_type;
	var field_label	= get_label(field_type);
	var field_size	= undefined;
	var field_value	= undefined;

	switch(field_type) {
		case 'text':
			field_size	= 'medium';
			field_value	= '';
			break;
		case 'paragraph_text':
			field_size	= 'medium';
			field_value	= '';
			break;
		case 'multiple_choice':
			field_value	= [{value: 'ตัวเลือก A', selected: true}, {value: 'ตัวเลือก B', selected: false}, {value: 'ตัวเลือก C', selected: false}];
			break;
		case 'checkboxes':
			field_value	= [{value: 'ตัวเลือก A', selected: false}, {value: 'ตัวเลือก B', selected: false}, {value: 'ตัวเลือก C', selected: false}];
			break;
		case 'drop_down':
			field_value	= [{value: 'ตัวเลือก A', selected: true}, {value: 'ตัวเลือก B', selected: false}, {value: 'ตัวเลือก C', selected: false}];
			break;
		case 'likert':
			field_value	= {choices: [{value: 'เห็นด้วย<br>อย่างยิ่ง', selected: false}, {value: 'เห็นด้วย', selected: false}, {value: 'ไม่เห็นด้วย', selected: false}, {value: 'ไม่เห็นด้วย<br>อย่างยิ่ง', selected: false}], statements: [{value: 'คำถามที่ 1'}, {value: 'คำถามที่ 2'}, {value: 'คำถามที่ 3'}]};
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

	if(config == undefined) {
		config = {
			field_type:			field_type,
			field_label:		field_label,
			field_size:			field_size,
			field_value:		field_value,
			field_require:		false,
			field_user_guide:	''
		};
	}

	var content = '';
	//content += '<div field_type="' + field_type + '" onmousedown="select_element(this);" onclick="select_element(this);" class="ui-corner-all">';
	//content += '<div field_type="' + field_type + '" onmousedown="select_element(this);" onclick="select_element(this); reset_right_menu();" class="ui-corner-all">';
	content += '<div field_type="' + field_type + '" onmousedown="select_element(this);" onclick="select_element(this); reset_right_menu();" class="ui-corner-all field_element">';
		content += edit_element(config);
	content += '</div>';

	$('.field_container').append(content);

	//var divs = $('.field_container').find('> div');
	var divs = $('.field_container').find('.field_element');
	return divs[divs.length - 1];
}

function edit_element(config, element) {
	var field_name = (config.field_name == undefined) ? "" : config.field_name;

	var default_label = config.field_label;
	var default_user_guide = config.field_user_guide;
	var input_container = '';

	switch(config.field_type) {
		case 'text':
			input_container	+= '<input type="text" name="' + field_name + '" class="' + config.field_size + '" value="' + config.field_value + '" onkeyup="reset_right_menu();" />';
			break;
		case 'paragraph_text':
			input_container	+= '<textarea name="' + field_name + '" class="' + config.field_size + '" onkeyup="reset_right_menu();">' + config.field_value + '</textarea>';
			break;
		case 'multiple_choice':
			$.each(config.field_value, function() {
				input_container	+= '<div  style ="display : inline ; padding: 10px 10px 10px;" ><label><input type="radio" name="' + field_name + '" ' + (this.selected ? 'checked' : '') + ' onclick="uncheck_other_radio(this);" /> <span>' + this.value + '</span></label></div>';
			});
			break;
		case 'checkboxes':
			$.each(config.field_value, function() {
				input_container	+= '<div style ="display : inline ; padding: 10px 10px 10px;" ><label><input type="checkbox" name="' + field_name + '" ' + (this.selected ? 'checked' : '') + ' /> <span>' + this.value + '</span></label></div>';
			});
			break;
		case 'drop_down':
			input_container	+= '<select name="' + field_name + '">';
			$.each(config.field_value, function() {
				input_container	+= '<option ' + (this.selected ? 'selected' : '') + '>' + this.value + '</option>';
			});
			input_container	+= '</select>';
			break;
		case 'likert':
			input_container	+= '<table border="0" cellpadding="0" cellspacing="0" class="likert">';
				input_container	+= '<thead>';
				input_container	+= '<tr class="likert-header">';
					input_container	+= '<td statement="statement"></td>';
					$.each(config.field_value.choices, function() {
						input_container	+= '<td align="center" valign="middle" choice="choice">' + this.value + '</td>';
					});
				input_container	+= '</tr>';
				input_container	+= '</thead>';
				input_container	+= '<tbody>';
				var count=0; var num_type = 'odd';
				$.each(config.field_value.statements, function() {
					count++;
					if(count %2 == 0) { num_type = 'even';} else { num_type = 'odd';}
					field_name = (this.field_name == undefined) ? "" : this.field_name;

					input_container	+= '<tr class="'+ num_type +'">';
						input_container	+= '<td align="left" valign="middle" statement="statement">' + this.value + '</td>';
						$.each(config.field_value.choices, function(index) {
							input_container	+= '<td align="center" valign="middle">';
								input_container	+= '<input type="radio" name="' + field_name + '" ' + (this.selected ? 'checked' : '') + ' onclick="uncheck_other_radio(this, \'tr\');" />';
								//input_container	+= '<br/>' + (index + 1);
							input_container	+= '</td>';
						});
					input_container	+= '</tr>';
				});
			input_container	+= '</tbody>';
			input_container	+= '</table>';
			break;
		case 'file_upload':
			input_container	+= '<input type="file" name="' + field_name + '" size="32" />';
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

	var content = '';
	content += '<div class="container_add_delete ui-corner-bl"><div onmousedown="clone_element(this);" class="icon-duplicate"></div>';
	content += '<div onmousedown="if(confirm(text_confirm_delete_element)){delete_element(this);}" class="icon-delete"></div></div>';
	if(default_user_guide.length != 0) {
		content += '<div class="field_description"><div class="description_container">' + nl2br(default_user_guide) + '</div></div>';
	}
	content += '<div class="field_preview">';
		content += '<input type="hidden" class="field_user_guide" value="' + config.field_user_guide + '" />';
		content += '<input type="hidden" class="field_require" value="' + (config.field_require ? '1' : '') + '" />';
		content += '<div class="label">';
			content += default_label;
			content += (config.field_require ? ' <font color="red">*</font>' : '');
		content += '</div>';
		content += '<div class="input_container">';
			content += input_container;
		content += '</div>';
	content += '</div>';

	var tmp = $('<div></div>');
	tmp.html(content);
	tmp.find('.input_container').attr('config', $.toJSON(config));

	content = tmp.html();

	if(element == undefined)
		return content;

	$(element).html(content);
}

function remove_element_field_name(config) {
	config.field_name = undefined;
	switch(config.field_type) {
		case 'text':
			break;
		case 'paragraph_text':
			break;
		case 'multiple_choice':
			break;
		case 'checkboxes':
			break;
		case 'drop_down':
			break;
		case 'likert':
			$.each(config.field_value.statements, function() {
				this.field_name = undefined;
			});
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

	return config;
}

function clone_element(element) {
	var select_element = $(element).parents('[field_type]').get(0);
	if(element == undefined)
		select_element = $('.field_container').find('.select_element').get(0);
	var config = read_element_config(select_element);
	config = remove_element_field_name(config);
	var new_element = $(select_element).clone(true);
	new_element.removeClass('select_element');
	new_element.insertAfter(select_element);
	edit_element(config, new_element);
}

function delete_element(element) {
	var select_element = $(element).parents('[field_type]').get(0);
	if(element == undefined)
		select_element = $('.field_container').find('.select_element').get(0);
	$(select_element).remove();
	reset_right_menu();
	check_state();
}

function reset_element() {
	var select_element = $($('.field_container').find('.select_element').get(0));
	var config = read_right_menu_config();
	//lnw_console(config);
	edit_element(config, select_element);
}
