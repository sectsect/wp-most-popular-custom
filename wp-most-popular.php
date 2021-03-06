<?php
/*
    Plugin Name: WP Most Popular Custom
    Plugin URI: https://github.com/sectsect/wp-most-popular-custom
    Description: Flexible plugin to show most popular posts based on views (This Plugin based on WP Most Popular)
    Version: 0.3.1
    Author: SECT INTERACTIVE AGENCY
    Author URI: https://www.ilovesect.com/
    License: GPL2

	Copyright 2011 Matt Geri (email: matt@mattgeri.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (phpversion() > 5) {
    // Setup our path
    define('WMP_PATH', dirname(__FILE__) . '/');

    // Setup activation and deactivation
    register_activation_hook(WP_PLUGIN_DIR . '/wp-most-popular-custom/wp-most-popular.php', 'WMP_system::install');
    register_deactivation_hook(WP_PLUGIN_DIR . '/wp-most-popular-custom/wp-most-popular.php', 'WMP_system::uninstall');

    // Include our helpers
    include_once(WMP_PATH . 'system/helpers.php');

    // Class for installation and uninstallation
    class WMP_system
    {
        public static function actions()
        {
            // Check for token
            if (! wp_verify_nonce(wp_unslash($_POST['token']), 'wmp_token')) {
                die();
            }

            include_once(WMP_PATH . 'system/track.php');
			/*==================================================
				Add code by SECT.
			================================================== */
		//	$track = new WMP_track(intval($_POST['id']));
			$track = new WMP_track(intval(wp_unslash($_POST['id'])), intval(wp_unslash($_POST['paged'])));
			/*==================================================
				Add code by SECT.
			================================================== */
        }

        public static function install()
        {
            include_once(WMP_PATH . 'system/setup.php');
            WMP_setup::install();
        }

        public static function javascript()
        {
            global $wp_query;
            wp_reset_query();
            // wp_print_scripts('jquery');

            $handle = 'jquery';
            $list = 'enqueued';
            if ( wp_script_is( $handle, $list ) ) {
				$token = wp_create_nonce('wmp_token');
	            if (! is_front_page() && (is_page() || is_single())) {
					/*==================================================
						Add code by SECT.
					================================================== */
	            //    echo '<!-- WordPress Most Popular --><script type="text/javascript">/* <![CDATA[ */ jQuery.post("' . admin_url('admin-ajax.php') . '", { action: "wmp_update", id: ' . $wp_query->post->ID . ', token: "' . $token . '" }); /* ]]> */</script><!-- /WordPress Most Popular -->';
					$paged = (get_query_var('page')) ? get_query_var('page') : 1;
					echo '<script>jQuery.post("' . admin_url('admin-ajax.php') . '", { action: "wmp_update", id: ' . $wp_query->post->ID . ', paged: ' . $paged . ', token: "' . $token . '" });</script>';
	            }
            }
        }

        public static function uninstall()
        {
            include_once(WMP_PATH . 'system/setup.php');
            WMP_setup::uninstall();
        }

        public static function widget()
        {
            register_widget('WMP_Widget');
        }
    }

    // Use ajax for tracking popular posts
    add_action('wp_head', 'WMP_system::javascript');
    add_action('wp_ajax_wmp_update', 'WMP_system::actions');
    // Comment out to stop logging stats for admin and logged in users
    add_action('wp_ajax_nopriv_wmp_update', 'WMP_system::actions');

    // Widget
    include_once(WMP_PATH . 'system/widget.php');
    add_action('widgets_init', 'WMP_system::widget');


	add_action('plugins_loaded', 'wpmpc_load_textdomain');
    function wpmpc_load_textdomain() {
    	load_plugin_textdomain('wpmpc', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }
    /*==================================================
        Add option page
    ================================================== */
    add_action('admin_menu', 'ranking_menu');
    function ranking_menu()
    {
		$page_hook_suffix = add_options_page('WP Most Popular Custom', 'WP Most Popular Custom', 'manage_options', 'ranking_menu', 'ranking_options_page');
    //    $page_hook_suffix = add_menu_page('Ranking', 'Ranking', 'manage_options', 'ranking_menu', 'ranking_options_page');
	    add_action('admin_print_styles-' . $page_hook_suffix, 'ranking_admin_styles');
	    add_action('admin_print_scripts-' . $page_hook_suffix, 'ranking_admin_scripts');    // @ https://wpdocs.osdn.jp/%E9%96%A2%E6%95%B0%E3%83%AA%E3%83%95%E3%82%A1%E3%83%AC%E3%83%B3%E3%82%B9/wp_enqueue_script#.E3.83.97.E3.83.A9.E3.82.B0.E3.82.A4.E3.83.B3.E7.AE.A1.E7.90.86.E7.94.BB.E9.9D.A2.E3.81.AE.E3.81.BF.E3.81.A7.E3.82.B9.E3.82.AF.E3.83.AA.E3.83.97.E3.83.88.E3.82.92.E3.83.AA.E3.83.B3.E3.82.AF.E3.81.99.E3.82.8B

        add_action('admin_init', 'register_ranking_settings');
    }
	function ranking_admin_styles()
	{
	    wp_enqueue_style('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css', array());
	}
	function ranking_admin_scripts()
	{
	    wp_enqueue_script('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js', array('jquery'));
	    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'admin/js/script.js', array('select2'));
	}
    function register_ranking_settings()
    {
        register_setting('ranking-settings-group', 'wmp_range');
        register_setting('ranking-settings-group', 'wmp_loginuser');
		register_setting('ranking-settings-group', 'wmp_split_single_page');
    }
    function ranking_options_page()
    {
        require_once(plugin_dir_path(__FILE__) . "admin/index.php");
    }

    /*==================================================
        Periodic delete all processing (every 2weeks)
    ================================================== */
    // // Add event
    // add_action('my_hourly_event', 'my_hourly_action');
    // function my_hourly_action() {
    // 	// Truncate the DB table
    // 	global $wpdb;
    // 	$results = $wpdb->get_results("
    // 		TRUNCATE TABLE wp_most_popular
    // 	");
    // 	return $results;
    // }
    // // Add event time
    // add_filter('cron_schedules', 'my_interval');
    // function my_interval($schedules) {
    // //	date_default_timezone_set('Asia/Tokyo');
    // 	$schedules['biweekly'] = array(
    // 		'interval'	=> 7 * 24 * 60 * 60 * 2,		//	2 weeks
    // 	//	'interval'	=> 180,							//	For Test
    // 		'display'	=> 'Biweekly'
    // 	);
    // 	return $schedules;
    // }
    // function my_activation() {
    // 	if(!wp_next_scheduled('my_hourly_event')){
    // 		wp_schedule_event(time(), 'biweekly', 'my_hourly_event');
    // 	}
    // }
    // add_action('wp', 'my_activation');
    // // Deactivation event
    // register_deactivation_hook(__FILE__, 'my_deactivation');
    // function my_deactivation() {
    // 	wp_clear_scheduled_hook('my_hourly_event');
    // }
}
