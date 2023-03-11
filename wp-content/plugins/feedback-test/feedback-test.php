<?php 
/**
 * Plugin Name: Feedback Test
 * Plugin URI: http://www.example.com
 * Description: This is a test plugin for feedback
 * Version: 1.0
 * Author: ELMahdi elhjuojy
 * Author URI: http://www.example.com
 * License: GPL2
*/

add_action( 'admin_menu', 'feedback_test_menu' );

function feedback_test_menu() {
    add_menu_page( 'Feedback Test', 'Feedback Test', 'manage_options', 'feedback-test', 'feedback_test_options' );


}


