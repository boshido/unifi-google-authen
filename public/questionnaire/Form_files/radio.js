function uncheck_other_radio(radio, parent) {
	if(parent == undefined)
		parent = '[field_type]';
	var checked = radio.checked;
	$(radio).parents(parent + ':first').find('input[type=radio]').each(function() {
		this.checked = false;
	});
	radio.checked = checked;
}
