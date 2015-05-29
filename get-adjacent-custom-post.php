<?php
/*
   Plugin Name: Get Adjacent Custom Post
   Plugin URI: http://wordpress.org/extend/plugins/get-adjacent-custom-post/
   Version: 0.1
   Author: Darcy Christ
   Description: This supports only the custom post types you identify and does not * look at categories anymore. This allows you to go from one custom post type * to
   Text Domain: get-adjacent-custom-post
   License: GPLv3
  */


$GetAdjacentCustomPost_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function GetAdjacentCustomPost_noticePhpVersionWrong() {
    global $GetAdjacentCustomPost_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Get Adjacent Custom Post" requires a newer version of PHP to be running.',  'get-adjacent-custom-post').
            '<br/>' . __('Minimal version of PHP required: ', 'get-adjacent-custom-post') . '<strong>' . $GetAdjacentCustomPost_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'get-adjacent-custom-post') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function GetAdjacentCustomPost_PhpVersionCheck() {
    global $GetAdjacentCustomPost_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $GetAdjacentCustomPost_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'GetAdjacentCustomPost_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function GetAdjacentCustomPost_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('get-adjacent-custom-post', false, $pluginDir . '/languages/');
}

/**
 * Replacement for get_adjacent_post()
 *
 * This supports only the custom post types you identify and does not
 * look at categories anymore. This allows you to go from one custom post type
 * to another which was not possible with the default get_adjacent_post().
 * Orig: wp-includes/link-template.php 
 * 
 * @param string $direction: Can be either 'prev' or 'next'
 * @param multi $post_types: Can be a string or an array of strings
 */
function get_adjacent_custompost($direction = 'prev', $post_types = 'post') {
    global $post, $wpdb;

    if(empty($post)) return NULL;
    if(!$post_types) return NULL;

    if(is_array($post_types)){
        $txt = '';
        for($i = 0; $i <= count($post_types) - 1; $i++){
            $txt .= "'".$post_types[$i]."'";
            if($i != count($post_types) - 1) $txt .= ', ';
        }
        $post_types = $txt;
    }

    $current_post_date = $post->post_date;

    $join = '';
    $in_same_cat = FALSE;
    $excluded_categories = '';
    $adjacent = $direction == 'prev' ? 'previous' : 'next';
    $op = $direction == 'prev' ? '<' : '>';
    $order = $direction == 'prev' ? 'DESC' : 'ASC';

    $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
    $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_title $op %s AND p.post_type IN({$post_types}) AND p.post_status = 'publish'", $post->post_title), $in_same_cat, $excluded_categories );
    $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_title $order LIMIT 1" );

    $query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
    $query_key = 'adjacent_post_' . md5($query);
    $result = wp_cache_get($query_key, 'counts');
    if ( false !== $result )
        return $result;

    $result = $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $sort");
  //print "SELECT p.* FROM $wpdb->posts AS p $join $where $sort<br/>";
    if ( null === $result )
        $result = '';

    wp_cache_set($query_key, $result, 'counts');
    return $result;
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// First initialize i18n
GetAdjacentCustomPost_i18n_init();


// Next, run the version check.
// If it is successful, continue with initialization for this plugin
if (GetAdjacentCustomPost_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('get-adjacent-custom-post_init.php');
    GetAdjacentCustomPost_init(__FILE__);
}
