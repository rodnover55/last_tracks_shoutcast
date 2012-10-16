(function($){
	$(function() {
		setInterval(function(){
			$.post('/wp-admin/admin-ajax.php', {action: 'last_track_shoutcast', rnd: Math.random()},
				function(response) {
					$('#last_track_shoutcast').children('.widgettitle').html(response.title);
					$('#last_track_shoutcast').children('.widgettitle').next().
						html(response.content);
				}, 'json');
			}, 60000);
 	})
 })(jQuery);