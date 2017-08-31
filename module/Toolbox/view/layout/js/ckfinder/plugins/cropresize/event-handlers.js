jQuery(function($){

	var jcropApi,
		pos;

	$('#target').Jcrop({
		bgColor: 'transparent',
		boxWidth: 550,
		boxHeight: 0,
		maxSize: 0,
		onChange: showCoords,
		onSelect: showCoords,
		onRelease: clearDimensions
	}, function() {
		jcropApi = this;
	});

	function clearDimensions() {
		$("#crop_width").val('');
		$("#crop_height").val('');
		jcropApi.setOptions({ allowSelect: true });
	}

	function showCoords(c) {
		var width,
			height;

		pos = c,

		width = Math.round(pos.w);
		width = (isNaN(width)) ? 0 : width;

		height = Math.round(pos.h);
		height = (isNaN(height)) ? 0 : height;

		$("#crop_width").val(width);
		$("#crop_height").val(height);

		$("#x").val(pos.x);
		$("#y").val(pos.y);
		$("#w").val(width);
		$("#h").val(height);
	};

	$("#crop_width, #crop_height").bind("keyup", function() {
		var width,
			x2,
			height,
			y2;

		width = $("#crop_width").val();
		width = (!isNaN(width)) ? width : 0;
		x2 = (pos.x + parseInt(width, 10));

		height = $("#crop_height").val();
		height = (!isNaN(height)) ? height : 0;
		y2 = (pos.y + parseInt(height, 10));

		jcropApi.setSelect([ pos.x, pos.y, Math.round(x2), Math.round(y2) ]);

		//console.log("Width: " + pos.x + " + " + width + " = " + Math.round(x2));
		//console.log("Height: " + pos.y + " + " + height + " = " + Math.round(y2));
		//console.log("----------------------------------------------------------");
	});

	$("#right_col").height( $("#left_col").height() );

	$("#crop_submit").bind("submit", function(event) {

		$("#image_quality").val( $("#crop_image_quality").val() );

		event.preventDefault();

		$.ajax({
			url: './lib/Process.php',
			global: false,
			type: "POST",
			dataType: "text",
			data: $(this).serialize(),
			beforeSend: function() {},
			success: function(response) {
				$("#crop_completed_value").html(response);
			}
		});
	});
});