function add_choice(choice) {
	var select_choice = $(choice).parents('.choice').get(0);
	var new_choice = $(select_choice).clone(true);
	new_choice.find('input[type=hidden]').val('');
	new_choice.find('input[type=text]').val('');
	new_choice.find('input[type=checkbox]').get(0).checked = false;
	new_choice.insertAfter(select_choice);

	reset_element();
}

function delete_choice(choice) {
	if($($(choice).parents('.choice').get(0)).parent().find('.choice').size() == 1) {
		alert(text_error_delete_last_choice);
		return;
	}
	if(!confirm(text_confirm_delete_choice))return;

	$($(choice).parents('.choice').get(0)).remove();

	reset_element();
}

function uncheck_other_choice(choice) {
	var checked = choice.checked;
	$($(choice).parents('.choice').get(0)).parent().find('.choice').each(function() {
		$(this).find('input[type=checkbox]').get(0).checked = false;
	});
	choice.checked = checked;
}

function write_choices(choices, options, limit) {
	if(!$.isArray(options))
		options = [options];

	$.each(options, function() {
		var content = '';
		content += '<div class="choice">';
		content += '<table cellpadding="0" cellspacing="0"><tr>'
		content += '<td><span style="cursor: n-resize;" class="ui-icon ui-icon-arrow-2-n-s">&nbsp;gg</span></td>';
		content += '<td><input type="hidden" name="field_name" value="' + (this.field_name == undefined ? '' : this.field_name) + '" /><input type="text" value="' + this.value + '" onkeyup="reset_element();" /></td>';
		content += '<td><div style="cursor: pointer;" onmousedown="add_choice(this);" class="icon-duplicate"></div></td>';
		content += '<td><div style="cursor: pointer;" onmousedown="delete_choice(this);" class="icon-delete"></div></td>';
		content += '<td><input style="" type="checkbox" ' + ((this.selected) ? 'checked' : '') + ' onclick="' + ((limit) ? 'uncheck_other_choice(this);' : '') + 'reset_element();" /></td>';
		content += '</tr></table>';
		content += '</div>';
		choices.append(content);
	});
}
