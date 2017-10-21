<?php
class TitoWidget extends WP_Widget
{
    /**
   * To create the example widget all four methods will be
   * nested inside this single instance of the WP_Widget class.
   **/
  public function __construct()
  {
      $widget_options = array(
       'classname' => 'Custom Tito Widget',
       'description' => 'This is an Example Widget',
     );
      parent::__construct('tito_widget', 'Tito Widget', $widget_options);
  }
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance[ 'title' ]);
        $radio_buttons = $instance['radio_buttons'];

        $blog_title = get_bloginfo('name');
        $tagline = get_bloginfo('description');
        echo $args['before_widget'].$args['before_title'].$title.$args['after_title'];

        if ($radio_buttons == 'radio_option_1') {
          ?>
            <tito-button event="nodeforum/node17festivalplus-copy"></tito-button>
          <?php
        } else {
          ?>
            <tito-widget event="nodeforum/node17festivalplus-copy"></tito-widget>
          <?php
        }
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $radio_buttons = esc_attr($instance['radio_buttons']);
        ?>
<p>
 <label for="<?php echo $this->get_field_id('title');
        ?>">Title:</label>
 <input type="text" id="<?php echo $this->get_field_id('title');
        ?>" name="<?php echo $this->get_field_name('title');
        ?>" value="<?php echo esc_attr($title);
        ?>" />
 <label for="<?php echo $this->get_field_id('choose');
        ?>">Choose:</label>
    <p>
     <label for="<?php echo $this->get_field_id('text_area');
        ?>">
         <?php echo 'Radio buttons';
        ?>
     </label><br>
     <label for="<?php echo $this->get_field_id('radio_buttons');
        ?>">
         <?php _e(' Small - Button:');
        ?>
         <input class="" id="<?php echo $this->get_field_id('radio_option_1');
        ?>" name="<?php echo $this->get_field_name('radio_buttons');
        ?>" type="radio" value="radio_option_1" <?php if ($radio_buttons === 'radio_option_1') {
    echo 'checked="checked"';
}
        ?> />
     </label><br>
     <label for="<?php echo $this->get_field_id('radio_buttons');
        ?>">
         <?php _e('Full Formular:');
        ?>
         <input class="" id="<?php echo $this->get_field_id('radio_option_2');
        ?>" name="<?php echo $this->get_field_name('radio_buttons');
        ?>" type="radio" value="radio_option_2" <?php if ($radio_buttons === 'radio_option_2') {
    echo 'checked="checked"';
}
        ?> />
     </label>
     </p>

</p><?php

    }
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags($new_instance[ 'title' ]);
        $instance['radio_buttons'] = strip_tags($new_instance['radio_buttons']);

        return $instance;
    }
}

function jpen_register_widget()
{
    register_widget('TitoWidget');
}
add_action('widgets_init', 'jpen_register_widget');
?>
