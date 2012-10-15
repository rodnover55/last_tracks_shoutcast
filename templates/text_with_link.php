<?php
function text_with_link($href, $text) {
	if (empty($href)) {
		echo $text;
	} else {
		echo "<a href='$href'>$text</a>";
	}
}