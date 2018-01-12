jQuery(document).ready(function($) {
	$('#warning-close').on('click', function() {
		$('#staging-warning-overlay').removeClass('show-overlay');
	});
});