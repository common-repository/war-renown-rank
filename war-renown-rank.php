<?php
/**
 * Plugin Name: WAR Renown Rank
 * Plugin URI: http://mike.roessing.ca/war-renown-rank/
 * Description: Shows the renown rank progress of a WAR character
 * Author: Mike Roessing
 * Version: 0.2
 * Author URI: http://mike.roessing.ca
 */

/**
 * Add function to widgets_init that'll load our widget.
 */
add_action('widgets_init', 'renownrank_init');

/**
 * Register our widget.
 * 'Renown_Rank' is the widget class used below.
 */
function renownrank_init() {
     register_widget('Renown_Rank');
}

/**
 * Renown_Rank class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.
 */
class Renown_Rank extends WP_Widget {

     /**
      * Widget setup.
      */
     function Renown_Rank() {
          /* Widget settings. */
          $widget_ops = array( 'classname' => 'widget_renownrank', 'description' => __('A widget that displays a WAR character\'s renown rank.', 'widget_renownrank') );

          /* Widget control settings. */
          $control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'war-renown-rank' );

          /* Create the widget. */
          $this->WP_Widget( 'war-renown-rank', __('WAR Renown Rank', 'widget_renownrank'), $widget_ops, $control_ops );
     }

     /**
      * How to display the widget on the screen.
      */
     function widget($args, $instance) {
          extract($args);

          $title = apply_filters('widget_title', $instance['title']);
          $charid = $instance['charid'];
          $serverid = $instance['serverid'];

          echo $before_widget;

          if ($title)
               echo $before_title . $title . $after_title;

          /* Set your user page based on the charid and server selected */
          $url = 'https://realmwar.warhammeronline.com/realmwar/CharacterInfo.war?id=' . $charid . '&server=' . $serverid;

          /* If there's an error reading the character page, say so gracefully, otherwise get the necessary information */
          if (!@$httpfile = file_get_contents($url)) {
               echo "Error reading Realm War page<br>for CharID $charid";
          } else {
               /* Read in character page, stripping all newlines and tabs, because preg_match() doesn't like them at all */
               $contents = str_replace(array("\r\n", "\r", "\n", "\t"), "", $httpfile);

               /* Find the character name */
               $name_pattern = '/\<div class="name"\>(\w+)\<\/div\>/i';
               preg_match($name_pattern, $contents, $name_matches);

               /* Find the renown rank */
               $rrank_pattern = '/\<div class="renown"\>(.*?)\<div class="number"\>(\d+)\<\/div\>/i';
               preg_match($rrank_pattern, $contents, $rrank_matches);

               /* Find your current renown and renown needed for next rank */
               $renown_pattern = '/Current Renown: (\d+)\/(\d+)/i';
               preg_match($renown_pattern, $contents, $renown_matches);

               $renown_percent = round(($renown_matches[1]/$renown_matches[2])*100);

?>

               <style type="text/css">
                    div.smallish-progress-wrapper {
                         position: relative;
                         border: 1px solid black;
                         background-color: #292929;
                    }
                    div.smallish-progress-bar {
                         position: absolute;
                         top: 0;
                         left: 0;
                         height: 100%;
                    }
                    div.smallish-progress-text {
                         text-align: center;
                         position: relative;
                         color: #ffffff;
                    }
               </style>
               <script type="text/javascript">
                    function drawProgressBar(color, width, percent) {
                         var pixels = width * (percent / 100);
                         document.write('<div class="smallish-progress-wrapper" style="width: ' + width + 'px">');
                         document.write('<div class="smallish-progress-bar" style="width: ' + pixels + 'px; background-color: ' + color + ';"></div>');
                         document.write('<div class="smallish-progress-text" style="width: ' + width + 'px">' + percent + '%</div>');
                         document.write('</div>');
                    }
               </script> 

               <center>
                    <b><a href="<?php echo $url; ?>"><?php echo $name_matches[1]; ?></a></b><br>
                    Renown Rank <?php echo $rrank_matches[2]; ?><br><br>
                    <?php echo $renown_matches[1] . ' / ' . $renown_matches[2]; ?><br>
                    <script type="text/javascript">drawProgressBar('#aa599c', 150, <?php echo $renown_percent; ?>);</script>
               </center>

<?php
               echo $afterwidget;
          }
     }

     /**
      * Update the widget settings.
      */
     function update($new_instance, $old_instance) {
          $instance = $old_instance;

          /* Strip tags for title, charid, and serverid to remove HTML. */
          $instance['title'] = strip_tags($new_instance['title']);
          $instance['charid'] = strip_tags($new_instance['charid']);
          $instance['serverid'] = strip_tags($new_instance['serverid']);

          return $instance;
     }

     /**
      * Displays the widget settings controls on the widget panel.
      */
     function form($instance) {
          /* Set up some default widget settings. */
          $defaults = array('title' => __('WAR Renown Rank', 'widget_renownrank'), 'charid' => __('1', 'widget_renownrank'), 'serverid' => '122');
          $instance = wp_parse_args((array) $instance, $defaults); ?>

          <!-- Widget Title: Text Input -->
          <p>
               <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'hybrid'); ?></label>
               <input id="<?php echo $this->get_field_id('title'); ?>" name=<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
          </p>

          <!-- Character ID: Text Input -->
          <p>
               <label for="<?php echo $this->get_field_id('charid'); ?>"><?php _e('Character ID:', 'widget_renownrank'); ?></label>
               <input id="<?php echo $this->get_field_id('charid'); ?>" name=<?php echo $this->get_field_name('charid'); ?>" value="<?php echo $instance['charid']; ?>" style="width:100%;" />
          </p>

          <!-- Server ID: Select Box -->
          <p>
               <label for="<?php echo $this->get_field_id('serverid'); ?>"><?php _e('Server ID:', 'serverid'); ?></label>
               <select id="<?php echo $this->get_field_id( 'serverid' ); ?>" name="<?php echo $this->get_field_name( 'serverid' ); ?>" class="widefat" style="width:100%;">
                    <option <?php if ( '2' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="2">Badlands</option>
                    <option <?php if ( '173' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="173">Dark Crag</option>
                    <option <?php if ( '159' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="159">Gorfang</option>
                    <option <?php if ( '196' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="196">Iron Rock</option>
                    <option <?php if ( '166' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="166">Phoenix Throne</option>
                    <option <?php if ( '201' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="201">Volkmar</option>
                    <option <?php if ( '122' == $instance['serverid'] ) echo 'selected="selected"'; ?> value="122">Warpstone (Test)</option>
               </select>
          </p>
     <?php
     }
}

?>
