<?php

class LastTrackRequest {
	private $url;
	private $is_require_auth;
	private $login;
	private $password;
	private $connect_timeout;
	private $timeout;
	private $tags;
	private $regex;

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
		$parse_format = $connect_options['parse_format'];

		$this->matches = preg_split('/%\\w+/u', $parse_format);
		$matches_symbol = array();
		preg_match_all('/(%\w+)/', $parse_format, $matches_symbol);

		$symbol = reset($matches_symbol[0]);
		$this->regex = '/';
		$this->tags = array();
		foreach ($this->matches as $key => $value) {
			if (!empty($value)) {
				$this->regex .= preg_quote($value);
				continue;
			}

			$this->tags[] = substr($symbol, 1);
			$symbol = next($matches_symbol[0]);

			if ($symbol === false) {
				$this->regex .= '(.+)';
				break;
			} else {
				$this->regex .= '(.+?)';
			}
		}
		$this->regex .= '/u';
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

			$is_exclude = false;
			foreach ($this->exclude as $test) {
				if (strpos(mb_strtoupper($title), mb_strtoupper($test)) !== false) {
					$is_exclude = true;
					break;
				}
			}

			if ($is_exclude) {
				continue;
			}

			if ($counter == 0) {
				$songs['current'] = $this->parse_title($title);
				++$counter;
				continue;
			}

			$songs['lasts'][] = $this->parse_title($title);
			if (($count > 0) && ($count == $counter)) {
				break;
			}
			++$counter;

		}
		return $songs;
	}

	private function parse_title($title) {
		$track = array();
		$m = array();
		preg_match_all($this->regex, $title, $m);

		$current_tag = reset($this->tags);

		for($i = 1; $i < count($m); $i++) {
			$value = $m[$i];
			$track[$current_tag] = $value[0];
			$current_tag = next($this->tags);

			if ($current_tag === false) {
				break;
			}
		}

		return $track;
	}
}