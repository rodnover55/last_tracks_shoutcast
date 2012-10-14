<?php

require_once "last_track_admin.php";
// require_once "last_track_widget.php";

class LastTrackPlugin {

	const PREFIX = 'last_track_shoutcast';
	const INFORMATION_NONE = 0;
	const INFORMATION_MESSAGE = 1;
	const INFORMATION_FULL = 2;

	private static $options;

	public static function activate() {
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
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('exclude')] =
				get_option(LastTrackPlugin::get_name_with_prefix('exclude'), null);
		LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix('information')] =
				get_option(LastTrackPlugin::get_name_with_prefix('information'),
						LastTrackPlugin::INFORMATION_FULL);

		add_action('admin_init', array('LastTrackAdmin', 'admin_init'));
		add_action('admin_menu', array('LastTrackAdmin', 'admin_menu'));

// 		add_action('init', array(LastTrackWidget, 'process_post'));
	}

	public static function get_name_with_prefix($name) {
		return LastTrackPlugin::PREFIX . '_' . $name;
	}

	public static function get_option($option_name) {
		return LastTrackPlugin::$options[LastTrackPlugin::get_name_with_prefix($option_name)];
	}
}