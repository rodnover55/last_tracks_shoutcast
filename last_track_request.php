<?php

class LastTrackRequest {
	private $url;
	private $is_require_auth;
	private $login;
	private $password;
	private $connect_timeout;
	private $timeout;

	private function set_options($curl, $options) {
		foreach ($options as $key => $value) {
			if (!curl_setopt($curl, $key, $value)) {
				return $key;
			}
		}
		return null;
	}

	function __construct($connect_options) {
		$this->url = $connect_options['url'];
		$this->is_require_auth = $connect_options['require_auth'];

		if ($this->is_require_auth) {
			$this->login = $connect_options['login'];
			$this->password = $connect_options['password'];
		}

		$this->connect_timeout = isset($connect_options['connect_timeout'])?
			($connect_options['connect_timeout']):(5);

		$this->timeout = isset($connect_options['timeout'])?
			($connect_options['timeout']):(5);

		$this->exclude = explode(';', $connect_options['exclude']);
	}

	public function get_last_songs($count = 0) {
		$songs = array();

		$curl = curl_init($this->url);

		if (!$curl) {
			$songs['errors'][] = __('Cannot init curl.', LastTrackPlugin::domain());
			return $songs;
		}

		$options = array(
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CONNECTTIMEOUT => $this->connect_timeout,
				CURLOPT_TIMEOUT => $this->timeout,
		);

		if ($this->is_require_auth) {
			$options[CURLOPT_HTTPAUTH] = CURLAUTH_ANY;
			$options[CURLOPT_USERPWD] = $this->login . ':' . $this->password;
		}

		$wrong_key = $this->set_options($curl, $options);
		if (isset($wrong_key)) {
			$songs['errors'][] = printf(__("Cannot set option '%s': %s", LastTrackPlugin::domain()),
					curl_error($curl));
			curl_close($curl);
			return $songs;
		}

		$xml = curl_exec($curl);

		if (!isset($xml)) {
			$songs['errors'][] = printf(__("Cannot exec curl: %s", LastTrackPlugin::domain()),
					curl_error($curl));
			curl_close($curl);
			return $songs;
		}

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		switch ($status) {
			case 401:
				$songs['errors'][] = __('Unauthorized.', LastTrackPlugin::domain());
				curl_close($curl);
				return $songs;
			case 200:
				break;
			default:
				$songs['errors'][] = $xml;
				curl_close($curl);
				return $songs;
		}

		$data = new SimpleXMLElement($xml);

		$counter = 0;

		foreach ($data->SONGHISTORY->SONG as $song) {
			$title = strval($song->TITLE);

			if ($counter == 0) {
				$songs['current'] = $this->parse_title($title);
				++$counter;
				continue;
			}

			$is_exclude = false;
			foreach ($this->exclude as $test) {
				if (strpos($title, $test) !== false) {
					$is_exclude = true;
					break;
				}
			}
			if (!$is_exclude) {
				$songs['lasts'][] = $this->parse_title($title);
				if (($count > 0) && ($count == $counter)) {
					break;
				}
				++$counter;
			}
		}
		return $songs;
	}

	private function parse_title($title) {
		$track = array();
	}
}