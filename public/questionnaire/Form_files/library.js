function show_dialog(url, width, height, name) {
    if(name === undefined)
        name = '';
    var left = (window.outerWidth - width) / 2;
    var top = (window.outerHeight - height) / 2;
    if(window.showModalDialog) {
        window.showModalDialog(url, name, "dialogWidth: " + width + "px; dialogHeight: " + height + "px; dialogLeft: " + left + "px; dialogTop: " + top + "px;");
    } else {
        window.open(url, name, "width=" + width + ", height=" + height + ", left=" + left + ", top=" + top + ", toolbar=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, modal=yes");
    }
}
