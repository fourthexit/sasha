<?php

/**
 * Plugin Name: Badili Login
 * Description: The badili login page
 * Version: 1.0
 * Author: Badili Innovations
 */
defined('ABSPATH') or exit();

class BadiliLogin {

    public function __construct() {
        //add reset password url to the login form
        add_filter('login_form_middle', function ( $content, $args ) {
            $content .= '<ul>';
            $content .= '<li><a href="' . site_url() . '/password-reset">Lost Password?</a></li>';
            // $content .= '<li><a href="' . site_url() . '/user-register">Register</a></li>';
            $content .= '</ul>';
            return $content;
        }, 9999, 2);


        //remove the remember me option
        add_filter('wpum_login_shortcode_args', function ($args) {
            $args['remember'] = false;
            return $args;
        });

        //on logout redirect them to home
        add_action('wp_logout', function () {
            wp_safe_redirect(home_url());
            exit;
        });

        //sasha.badili.co.ke/login/?redirect_to=http%3A%2F%2Flocalhost%2Fsasha%2Ffile-upload%2F&reauth=1
    }

}

new BadiliLogin();

