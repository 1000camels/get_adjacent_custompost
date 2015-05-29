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

/*
    "WordPress Plugin Template" Copyright (C) 2015 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
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
