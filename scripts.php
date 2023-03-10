<?php

if( !function_exists('cc_plugin_scripts')) {
    function cc_plugin_scripts() {

        //Plugin Frontend CSS
        wp_enqueue_style('cc-css', plugin_dir_url( __FILE__ ). 'admin/css/content-calendar-admin.css');

    }
    add_action('wp_enqueue_scripts', 'cc_plugin_scripts');
}