/**
 *
 */

(function($){
	$(function() {
		$('[name="last_track_shoutcast_require_auth"]').click(function() {
			if ($('[name="last_track_shoutcast_login"]').attr('readonly') === undefined) {
				$('[name="last_track_shoutcast_login"]').attr('readonly', "");
				$('[name="last_track_shoutcast_password"]').attr('readonly', "");
			}
			else {
				$('[name="last_track_shoutcast_login"]').removeAttr('readonly');
				$('[name="last_track_shoutcast_password"]').removeAttr('readonly');
			}
		});
 	})
})(jQuery);
