<?php

class ElementsLibrary {
	public static function draw_input($tags) {
		$html = '<input ';
		foreach ($tags as $key => $value) {
			if (isset($value)) {
				$html .= $key .  '="' . $value .'" ';
			}
		}
		$html .= ' />';

		return $html;
	}

	public static function draw_text($name, $value = null, $size = null, $tags = array()) {
		$tags = array_merge(array(
				'name' => $name,
				'value' => $value,
				'size' => $size,
				'type' => 'text'), $tags);
		return ElementsLibrary::draw_input($tags);
	}

	public static function echo_text($name, $value = null, $size = null, $tags = array()) {
		return function() use ($name, $value, $size, $tags) {
			echo ElementsLibrary::draw_text($name, $value, $size, $tags);
		};
	}

	public static function draw_checkbox($name, $value = null, $tags = array()) {
		$tags = array_merge(array(
				'name' => $name,
				'value' => $name,
				'type' => 'checkbox'), $tags);
		if ($value != '') {
			$tags['checked'] = '';

		}
		return ElementsLibrary::draw_input($tags);
	}

	public static function echo_checkbox($name, $value = null, $tags = array()) {
		return function() use ($name, $value, $tags) {
			echo ElementsLibrary::draw_checkbox($name, $value, $tags);
		};
	}

	public static function draw_select($name, $options, $value_selected = null, $tags = array()) {
		$tags = array_merge(array(
				'name' => $name), $tags);

		$html = '<select ';

		foreach ($tags as $key => $value) {
			if (isset($value)) {
				$html .= $key .  '="' . $value .'" ';
			}
		}

		$html .= '>';

		foreach ($options as $value => $label) {
			$html .= '<option value="' . $value . '" ';

			if ($value_selected == $value) {
				$html .= 'selected';
			}

			$html .= '>' . $label . '</option>';
		}

		$html .= '</select>';

		return $html;
	}

	public static function echo_select($name, $options, $value = null, $tags = array()) {
		return function() use ($name, $options, $value, $tags) {
			echo ElementsLibrary::draw_select($name, $options, $value, $tags);
		};
	}
}