<?php
defined('ABSPATH') || exit;

/**
 * Class Post View Counter
 *
 * @since 1.0.0
 */
class Post_View_Counter
{
    /**
     * File.
     *
     * @var string $file File.
     *
     * @since 1.0.0
     */
    public string $file;

    /**
     * Plugin Version.
     *
     * @var mixed|string $version Version.
     *
     * @since 1.0.0
     */
    public string $version = '1.0.0';

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct($file, $version = '1.0.0')
    {
        $this->file = $file;
        $this->version = $version;
        $this->define_constant();
        $this->activations();
        $this->init_hooks();

    }

    /**
     * Define constant.
     *
     * @return void
     * @since 1.0.0
     */
    public function define_constant()
    {
        define('WPPVC_VERSION', $this->version);
        define('WPPVC_PLUGIN_PATH', plugin_dir_path($this->file));
        define('WPPVC_PLUGIN_URL', plugin_dir_url($this->file));
        define('WPPVC_PLUGIN_BASENAME', plugin_basename($this->file));

    }

    /**
     * Activations .
     *
     * @return void
     * @since 1.0.0
     */
    public function activations()
    {
        register_activation_hook($this->file, array($this, 'activation_hook'));
        register_deactivation_hook($this->file, array($this, 'deactivation_hook'));

    }

    /**
     * Activation hook .
     *
     * @return void
     * @since 1.0.0
     */
    public function activation_hook()
    {
        update_option('WPPVC_VERSION', $this->version);
        if (!wp_next_scheduled('pvc_reset_daily_views')) {
            wp_schedule_event(time(), 'daily', 'pvc_reset_daily_views');
        }
        if (!wp_next_scheduled('pvc_reset_monthly_views')) {
            wp_schedule_event(time(), 'monthly', 'pvc_reset_monthly_views');
        }
    }

    /**
     * Deactivation hook.
     *
     * @return void
     * @since 1.0.0
     */
    public function deactivation_hook()
    {
        wp_clear_scheduled_hook('pvc_reset_daily_views');
        wp_clear_scheduled_hook('pvc_reset_monthly_views');
    }

    /**
     * Init hooks.
     *
     * @return void
     * @since 1.0.0
     */
    public function init_hooks()
    {
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_pvc_count_view', array($this, 'count_view'));
        add_action('wp_ajax_nopriv_pvc_count_view', array($this, 'count_view'));
        add_filter('manage_posts_columns', array($this, 'add_views_column'));
        add_action('manage_posts_custom_column', array($this, 'display_views_column'), 10, 2);
        add_action('pvc_reset_daily_views', array($this, 'reset_daily_views'));
        add_action('pvc_reset_monthly_views', array($this, 'reset_monthly_views'));

        add_filter('the_content', array($this, 'append_view_counts'));
        add_action('wp_head', array($this, 'display_view_counts'));

        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    /**
     * load text domain.
     *
     * @return void
     * @since 1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain('WPPVC', false, plugin_basename($this->file));
    }

    /**
     * Enqueue the JavaScript file.
     *
     * @return void
     * @since 1.0.0
     */
    public function enqueue_scripts()
    {
        if (is_single()) {
            wp_enqueue_script('pvc-views-counter', plugin_dir_url($this->file) . 'view_count.js', array('jquery'), $this->version, true);
            wp_localize_script('pvc-views-counter', 'pvc_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'post_id' => get_the_ID()
            ));
        }
    }

    /**
     * Count views.
     *
     * @return void
     * @since 1.0.0
     */
    public function count_view()
    {
        if (!isset($_POST['post_id'])) {
            wp_die();
        }

        $post_id = intval($_POST['post_id']);

        // Verify post ID
        if (get_post_status($post_id) != 'publish') {
            wp_die();
        }

        // Get current counts
        $views = get_post_meta($post_id, 'pvc_views', true);
        $daily_views = get_post_meta($post_id, 'pvc_daily_views', true);

        // Increment counts
        $views = (int)$views + 1;
        $daily_views = (int)$daily_views + 1;

        // Update counts
        update_post_meta($post_id, 'pvc_views', $views);
        update_post_meta($post_id, 'pvc_daily_views', $daily_views);

        wp_die();
    }

    /**
     * Add views column.
     *
     * @return array
     * @since 1.0.0
     */
    public function add_views_column($columns)
    {
        $columns['views'] = 'Total Views';
        $columns['daily_views'] = 'Daily Views';
        return $columns;
    }

    /**
     * Display views column.
     *
     * @return void
     * @since 1.0.0
     */
    public function display_views_column($column, $post_id)
    {
        if ($column == 'views') {
            echo (int)get_post_meta($post_id, 'pvc_views', true);
        } elseif ($column == 'daily_views') {
            echo (int)get_post_meta($post_id, 'pvc_daily_views', true);
        }
    }

    /**
     * Reset daily views.
     *
     * @return void
     * @since 1.0.0
     */
    public function reset_daily_views()
    {
        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_value = 0 WHERE meta_key = 'pvc_daily_views'");
    }

    /**
     * Reset monthly views.
     *
     * @return void
     * @since 1.0.0
     */
    public function reset_monthly_views()
    {
        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->postmeta} SET meta_value = 0 WHERE meta_key = 'pvc_monthly_views'");
    }

    /**
     * Append view counts to the post content.
     *
     * @param string $content The post content.
     * @return string The post content with view counts appended.
     */
    public function append_view_counts($content)
    {
        if (is_single()) {
            $view_counts = $this->display_view_counts();
            $content .= $view_counts;
        }
        return $content;
    }

    /**
     * Display view counts on the single post page.
     *
     * @return void
     * @since 1.0.0
     */
    public function display_view_counts()
    {

        if (is_single()) {
            global $post;
            $total_views = get_post_meta($post->ID, 'pvc_views', true);
            $daily_views = get_post_meta($post->ID, 'pvc_daily_views', true);

            $output = '<div class="post-view-counts">';
            $output .= '<p>Total Views: ' . (int)$total_views . '</p>';
            $output .= '<p>Today\'s Views: ' . (int)$daily_views . '</p>';
            $output .= '</div>';

            return $output;
        }
        return '';
    }

    /**
     * Add admin menu.
     *
     * @return void
     * @since 1.0.0
     */
    public function add_admin_menu()
    {
        add_menu_page(
            'Post View Counter Settings',
            'Post View Counter',
            'manage_options',
            'post-view-counter',
            array($this, 'admin_settings_page'),
            'dashicons-visibility'
        );
    }

    /**
     * Admin settings page.
     *
     * @return void
     * @since 1.0.0
     */
    public function admin_settings_page()
    {

        echo '<div class="wrap">';
        echo '<h1>Post View Counter Settings</h1>';

        echo " </div>";

    }

}

