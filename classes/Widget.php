<?php


/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action('widgets_init', 'medicalprice_load_widgets');

/**
 * Register our widget.
 *
 * @since 0.1
 */
function medicalprice_load_widgets() {
    register_widget('medicalprice_Widget');
}



/**
 * Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings,
  form,
  display,
  and update.  Nice!
 *
 * @since 0.1
 */
class medicalprice_Widget extends WP_Widget {

    public $tcolor = 'price_slider_tcolor';
    public $bcolor = 'price_slider_bcolor';

    /**
     * Widget setup.
     */
    function __construct() {

        /* Widget settings. */
        $widget_ops = array('classname' => 'price_slider', 'description' => __('Price Slider', 'price_slider'));

        /* Widget control settings. */
        $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'price_slider');

        /* Create the widget. */
        $this->WP_Widget('price_slider', __('Price Slider', 'brds'), $widget_ops, $control_ops);
    }

    /**
     * How to display the widget on the screen.
     */
    function widget($args, $instance) {
        extract($args);

        /* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title']);
        $content = $instance['content'];

        $id = rand($min = 0, $max = 31100);


        /* Before widget (defined by themes). */
        echo $before_widget;

        $tcolor = get_option($this->tcolor);
        if (!$tcolor)
            $tcolor = 'fff';
        $bcolor = get_option($this->bcolor);
        if (!$bcolor)
            $bcolor = '000';

        echo <<<EOT

<style type='text/css'>
#boxx{$id} {
    position: fixed;
    z-index: 999;
    bottom: -100px;
    right: 0;
    width: 300px;
    height: 120px;
    color: #{$tcolor};
    background-color: #{$bcolor};
    text-align: center;
}

#labelx{$id} {
    height: 20px;
    cursor: pointer;
}
</style>


<div id="boxx{$id}">
    <div id="labelx{$id}">{$title}</div><br />
    <div id="contentsx{$id}">{$content}</div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready( function () {
$('#boxx{$id}').bind('mouseenter', function() {
 $(this).stop().animate({ bottom: 0 }, 350);
}).bind('mouseleave', function() {
   $(this).stop().animate({ bottom: -100 }, 350);
});

} ) ;

</script>

EOT;


        /* After widget (defined by themes). */
        echo $after_widget;
    }

    /**
     * Update the widget settings.
     */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['content'] = $new_instance['content'];
//        strip_tags($new_instance['name']);

        /* No need to strip tags for sex and show_sex. */
//        $instance['sex'] = $new_instance['sex'];
//        $instance['show_sex'] = $new_instance['show_sex'];

        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form($instance) {

        /* Set up some default widget settings. */
        $defaults = array('title' => __('Slider', 'exbr'), 'content' => __('', 'exbr'));
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'hybrid'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:98%;" />
        </p>

        <!-- Your Name: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Content', 'exbr'); ?></label>
            <textarea id="<?php echo $this->get_field_id('content'); ?>" name="<?php echo $this->get_field_name('content'); ?>" style="width:100%; height: 150px;"><?php echo $instance['content']; ?></textarea>
        </p>


        <?php
    }

}

?>