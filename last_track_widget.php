<?php

require_once "last_track_plugin.php";
require_once "last_track_request.php";
require_once "elements-library.php";

class LastTrackWidget {
	public static function init() {
		wp_register_sidebar_widget(LastTrackPlugin::PREFIX, __('Shoutcast last tracks',
				LastTrackPlugin::domain()), array(__CLASS__, 'draw'));
		wp_register_widget_control(LastTrackPlugin::PREFIX, __('Shoutcast last tracks',
				LastTrackPlugin::domain()), array(__CLASS__, 'settings'));
		add_action('wp_ajax_' . LastTrackPlugin::PREFIX, array(__CLASS__, 'ajax'));
	}

	private static function echo_template() {
		$connect_options = array(
				'url' => LastTrackPlugin::get_option('url'),
				'require_auth' => (LastTrackPlugin::get_option('require_auth') != ''),
				'login' => LastTrackPlugin::get_option('login'),
				'password' => LastTrackPlugin::get_option('password'),
				'connect_timeout' => LastTrackPlugin::get_option('connect_timeout'),
				'timeout' => LastTrackPlugin::get_option('timeout'),
				'exclude' => LastTrackPlugin::get_option('exclude'));

		$information = LastTrackPlugin::get_option('information');
		$information_message = LastTrackPlugin::get_option('information_message');
		$current_song = LastTrackPlugin::get_option('current_song');
		$last_songs = LastTrackPlugin::get_option('last_songs');
		$count_songs = LastTrackPlugin::get_option('count_songs');

		$lt_request = new LastTrackRequest($connect_options);
		$songs = $lt_request->get_last_songs($count_songs);

		include 'templates/widget_page.php';
	}

	private static function get_template() {
		ob_start();

		LastTrackWidget::echo_template();

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public static function draw($args) {
		extract($args);

		echo $before_widget;
		echo $before_title;
		echo LastTrackPlugin::get_option('title');
		echo $after_title;

		LastTrackWidget::echo_template();

		wp_enqueue_script("jquery");
		wp_enqueue_script(LastTrackPlugin::get_name_with_prefix('widget_js'),
				plugins_url('templates/js/widget.js', __FILE__), array('jquery'));

		echo $after_widget;
	}

	public static function settings() {
		$post_options = array(LastTrackPlugin::get_name_with_prefix('information'),
				LastTrackPlugin::get_name_with_prefix('information_message'),
				LastTrackPlugin::get_name_with_prefix('title'),
				LastTrackPlugin::get_name_with_prefix('current_song'),
				LastTrackPlugin::get_name_with_prefix('last_songs'),
				LastTrackPlugin::get_name_with_prefix('count_songs'));

		foreach($post_options as $option) {
			if (!empty($_REQUEST[$option])) {
				update_option($option, $_REQUEST[$option]);
			}
		}


		$options = array(
				LastTrackPlugin::INFORMATION_NONE => __('None', LastTrackPlugin::domain()),
				LastTrackPlugin::INFORMATION_MESSAGE => __('Specified message',
						LastTrackPlugin::domain()),
				LastTrackPlugin::INFORMATION_FULL => __('Full', LastTrackPlugin::domain()));

		echo ElementsLibrary::draw_label(__('Error notification', LastTrackPlugin::domain()));
		echo ElementsLibrary::draw_select(LastTrackPlugin::get_name_with_prefix('information'),
				$options, LastTrackPlugin::get_option('information'));
		echo ElementsLibrary::draw_label(__('Error message', LastTrackPlugin::domain()));
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('information_message'),
				LastTrackPlugin::get_option('information_message'));
		echo ElementsLibrary::draw_label(__('Title', LastTrackPlugin::domain()));
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('title'),
				LastTrackPlugin::get_option('title'));
		echo ElementsLibrary::draw_label(__('Current track title', LastTrackPlugin::domain()));
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('current_song'),
				LastTrackPlugin::get_option('current_song'));
		echo ElementsLibrary::draw_label(__('Last tracks title', LastTrackPlugin::domain()));
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('last_songs'),
				LastTrackPlugin::get_option('last_songs'));
		echo ElementsLibrary::draw_label(__('Count last songs', LastTrackPlugin::domain()));
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('count_songs'),
				LastTrackPlugin::get_option('count_songs'));
	}

	public static function ajax() {
		$ajax = array(
			'title' => LastTrackPlugin::get_option('title'),
			'content' => LastTrackWidget::get_template());
		exit(json_encode($ajax));
	}
}