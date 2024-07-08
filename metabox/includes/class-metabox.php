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
        add_action('save_post', array($this, 'mtb_image_save_field'));
        add_action('save_post', array($this, 'mtb_gellery_save_field'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_assets'));
        //user profile contact info
        add_filter('user_contactmethods', array($this, 'user_contact_method_render'));
        // Category texonomy
        add_action('init', array($this, 'mtb_texonomy_render'));
        //Category
        add_action('category_add_form_fields', array($this, 'mtb_category_add_form_fields'));
        add_action('category_edit_form_fields', array($this, 'mtb_category_edit_form_fields'));
        add_action('create_category', array($this, 'mtb_category_meta_save'));
        add_action('edit_category', array($this, 'mtb_category_update_meta_save'));
        //Tags
        add_action('post_tag_add_form_fields', array($this, 'mtb_category_add_form_fields'));
        add_action('post_tag_edit_form_fields', array($this, 'mtb_category_edit_form_fields'));
        add_action('create_post_tag', array($this, 'mtb_category_meta_save'));
        add_action('edit_post_tag', array($this, 'mtb_category_update_meta_save'));
        // select post meta
        add_action('save_post', array($this, 'mtb_display_select_metabox_save'));

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
     * Add assets for admin sections
     *
     * @return void
     * @since 1.0.0
     */
    public function admin_enqueue_assets(){
      wp_enqueue_style('mtb_style', plugin_dir_url($this->file) . 'assets/admin/css/style.css');
      wp_enqueue_script('mtb_script', plugin_dir_url($this->file) . 'assets/admin/js/main.js', array('jquery'),'1.0.0', true);

    }

    /**
     * Initialize Meta Field.
     *
     * @return void
     * @since 1.0.0
     */
    public function add_meta_field()
    {
        add_meta_box('mtb_post_location',
            __('Locations Info', 'meta-box'),
            array($this, 'mtb_display_metabox'),
            'post');

        add_meta_box('mtb_images_metabox',
        __('Images Info', 'meta-box'),
        array($this, 'mtb_display_images_metabox'),
        'post');
        add_meta_box('mtb_gellery_metabox',
            __('Images Gellery', 'meta-box'),
            array($this, 'mtb_display_gellery_metabox'),
            'post');
        add_meta_box(
            'mtb_selected_posts',
            __('Select Posts', 'meta-box'),
            array($this, 'mtb_display_select_metabox'),
            'page'
        );
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

    public function mtb_display_images_metabox($post){
        wp_nonce_field('mtb_post_image_nonce_action', 'mtb_post_image_nonce');
        $image_id= esc_attr(get_post_meta($post->ID, 'mtb_post_image_id', true));
        $image_url= esc_attr(get_post_meta($post->ID, 'mtb_post_image_url', true));

        $imgage_html=<<<EOD
       <div>
       <label>Images</label>
        <button type="button" id="mtb_upload_img" class="button">Upload Image</button>
        <input type="hidden" id="mtb-image-id" name="mtb_image_id" value="{$image_id}" />
        <input type="hidden" id="mtb_img_url" name="mtb_img_url" value="{$image_url}" />
        <div id="mtb_images_container" style="width: 100%; height:auto;" ></div>
        
      </div>
    </div>
    EOD;

        echo $imgage_html;
    }

    public function mtb_display_gellery_metabox($post){
        wp_nonce_field('mtb_post_gellery_nonce_action', 'mtb_post_gellery_nonce');
        $images_id= esc_attr(get_post_meta($post->ID, 'mtb_post_gellery_ids', true));
        $images_url= esc_attr(get_post_meta($post->ID, 'mtb_post_gellery_urls', true));

        $imgage_html=<<<EOD
       <div>
       <label>Images</label>
        <button type="button" id="mtb_upload_gellery" class="button">Gellery</button>
        <input type="hidden" id="mtb_images_id" name="mtb_images_id" value="{$images_id}" />
        <input type="hidden" id="mtb_imgs_url" name="mtb_imgs_url" value="{$images_url}" />
        <div id="mtb_gellery_container" style="width: 100%; height:auto;" ></div>
        
      </div>
    </div>
    EOD;

        echo $imgage_html;
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
    public function mtb_gellery_save_field($post_id)
    {
        if (!$this->is_secured('mtb_post_gellery_nonce', 'mtb_post_gellery_nonce_action', $post_id)) {
            return $post_id;
        }

        $images_id=isset($_POST['mtb_images_id']) ? absint($_POST['mtb_images_id']) : '';
        $images_url=isset($_POST['mtb_imgs_url']) ? esc_url($_POST['mtb_imgs_url']) : '';
        update_post_meta($post_id, 'mtb_post_gellery_ids', $images_id);
        update_post_meta($post_id, 'mtb_post_gellery_urls', $images_url);
    }

    /**
     * Save meta locations filed.
     *
     * @return void
     * @since 1.0.0
     */
    public function mtb_image_save_field($post_id)
    {
        if (!$this->is_secured('mtb_post_image_nonce', 'mtb_post_image_nonce_action', $post_id)) {
            return $post_id;
        }

        $image_id=isset($_POST['mtb_image_id']) ? absint($_POST['mtb_image_id']) : '';
        $image_url=isset($_POST['mtb_img_url']) ? esc_url($_POST['mtb_img_url']) : '';
        update_post_meta($post_id, 'mtb_post_image_id', $image_id);
        update_post_meta($post_id, 'mtb_post_image_url', $image_url);
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
    /**
     * User Contact information
     *
     * @param array $methods
     *
     * @return array
     */
    public function user_contact_method_render($methods)
    {
        $methods['facebook'] = __( 'Facebook','meta-box' );
        $methods['twitter']  = __( 'Twitter','meta-box' );
        $methods['linkedin'] = __( 'Linkdin','meta-box' );
        $methods['skype']    = __( 'Skype','meta-box' );

        return $methods;
    }
    /**
     * Texonomy field
     *
     *
     * @return void
     */

    public function mtb_texonomy_render()
    {
      $argument=array(
          'type'=>'string',
          'sanitize_callback'=>'sanitize_text_field',
          'single'=>true,
          'descriptions'=>'Simple category meta filed for texonomy',
          'show_in_rest'=>true,
      );
      register_meta('term', 'mtb_tax_extra_info', $argument);
    }
    /**
     * Texonomy field
     *
     * @return void
     */
    public function mtb_category_add_form_fields()
    {

        ?>
        <div class="form-field form-required term-name-wrap">
            <label for="tag-name"><?php _e('Extra Info','meta-box') ?></label>
            <input name="extra_info" id="extra_info" type="text" value="" size="40" aria-required="true" aria-describedby="name-description">
            <p>The Extra info is how it appears on your site.</p>
        </div>
        <?php
    }
    /**
     * Texonomy field
     *
     * @return void
     */
    public function mtb_category_edit_form_fields($term)
    {
        $extra_info = get_term_meta( $term->term_id, 'mtb_tax_extra_info', true )


        ?>
        <tr class="form-field form-required term-name-wrap">
            <th scope="row"><label for="tag-name"><?php _e('Extra Info','meta-box') ?></label>  </th>
            <td>
                <input name="extra_info" id="extra_info" type="text" value="<?php echo esc_attr( $extra_info ); ?>" size="40" aria-required="true" aria-describedby="name-description">
                <p>The Extra info is how it appears on your site.</p>
            </td>
        </tr>
        <?php
    }
    /**
     * Create category field
     *
     * @return void
     */

    public function mtb_category_meta_save($term_id)
    {
        if ( wp_verify_nonce( $_POST['_wpnonce_add-tag'], 'add-tag' ) ) {
            $extra_info = sanitize_text_field( $_POST['extra_info'] );
            update_term_meta( $term_id, 'mtb_tax_extra_info', $extra_info );
        }
    }
    /**
     * Update category field
     *
     * @return void
     */

    public function mtb_category_update_meta_save($term_id)
    {


        if ( wp_verify_nonce( $_POST['_wpnonce'], "update-tag_{$term_id}" ) ) {


            $extra_info = sanitize_text_field( $_POST['extra_info'] );
            update_term_meta( $term_id, 'mtb_tax_extra_info', $extra_info );
        }
    }

    /**
     * Display select meta
     *
     * @return void
     */
    public function mtb_display_select_metabox($post) {
        $selected_post_id = get_post_meta($post->ID,'mtb_selected_posts',true);
        wp_nonce_field( 'mtb_posts_action', 'mtb_post_nonce' );
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => - 1
        );
        $dropdown_list = '';
        $_posts        = new wp_query( $args );
        $dropdown_list = '';
        while ( $_posts->have_posts() ) {

            $_posts->the_post();
            $selected = in_array(get_the_ID(), (array) $selected_post_id) ? 'selected' : '';

            $dropdown_list .= sprintf( "<option %s value='%s'>%s</option>", $selected, get_the_ID(), get_the_title() );
        }
        wp_reset_query();
        $label = __('Select Posts', 'meta-box');
        $metabox_html = <<<EOD
        <div>
            <label for="mtb_posts">{$label}</label>
            <select multiple="multiple" name="mtb_posts[]" id="mtb_posts">
                <option value="0">{$label}</option>
                {$dropdown_list}
            </select>
        </div>
        EOD;
        echo $metabox_html;
    }

    /**
     * Display select meta
     *
     * @return mixed
     */
   public function mtb_display_select_metabox_save( $post_id ) {
        if ( !$this->is_secured( 'mtb_post_nonce', 'mtb_posts_action', $post_id ) ) {
            return $post_id;
        }

        $selected_post_id = $_POST['mtb_posts'];
        if ( $selected_post_id > 0 ) {
            update_post_meta( $post_id, 'mtb_selected_posts', $selected_post_id );
        }
        return $post_id;
    }

}