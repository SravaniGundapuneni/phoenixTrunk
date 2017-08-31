if (!app) {
	throw new Error('app namespace undefined');
}

if (!app.mediaManager) {
	throw new Error('Media Manager namespace undefined');
}

$(function() {
	$('#uploadActionForm').on('submit', function(event) {
		var options = {
			url: $(this).attr('action'),
			success: onUploadActionFormSuccess,
		};

		$(this).ajaxSubmit(options);
		return false;
	});

	/*
	$('#cropForm').on('submit', function(event) {
		if (parseInt($('#w').val())) {
			return true;
		}

		event.preventDefault();
		alert('Please select a crop region then press submit.');
		return false;	
	});
	*/

	$('#mediaPreviewList').on('click', '.deleteItem', function() {
		app.mediaManager.deleteItem($(this).parent());
	});
	
	// Get all the thumbnail
	$('div.image').mouseenter(function(e) {
		$('#popup')
			.appendTo(this)
			.css({top:'30px', left:'-40px', display:'block'})
			.find('img')
				.attr({
					src: $(this).find('img').attr('src')
				});
	})
	.mouseleave(function() {
		$('#popup').css({display:'none'});
	});

	$('form[data-async]').submit(function(event) {
		var $form = $(this),
			serializedData = $form.serialize();

		$.ajax({
			type: $form.attr('method'),
			url: $form.attr('action'),
			data: $form.serialize(),
			 
			success: function(data, status) {
				$('#frameWindowAddFolder').modal('hide');
				location.reload(); 
			}
		});
	 
		event.preventDefault();
	}); 

	/*
	$('#cropbox').Jcrop({
		aspectRatio: 0,
		setSelect: [160, 160, 480, 270],
		onSelect: app.mediaManager.updateCoords
	});
	*/

	function onUploadActionFormSuccess(response, status) {
		$('#mediaPreviewList').append(response.html);
		$('#uploadActionForm').clearForm();
	}
});
