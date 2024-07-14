<?php
/*
 * Plugin Name:       Column Management
 * Plugin URI:        https://woocopilot.com/plugins/woo-custom-price/
 * Description:       How to manage custom colum in wp.
 * Version:           1.0.1
 * Requires at least: 6.5
 * Requires PHP:      7.2
 * Author:            WooCopilot
 * Author URI:        https://woocopilot.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       clm-manage
 * Domain Path:       /languages
 */
//
/*
Column Management is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Column Management  is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Column Management. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

function clm_load_textdomain()
{
    load_plugin_textdomain('clm-manage', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action('plugins_loaded', 'clm_load_textdomain');

function clm_manage_posts_columns($columns)
{
//    print_r($columns);
    //the existing post column remove
    unset($columns['tags']);
    unset($columns['categories']);
    unset($columns['author']);
    $columns['categories']="categories";
    $columns['id']=__('Post ID','clm-manage');
    $columns['thumbnail']=__('Thumbnail','clm-manage');
    $columns['wordcount']=__('Word Count','clm-manage');
    return $columns;
}

add_filter('manage_posts_columns', 'clm_manage_posts_columns');
add_filter('manage_pages_columns', 'clm_manage_posts_columns');

function clm_manage_posts_columns_data($column,$post_id)
{
    if('id'==$column){
        echo $post_id;
    }elseif('thumbnail'==$column){
        $thumbnail = get_the_post_thumbnail($post_id,array(100,100));
        echo $thumbnail;
    }elseif('wordcount'==$column){
        $_post=get_post($post_id);
        $content=$_post->post_content;
        $word_count=str_word_count(strip_tags($content));
        update_post_meta($post_id,'word_count',$word_count);
        $wordn=get_post_meta($post_id,'word_count',true);
        echo $wordn;
    }
}
add_action('manage_posts_custom_column', 'clm_manage_posts_columns_data', 10, 2);
add_action('manage_pages_custom_column', 'clm_manage_posts_columns_data', 10, 2);

function clm_manage_sortable_columns_data($column_name)
{
    $column_name['wordcount']='word_count';
    return $column_name;
}
add_filter('manage_edit-post_sortable_columns', 'clm_manage_sortable_columns_data');

// Handle sorting logic
function clm_sortable_column_orderby($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('word_count' == $orderby) {
        $query->set('meta_key', 'word_count');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'clm_sortable_column_orderby');

function clm_manage_post_filter_columns()
{
    if(isset($_GET['post_type']) && $_GET['post_type']!='post'){
        return;
    }

    $filter_value=isset($_GET['demofilter'])?$_GET['demofilter']:'';
    $values=array(
            "0"=>'select',
           "1"=>'some post',
           "2"=>'some more post',
         )

    ?>
    <select name="demofilter">
        <?php
        foreach ($values as $key => $value) {
        printf("<option value='%s' %s>%s</option>",$key,$key==$filter_value?"selected='selected'":"",$value);
        }
        ?>
    </select>
<?php
}

add_action('restrict_manage_posts', 'clm_manage_post_filter_columns');
function clm_manage_post_filter_columns_data($wp_query)
{
$filter_value=isset($_GET['demofilter'])?$_GET['demofilter']:'';
if('1'==$filter_value){
    $wp_query->set('post__in',array(1,97));
}elseif('2'==$filter_value){
    $wp_query->set('post__not_in',array(97,101));
}

}
add_action('pre_get_posts', 'clm_manage_post_filter_columns_data');


function clm_thumbnail_filter_columns()
{
    if(isset($_GET['post_type']) && $_GET['post_type']!='post'){
        return;
    }

    $filter_value=isset($_GET['thumbnail_filter'])?$_GET['thumbnail_filter']:'';
    $values=array(
        "0"=>'select',
        "1"=>'thumbnail',
        "2"=>'no thumbnail',
    )

    ?>
    <select name="thumbnail_filter">
        <?php
        foreach ($values as $key => $value) {
            printf("<option value='%s' %s>%s</option>",$key,$key==$filter_value?"selected='selected'":"",$value);
        }
        ?>
    </select>
    <?php
}

add_action('restrict_manage_posts', 'clm_thumbnail_filter_columns');
function clm_thumbnail_filter_columns_data($wp_query)
{
    $filter_value=isset($_GET['thumbnail_filter'])?$_GET['thumbnail_filter']:'';
    $wp_query->set('post_per_page',5);
    if('1'==$filter_value){
        $wp_query->set('meta_query',array(
                array(
                    'key'=>'_thumbnail_id',
                    'compare'=>'EXISTS'
                )
        ));
    }elseif('2'==$filter_value){
        $wp_query->set('meta_query',array(
            array(
                'key'=>'_thumbnail_id',
                'compare'=>'NOT EXISTS'
            )
        ));
    }

}
add_action('pre_get_posts', 'clm_thumbnail_filter_columns_data');



