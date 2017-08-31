$(function() {

	if (CKFinder === undefined) {
		throw new Error('CKFinder is undefined');
	}

	var finder = new CKFinder();
	finder.basePath = '/d/';

	$('#add-media').on('click', function(event) {
		event.preventDefault();
		browseFiles();
	});

	$('#media-list').on('click', '.remove-attachment', function() {
		var $mediaItem = $(this).parent();

		// TODO: selecting upstream is bad, remove by data attributes or something instead
		$mediaItem.fadeOut('normal', function() {
			$mediaItem.remove();
		});
	});

	function browseFiles() {
		try {	
			console.log('try');
			finder.popup();
			return false;
		} catch (err) {
			console.log('wat');
			console.log(err.message);
		}
	}	
});