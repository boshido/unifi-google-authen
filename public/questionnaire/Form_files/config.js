var text_confirm_delete_element	= 'คุณแน่ใจว่าจะลบ Field นี้หรือไม่ ?';
var text_confirm_delete_choice	= 'คุณแน่ใจว่าจะลบตัวเลือกนี้หรือไม่ ?';

var text_error_delete_last_choice	= 'จำเป็นต้องมีอย่างน้อย 1 ตัวเลือก';

var theme_now = 'humanity';

function get_label(field_type) {
	switch(field_type) {
		case 'text':
			return 'Text';
			break;
		case 'paragraph_text':
			return 'Paragraph Text';
			break;
		case 'multiple_choice':
			return 'Multiple Choices';
			break;
		case 'checkboxes':
			return 'Checkboxs';
			break;
		case 'drop_down':
			return 'Drop Down';
			break;
		case 'likert':
			return 'Likert';
			break;
		case 'file_upload':
			return 'File Upload';
			break;
		case 'date':
			return 'Date';
			break;
		case 'time':
			return 'Time';
			break;
		case 'address':
			return 'Address';
			break;
		case 'number':
			return 'Number';
			break;
		default:
	}
	return '';
}
