<div>
<?php
  include_once "text_with_link.php";
  if (isset($songs['errors'])) {
  	switch ($information) {
  		case LastTrackPlugin::INFORMATION_MESSAGE:
  			echo $information_message;
  			break;
  		case LastTrackPlugin::INFORMATION_FULL:
          foreach($songs['errors'] as $error) { ?>
            <?php _e('Errors:', LastTrackPlugin::domain());
              echo $error; ?><br/><?php
          }
          break;
    	}
    	return;
    }
    else {?>
        <div>
          <div class="current_song_title"><?php echo $current_song; ?></div>
            <?php
              text_with_link($href,
                "<div class='current_song_artist'>{$songs['current']['artist']}</div>"
                . "<div class='current_song_track'>{$songs['current']['track']}</div>"); ?><br/>
        </div>
        <?php if (isset($songs['lasts']) && is_array($songs['lasts'])) { ?>
        <div><div class="last_songs_title"><?php echo $last_songs; ?></div>
          <ul>
          <?php foreach ($songs['lasts'] as $song) { ?>
            <li class="last_songs">
              <div class="last_song_artist"><?php echo $song['artist']; ?></div>
              <div class="last_song_track"><?php echo $song['track']; ?></div>
            </li>
            <?php
          } ?>
          </ul>
        </div><?php 
        } ?>
      <?php
    }?>
</div>