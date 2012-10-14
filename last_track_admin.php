<?php

require_once 'elements-library.php';
require_once "last_track_plugin.php";

class LastTrackAdmin {
	public static function admin_init() {
		add_settings_section(LastTrackPlugin::get_name_with_prefix('general'),
				__('General', LastTrackPlugin::domain()), function(){},
				LastTrackPlugin::get_name_with_prefix('options'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('url'),
				__('Link', LastTrackPlugin::domain()),
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('url'),
						LastTrackPlugin::get_option('url')),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		$is_require_auth = (LastTrackPlugin::get_option('require_auth') != '');

		add_settings_field(LastTrackPlugin::get_name_with_prefix('require_auth'),
				__('Use authrization', LastTrackPlugin::domain()),
				ElementsLibrary::echo_checkbox(
						LastTrackPlugin::get_name_with_prefix('require_auth'), $is_require_auth),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		$tags = array();

		if (!$is_require_auth) {
			$tags['readonly'] = true;
		};

		add_settings_field(LastTrackPlugin::get_name_with_prefix('login'),
				__('Login', LastTrackPlugin::domain()),
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('login'),
						($is_require_auth)?(LastTrackPlugin::get_option('login')):(''),
						null, $tags),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('password'),
				__('Password', LastTrackPlugin::domain()),
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('password'),
						($is_require_auth)?(LastTrackPlugin::get_option('password')):(''),
						null, $tags),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('exclude'),
				__('Excludes', LastTrackPlugin::domain()),
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('exclude'),
						LastTrackPlugin::get_option('exclude')),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('connect_timeout'),
				__('Connect timeout', LastTrackPlugin::domain()),
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('connect_timeout'),
						LastTrackPlugin::get_option('connect_timeout')),
				LastTrackPlugin::get_name_with_prefix('options'),
				LastTrackPlugin::get_name_with_prefix('general'));

		add_settings_field(LastTrackPlugin::get_name_with_prefix('timeout'),
				__('Timeout', LastTrackPlugin::domain()),
				ElementsLibrary::echo_text(LastTrackPlugin::get_name_with_prefix('timeout'),
						LastTrackPlugin::get_option('timeout')),
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
				LastTrackPlugin::get_name_with_prefix('connect_timeout'));
		register_setting(LastTrackPlugin::PREFIX,
				LastTrackPlugin::get_name_with_prefix('timeout'));
	}

	public static function admin_menu() {
		add_options_page(__('Last track shoutcast settings', LastTrackPlugin::domain()),
				__('Last tracks shoutcast', LastTrackPlugin::domain()), 'manage_options',
				LastTrackPlugin::PREFIX, array(__CLASS__, 'get_options'));
	}

	public static function get_options() {
		include "templates/admin_page.php";
	}
}