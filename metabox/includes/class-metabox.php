<?php

defined('ABSPATH') || exit;

/**
 * Class Wp_Mata_Box.
 *
 * @since 1.0.0
 */
class Wp_Mata_Box
{

    public object $admin;

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
        $this->activation();
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
        define('MTB_VERSION', $this->version);
        define('MTB_PLUGIN_DIR', plugin_dir_path($this->file));
        define('MTB_PLUGIN_URL', plugin_dir_url($this->file));
        define('MTB_PLUGIN_BASENAME', plugin_basename($this->file));
    }

    /**
     * Activation.
     *
     * @return void
     * @since 1.0.0
     */
    public function activation()
    {
        register_activation_hook($this->file, array($this, 'activation_hook'));
    }

    /**
     * Activation hook.
     *
     * @return void
     * @since 1.0.0
     */
    public function activation_hook()
    {
        update_option('metabox_version', $this->version);
    }

    /**
     * Init hooks.
     *
     * @return void
     * @since 1.0.0
     */

    public function init_hooks()
    {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_menu', array($this, 'add_meta_field'));
        add_action('save_post', array($this, 'mtb_save_field'));
    }

    /**
     * Load textdomain.
     *
     * @return void
     * @since 1.0.0
     */

    public function load_textdomain()
    {
        load_plugin_textdomain('metabox', false, dirname(plugin_basename($this->file)) . '/languages/');

    }

    /**
     * Initialize Meta Field.
     *
     * @return void
     * @since 1.0.0
     */
    public function add_meta_field()
    {
        add_meta_box('mtb_post_location', __('Locations Info', 'meta-box'), array($this, 'mtb_display_metabox'), 'post',);
    }

    /**
     * Display meta locations filed.
     *
     * @return void
     * @since 1.0.0
     */
    public function mtb_display_metabox($post)
    {
        $locations = get_post_meta(get_the_ID($post->ID), 'mtb_post_location', true);
        $country = get_post_meta(get_the_ID($post->ID), 'mtb_post_country', true);
        $is_favorite = get_post_meta(get_the_ID($post->ID), 'mtb_is_favorite', true);
        $save_colors = get_post_meta(get_the_ID($post->ID), 'mtb_colors', true);
        $radio = get_post_meta(get_the_ID($post->ID), 'mtb_radio_color', true);
        $drodown_color = get_post_meta(get_the_ID($post->ID), 'mtb_dropdown_color', true);
        $checked=$is_favorite==1?'checked':'';
        wp_nonce_field('mtb_post_location_nonce_action', 'mtb_post_location_nonce');
        $label1 = __('Location:', 'meta-box');
        $label2 = __('Country:', 'meta-box');
        $label3 = __('Is Favorite:', 'meta-box');
        $label4 = __('Colors:', 'meta-box');
        $label5 = __('Colors radio:', 'meta-box');
        $colors=array('red','green','blue','yellow','pink','black','purple','brown');

        $metaBox_html = <<<EOD
       <p>
        <label for="mtb_post_locations">$label1</label>
        <input type="text" id="mtb_post_location" name="mtb_post_location" value="{$locations}" />
        <br>
         </p> 
         <p> 
        <label for="mtb_post_country">$label2</label>
        <input type="text" id="mtb_post_country" name="mtb_post_country" value="{$country}" />   
        </p> 
       <br>
       <p>
       <label for="is_favorite">$label3</label>
       <input type="checkbox" id="is_favorite" name="is_favorite" value="1" $checked/>
       </p> 
        <p>
       <label for="is_favorite">$label4</label>
       EOD;
        $save_colors=is_array($save_colors)?$save_colors:array();
       foreach ($colors as $color){
           $_color=ucwords($color);
           $checked=in_array($color,$save_colors)?'checked':'';
           $metaBox_html .=<<<EOD
           <label for="mtb_color_{$color}">{$_color}</label>
           <input type="checkbox" id="mtb_color_{$color}" name="mtb_color[]" value="{$color}" {$checked} />
           EOD;
    }
        $metaBox_html.="</p>";
       $metaBox_html.=<<<EOD
           <label for="is_favorite">$label5</label>

EOD;

        foreach ($colors as $color){
            $checked = $color == $radio ? 'checked=checked' : '';
            $_color=ucwords($color);
            $metaBox_html .=<<<EOD
           <label for="mtb_clr_{$color}">{$_color}</label>
           <input type="radio" id="mtb_clr_{$color}" name="mtb_radio_color" value="{$color}" {$checked} />
           EOD;
        }
        $metaBox_html.="</p>";
        $dropdown_html="<option value='0'>".__('Choose your color', 'meta-box')."</option>";
        foreach ($colors as $color){
            $_color=ucwords($color);
            $selected='';
            $selected=$color==$drodown_color?'selected=selected':'';
            $dropdown_html .=sprintf('<option value="%s" %s>%s</option>',$color,$selected,$_color);
        }

        $metaBox_html.=<<<EOD
           <label for="mtb_fv_color">{$label4}</label>
           <select type="checkbox" id="mtb_fv_color" name="mtb_fv_color"/>
           {$dropdown_html}
            </select>
           EOD;

        echo $metaBox_html;
    }

    /**
     * secured nonce field
     *
     * @return boolean
     * @since 1.0.0
     */
    private function is_secured($nonce_filed, $action, $post_id)
    {
        $nonce = isset($_POST[$nonce_filed]) ? $_POST[$nonce_filed] : '';
        if ($nonce == '') {
            return false;
        }
        if (!wp_verify_nonce($nonce, $action)) {
            return false;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }
        if (wp_is_post_autosave($post_id)) {
            return false;
        }
        if (wp_is_post_revision($post_id)) {
            return false;
        }
        return true;
    }

    /**
     * Save meta locations filed.
     *
     * @return void
     * @since 1.0.0
     */
    public function mtb_save_field($post_id)
    {
        if (!$this->is_secured('mtb_post_location_nonce', 'mtb_post_location_nonce_action', $post_id)) {
            return $post_id;
        }
        $location = isset($_POST['mtb_post_location']) ? $_POST['mtb_post_location'] : '';
        $country = isset($_POST['mtb_post_country']) ? $_POST['mtb_post_country'] : '';
        $is_favorite = isset($_POST['is_favorite']) ? $_POST['is_favorite'] : '';
        $colors = isset($_POST['mtb_color']) ? $_POST['mtb_color'] : array();
        $radio = isset($_POST['mtb_radio_color']) ? $_POST['mtb_radio_color'] : '';
        $dropdown_color = isset($_POST['mtb_fv_color']) ? $_POST['mtb_fv_color'] : '';
//        if ($location == '' || $country == '') {
//            return $post_id;
//        } else {
//
//
//        }
        update_post_meta($post_id, 'mtb_post_location', $location);
        update_post_meta($post_id, 'mtb_is_favorite', $country);
        update_post_meta($post_id, 'is_favorite', $is_favorite);
        update_post_meta($post_id, 'mtb_colors', $colors);
        update_post_meta($post_id, 'mtb_radio_color', $radio);
        update_post_meta($post_id, 'mtb_dropdown_color', $dropdown_color);
    }

}