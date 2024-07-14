<?php
defined('ABSPATH') || exit;

/**
 * Class Demo_Widgets
 *
 * @since 1.0.0
 */
class Demo_Widgets extends WP_Widget {

    public function __construct()
    {
        parent::__construct(
            'demowidgets',
            __('Demo Widgets', 'demo-widgets'),
            __('Our Demo Widgets Descriptions', 'demo-widgets')
        );

        add_action('plugins_loaded', array($this, 'demo_widgets_textdomain'));
    }

    /**
     * Load text domain.
     *
     * @return void
     * @since 1.0.0
     */
    public function demo_widgets_textdomain()
    {
        load_plugin_textdomain('demo-widgets', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Outputs the options form on admin.
     *
     * @param array $instance The widget options.
     */
    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : __('Demo Widgets', 'demo-widgets');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title', 'demo-widgets'); ?></label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Outputs the content of the widget.
     *
     * @param array $args Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
     * @param array $instance The settings for the particular instance of the widget.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo 'This is the content of the Demo Widget.'; // Replace this with your widget content
        echo $args['after_widget'];
    }

    /**
     * Processing widget options on save.
     *
     * @param array $new_instance The new options.
     * @param array $old_instance The previous options.
     * @return array Updated options to save.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }

}

