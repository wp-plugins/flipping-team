jQuery(document).ready(function() {
	jQuery('#upload_image_button').click(function() {
		//formfield = jQuery('#upload_image').attr('name');
		tb_show('','media-upload.php?type=image&TB_iframe=true');
		return false;
	});
	// send url back to plugin editor
	window.send_to_editor = function(html) {
		var imgurl = jQuery('img',html).attr('src');
		jQuery('#upload_image').val(imgurl);
		jQuery('#member_image').attr('src', imgurl);
		tb_remove();
	}
});
