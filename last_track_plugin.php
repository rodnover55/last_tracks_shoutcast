<?php

require_once "last_track_admin.php";
require_once "last_track_widget.php";

class LastTrackPlugin {

	const PREFIX = 'last_track_shoutcast';
	const INFORMATION_NONE = 0;
	const INFORMATION_MESSAGE = 1;
	const INFORMATION_FULL = 2;

	private static $options;

	public static function domain() {
		return LastTrackPlugin::PREFIX;
	}

	public static function activate() {
		load_plugin_textdomain(LastTrackPlugin::domain(), false, dirname(plugin_basename(__FILE__)) . '/languages/');
		register_activation_hook(__FILE__, array(__CLASS__, 'install'));
		add_action('plugins_loaded', array(__CLASS__, 'loaded'));
	}

	public static function install() {
	}

	public static function loaded() {
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('url')] =
				get_option(LastTrackPlugin::get_name_with_prefix('url'), 'http://localhost:8000');
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('require_auth')] =
				get_option(LastTrackPlugin::get_name_with_prefix('require_auth'), '');
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('login')] =
				get_option(LastTrackPlugin::get_name_with_prefix('login'), '');
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('password')] =
				get_option(LastTrackPlugin::get_name_with_prefix('password'), '');
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('connect_timeout')] =
				get_option(LastTrackPlugin::get_name_with_prefix('connect_timeout'), '');
				LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('timeout')] =
				get_option(LastTrackPlugin::get_name_with_prefix('timeout'), '');

		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('exclude')] =
				get_option(LastTrackPlugin::get_name_with_prefix('exclude'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('parse_format')] =
			get_option(LastTrackPlugin::get_name_with_prefix('parse_format'), '%artist - %track');

		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('information')] =
				get_option(LastTrackPlugin::get_name_with_prefix('information'),
						LastTrackPlugin::INFORMATION_FULL);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('information_message')] =
			get_option(LastTrackPlugin::get_name_with_prefix('information_message'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('title')] =
			get_option(LastTrackPlugin::get_name_with_prefix('title'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('current_song')] =
			get_option(LastTrackPlugin::get_name_with_prefix('current_song'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('last_songs')] =
			get_option(LastTrackPlugin::get_name_with_prefix('last_songs'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('count_songs')] =
			get_option(LastTrackPlugin::get_name_with_prefix('count_songs'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('href')] =
			get_option(LastTrackPlugin::get_name_with_prefix('href'), '');


		add_action('admin_init', array('LastTrackAdmin', 'admin_init'));
		add_action('admin_menu', array('LastTrackAdmin', 'admin_menu'));

		add_action('init', array('LastTrackWidget', 'init'));
	}

	public static function get_name_with_prefix($name) {
		return LastTrackPlugin::PREFIX . '_' . $name;
	}

	public static function get_option($option_name) {
		return get_option(LastTrackPlugin::get_name_with_prefix($option_name),
				LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix($option_name)]);
	}
}