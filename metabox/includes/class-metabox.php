<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class Wp_Mata_Box.
 *
 * @since 1.0.0
 */

class Wp_Mata_Box{

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
    public function __construct( $file, $version = '1.0.0' ) {
        $this->file = $file;
        $this->version = $version;
        $this->define_constant();
        $this->activation();
        $this->init_hooks();
    }

    /**
     * Define constant.
     *
     * @since 1.0.0
     * @return void
     */
    public function define_constant(){
        define( 'MTB_VERSION', $this->version );
        define( 'MTB_PLUGIN_DIR', plugin_dir_path( $this->file ) );
        define( 'MTB_PLUGIN_URL', plugin_dir_url( $this->file ) );
        define( 'MTB_PLUGIN_BASENAME', plugin_basename( $this->file ) );
    }

    /**
     * Activation.
     *
     * @since 1.0.0
     * @return void
     */
    public function activation() {
        register_activation_hook( $this->file, array( $this, 'activation_hook' ) );
    }

    /**
     * Activation hook.
     *
     * @since 1.0.0
     * @return void
     */
    public function activation_hook() {
        update_option( 'metabox_version', $this->version );
    }

    /**
     * Init hooks.
     *
     * @since 1.0.0
     * @return void
     */

    public function init_hooks() {
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ));
        add_action('admin_menu', array( $this, 'add_meta_location' ));
        add_action('save_post', array( $this, 'mtb_save_location' ));
    }

    /**
     * Load textdomain.
     *
     * @since 1.0.0
     * @return void
     */

    public function load_textdomain() {
        load_plugin_textdomain( 'meta-box', false, plugin_basename( $this->file ) );
    }
    /**
     * Initialize Meta Field.
     *
     * @since 1.0.0
     * @return void
     */
    public function add_meta_location() {
        add_meta_box('mtb_post_location',__('Locations Info','meta-box'),array( $this, 'mtb_display_meta_locations'), 'post', );
    }
    /**
     * Display meta locations filed.
     *
     * @since 1.0.0
     * @return void
     */
    public function mtb_display_meta_locations($post) {
        $locations=get_post_meta( get_the_ID($post->ID), 'mtb_post_location', true );
        wp_nonce_field( 'mtb_post_location_nonce_action', 'mtb_post_location_nonce' );
       $label=__('Location:','meta-box');
       $metaBox_html=<<<EOD
      <p>
      <label for="mtb_post_locations">$label</label>
       <input type="text" id="mtb_post_location" name="mtb_post_location" value="{$locations}" />
      </p>
     EOD;
      echo $metaBox_html;
    }
    /**
     * secured nonce field
     *
     * @since 1.0.0
     * @return boolean
     */
    private function is_secured($nonce_filed,$action,$post_id)
    {
        $nonce=isset($_POST[$nonce_filed])?$_POST[$nonce_filed]:'';
        if($nonce==''){
            return false;
        }
        if(!wp_verify_nonce($nonce,$action)){
            return false;
        }
        if(!current_user_can('edit_post', $post_id)){
            return false;
        }
        if(wp_is_post_autosave($post_id)){
            return false;
        }
        if(wp_is_post_revision($post_id)){
            return false;
        }
        return true;
    }
    /**
     * Save meta locations filed.
     *
     * @since 1.0.0
     * @return void
     */
    public function mtb_save_location( $post_id ) {
        if(!$this->is_secured('mtb_post_location_nonce','mtb_post_location_nonce_action', $post_id)) {
            return $post_id;
        }
            $location=isset($_POST['mtb_post_location']) ? $_POST['mtb_post_location'] : '';
    if($location==''){
      return $post_id;
    } else{
        update_post_meta( $post_id, 'mtb_post_location', $location );
    }
    }

}