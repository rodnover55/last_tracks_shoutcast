<?php require_once "last_track_plugin.php"; ?>

<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php echo 'Страница параметров'; ?></h2>
  <p></p>
  <form action="options.php" method="post">
    <?php settings_fields(LastTrackPlugin::PREFIX); ?>
    <?php do_settings_sections(LastTrackPlugin::get_name_with_prefix('options')); ?>
    <?php submit_button(); ?>
  </form>
</div>
<?php
	wp_enqueue_script("jquery");
	wp_enqueue_script(LastTrackPlugin::get_name_with_prefix('options_js'),
			plugins_url('js/options.js', __FILE__), array('jquery'));
?>

