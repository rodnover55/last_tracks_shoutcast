<?php

require_once "last_track_plugin.php";
require_once "last_track_request.php";
require_once "elements-library.php";

class LastTrackWidget {
	public static function init() {
		wp_register_sidebar_widget(LastTrackPlugin::PREFIX, 'Последние треки', array(__CLASS__, 'draw'));
		wp_register_widget_control(LastTrackPlugin::PREFIX, 'Последние треки', array(__CLASS__, 'settings'));
	}

	public static function draw($args) {
		extract($args);

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
		$title = LastTrackPlugin::get_option('title');
		$current_song = LastTrackPlugin::get_option('current_song');
		$last_songs = LastTrackPlugin::get_option('last_songs');
		$count_songs = LastTrackPlugin::get_option('count_songs');

		$lt_request = new LastTrackRequest($connect_options);
		$songs = $lt_request->get_last_songs($count_songs);

		include 'templates/widget_page.php';
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
				LastTrackPlugin::INFORMATION_NONE => 'Нет',
				LastTrackPlugin::INFORMATION_MESSAGE => 'Сообщение',
				LastTrackPlugin::INFORMATION_FULL => 'Полная');

		echo ElementsLibrary::draw_label('Оповещения об ошибках');
		echo ElementsLibrary::draw_select(LastTrackPlugin::get_name_with_prefix('information'),
				$options, LastTrackPlugin::get_option('information'));
		echo ElementsLibrary::draw_label('Сообщение об ошибке');
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('information_message'),
				LastTrackPlugin::get_option('information_message'));
		echo ElementsLibrary::draw_label('Заголовок');
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('title'),
				LastTrackPlugin::get_option('title'));
		echo ElementsLibrary::draw_label('Заголовок текущей');
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('current_song'),
				LastTrackPlugin::get_option('current_song'));
		echo ElementsLibrary::draw_label('Заголовок последних');
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('last_songs'),
				LastTrackPlugin::get_option('last_songs'));
		echo ElementsLibrary::draw_label('Количество песен');
		echo ElementsLibrary::draw_text(LastTrackPlugin::get_name_with_prefix('count_songs'),
				LastTrackPlugin::get_option('count_songs'));
	}
}