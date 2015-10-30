<?php

/*
Plugin Name: Object Store Backups
Plugin URI: https://github.com/crypticsoft/objectstore-backups
Description: Backup your WordPress site to any Object Store service.
Version: 3.5.2
Author: Todd Wilson
Author URI: http://icreativepro.com/
Network: false
Text Domain: objectstore-backups
Domain Path: /i18n

Copyright 2015 Todd Wilson (email: twilson@liquidweb.com)

    This objectstore-backups plugin is a fork of DreamObjects, a plugin for WordPress.

    objectstore-backups is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    objectstore-backups is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 * @package dh-do-backups
 */
 
function dreamobjects_core_incompatibile( $msg ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
	deactivate_plugins( __FILE__ );
    wp_die( $msg );
}

if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {

	require_once ABSPATH . '/wp-admin/includes/plugin.php';
		
	if ( version_compare( PHP_VERSION, '5.3.3', '<' ) ) {
		dreamobjects_core_incompatibile( __( 'The official Amazon Web Services SDK, which Object Store Backups relies on, requires PHP 5.3 or higher. The plugin has now disabled itself.', 'dreamobjects' ) );
	}
	elseif ( !function_exists( 'curl_version' ) 
		|| !( $curl = curl_version() ) || empty( $curl['version'] ) || empty( $curl['features'] )
		|| version_compare( $curl['version'], '7.16.2', '<' ) )
	{
		dreamobjects_core_incompatibile( __( 'The official Amazon Web Services SDK, which Object Store Backups relies on, requires cURL 7.16.2+. The plugin has now disabled itself.', 'dreamobjects' ) );
	}
	elseif ( !( $curl['features'] & CURL_VERSION_SSL ) ) {
		dreamobjects_core_incompatibile( __( 'The official Amazon Web Services SDK, which Object Store Backups relies on, requires that cURL is compiled with OpenSSL. The plugin has now disabled itself.', 'dreamobjects' ) );
	}
	elseif ( !( $curl['features'] & CURL_VERSION_LIBZ ) ) {
		dreamobjects_core_incompatibile( __( 'The official Amazon Web Services SDK, which Object Store Backups relies on, requires that cURL is compiled with zlib. The plugin has now disabled itself.', 'dreamobjects' ) );
	} elseif ( is_multisite() ) {
		dreamobjects_core_incompatibile( __( 'Sorry, but Object Store Backups is not currently compatible with WordPress Multisite, and should not be used. The plugin has now disabled itself.', 'dreamobjects' ) );
	}
}
 
require_once 'lib/defines.php';
require_once 'lib/dhdo.php';
require_once 'lib/messages.php';
require_once 'lib/settings.php';

// WP-CLI
if ( defined('WP_CLI') && WP_CLI ) {
	include( 'lib/wp-cli.php' );
}
