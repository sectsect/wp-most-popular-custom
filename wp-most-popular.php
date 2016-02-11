<?php
/*
    Plugin Name: WP Most Popular Custom
    Plugin URI: http://www.ilovesect.com/
    Description: Flexible plugin to show most popular posts based on views
    Version: 0.1.1
    Author: SECT
    Author URI: http://www.ilovesect.com/
    License: GPL2

    Copyright 2015 SECT (email: info@sectwebstudio.com)

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
            if (! wp_verify_nonce($_POST['token'], 'wmp_token')) {
                die();
            }

            include_once(WMP_PATH . 'system/track.php');
            $track = new WMP_track(intval($_POST['id']));
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
            wp_print_scripts('jquery');
            $token = wp_create_nonce('wmp_token');
            if (! is_front_page() && (is_page() || is_single())) {
                echo '<!-- WordPress Most Popular --><script type="text/javascript">/* <![CDATA[ */ jQuery.post("' . admin_url('admin-ajax.php') . '", { action: "wmp_update", id: ' . $wp_query->post->ID . ', token: "' . $token . '" }); /* ]]> */</script><!-- /WordPress Most Popular -->';
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


    /*==================================================
        オプションページ追加
    ================================================== */
    add_action('admin_menu', 'ranking_menu');
    function ranking_menu()
    {
        add_menu_page('Ranking', 'Ranking', 5, 'ranking_menu', 'ranking_options_page');
        add_action('admin_init', 'register_ranking_settings');
    }
    function register_ranking_settings()
    {
        register_setting('ranking-settings-group', 'wmp_range');
        register_setting('ranking-settings-group', 'wmp_loginuser');
    }
    function ranking_options_page()
    {
        require_once(plugin_dir_path(__FILE__) . "admin/index.php");
    }

    /*==================================================
        定期処理（隔週(2weeks)でランキング・アクセス数のリセット）
    ================================================== */
    // // イベントの作成
    // add_action('my_hourly_event', 'my_hourly_action');
    // function my_hourly_action() {
    // 	// ランキングのDBテーブルを空にする
    // 	global $wpdb;
    // 	$results = $wpdb->get_results("
    // 		TRUNCATE TABLE wp_most_popular
    // 	");
    // 	return $results;
    // }
    // // イベントの時間追加
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
    // 	// イベントが未登録なら登録する
    // 	if(!wp_next_scheduled('my_hourly_event')){
    // 		wp_schedule_event(time(), 'biweekly', 'my_hourly_event');
    // 	}
    // }
    // add_action('wp', 'my_activation');
    // // イベント排除
    // register_deactivation_hook(__FILE__, 'my_deactivation');
    // function my_deactivation() {
    // 	wp_clear_scheduled_hook('my_hourly_event');
    // }
}
