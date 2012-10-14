<?php
  if (isset($songs['errors'])) {
  	switch ($information) {
  		case LastTrackPlugin::INFORMATION_MESSAGE:
  			echo $information_message;
  			echo $after_widget;
  			break;
  		case LastTrackPlugin::INFORMATION_FULL:
          foreach($songs['errors'] as $error) { ?>
            <div> <?php _e('Errors:', LastTrackPlugin::domain()) ?>
            <?php echo $error; ?><br/>
            </div>
            <?php
          }
          echo $after_widget;
          break;
    	}
    	return;
    }
    else {?>
      <div>
        <div class="current_song_title"><?php echo $current_song; ?><br/>
          <div class="current_song"><?php echo $songs['current']; ?></div>
        </div><br/>
      <div><div class="last_songs_title"><?php echo $last_songs; ?></div>
      <?php foreach ($songs['lasts'] as $song) { ?>
        <div class="last_song"><?php echo $song; ?></div>
        <?php
      } ?>
      </div>
      <?php
    }?>
