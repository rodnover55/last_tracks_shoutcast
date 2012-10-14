<?php

require_once 'elements-library.php';
require_once "last_track_plugin.php";

class LastTrackAdmin {
	public static function admin_init() {
		add_settings_section(LastTrackPlugin::get_name_with_prefix('general'),
				'Основные настройки', function(){},
				LastTrackPlugin::get_name_with_prefix('options'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('url'), 'Ссылка',
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('url'),
						LastTrackPlugin::get_option('url')),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		$is_require_auth = (LastTrackPlugin::get_option('require_auth') != '');

		add_settings_field(LastTrackPlugin::get_name_with_prefix('require_auth'),
				'Использовать авторизацию',
				ElementsLibrary::echo_checkbox(
						LastTrackPlugin::get_name_with_prefix('require_auth'), $is_require_auth),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		$tags = array();

		if (!$is_require_auth) {
			$tags['readonly'] = true;
		};

		add_settings_field(LastTrackPlugin::get_name_with_prefix('login'), 'Имя пользователя',
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('login'),
						($is_require_auth)?(LastTrackPlugin::get_option('login')):(''),
						null, $tags),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('password'), 'Пароль',
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('password'),
						($is_require_auth)?(LastTrackPlugin::get_option('password')):(''),
						null, $tags),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('exclude'), 'Исключения',
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('exclude'),
						LastTrackPlugin::get_option('exclude')),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		$options = array(
				LastTrackPlugin::INFORMATION_NONE => 'Нет',
				LastTrackPlugin::INFORMATION_MESSAGE => 'Сообщение',
				LastTrackPlugin::INFORMATION_FULL => 'Полная');

		add_settings_field(LastTrackPlugin::get_name_with_prefix('information'),
				'Оповещения об ошибках',
				ElementsLibrary::echo_select(
						LastTrackPlugin::get_name_with_prefix('information'), $options,
						LastTrackPlugin::get_option('information')),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('url'));
		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('require_auth'));
		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('login'));
		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('password'));
		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('exclude'));
		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('information'));
	}

	public static function admin_menu() {
		add_options_page('last_track_shoutcast', 'Last tracks shoutcast', 'manage_options',
				LastTrackPlugin::PREFIX, array(__CLASS__, 'get_options'));
	}

	public static function get_options() {
		include "admin_page.php";
	}
}