(function($){
	$(function() {
		setInterval(function(){
			$.post('/wp-admin/admin-ajax.php', {action: 'last_track_shoutcast', rnd: Math.random()},
				function(response) {
					$('#last_track_shoutcast').children('.widget-title').html(response.title);
					$('#last_track_shoutcast').children('.widget-title').next().
						html(response.content);
				}, 'json');
			}, 60000);
 	})
 })(jQuery);