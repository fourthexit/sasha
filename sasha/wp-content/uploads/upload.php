<?php

/**
 * Plugin Name: Badili Upload
 * Author: Telewa Emmanuel
 * Description: The file upload plugin by Badili Innovations
 * version: 1.0
 */
defined('ABSPATH') or exit();
define('FILE_UPLOAD_PAGE', 'add-new');
define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

class BadiliUpload {

    private function fileUploadTemplate() {
        //coming soon template enabled in settings
        // echo "break"; die;

        add_filter('page_template', function ($page_template) {
            if (is_page(FILE_UPLOAD_PAGE)) {
                $page_template = __DIR__ . '/views/file-upload.php';
            }
            return $page_template;
        });
    }

    public function __construct() {

        require_once 'controllers/MainController.php';
        require_once 'search/Search.php';
        require_once 'utils/Utils.php';
        require_once 'widgets/sweetpotato_topics_widget.php';
        require_once 'widgets/sweetpotato_publications_widget.php';
        require_once 'badili-file-meta.php';
        new MainController();
        new Search();
        new Utils();


        $this->register_my_scripts();
        $this->load_my_scripts();
        $this->config();
        $this->fileUploadTemplate();
    }

    private function register_my_scripts() {
        //css
        wp_register_style('bootstrap-css', plugins_url("assets/dist/lib/bootstrap/dist/css/bootstrap.css", __FILE__));
        wp_register_style('badili-upload-css', plugins_url("assets/dist/css/badili-upload.min.css", __FILE__));
        wp_register_style('badili-home-css', plugins_url("assets/dist/css/badili-home.min.css", __FILE__));

        //for local dev, reload js with each request
        $timestamp = (site_url() == 'http://localhost/sasha') ? time() : ''; //only for dev purposes
        $timestamp = '?t=' . $timestamp;
        wp_register_script('bootstrap-js', plugins_url("assets/dist/lib/bootstrap/dist/js/bootstrap.min.js", __FILE__), array('jquery'), true);
        wp_register_script('angular-js', plugins_url("assets/dist/lib/angular.min.js", __FILE__));
        wp_register_script('ui.bootstrap-tpls', plugins_url("assets/dist/lib/ui-bootstrap-tpls.js", __FILE__), array('jquery', 'angular-js', 'bootstrap-js'));
        wp_register_script('jquery-validate', plugins_url('assets/dist/lib/jquery.validate.min.js', __FILE__), array('jquery'), false, true);
        wp_register_script('moment-js', plugins_url("assets/dist/lib/moment.min.js", __FILE__));
        wp_register_script('my_utils', plugins_url('assets/dist/js/utils.min.js' . $timestamp, __FILE__), array('jquery', 'angular-js', 'jquery-validate', 'bootstrap-js', 'moment-js', 'ui.bootstrap-tpls'), '', true);
        wp_register_script('choose-action-js', plugins_url('assets/dist/js/choose-action.min.js' . $timestamp, __FILE__), array('my_utils'), '', true);
        wp_register_script('badili-pdf-upload-js', plugins_url('assets/dist/js/badili-pdf-upload.min.js' . $timestamp, __FILE__), array('choose-action-js'), '', true);
        wp_register_script('badili-image-upload-js', plugins_url('assets/dist/js/badili-image-upload.min.js' . $timestamp, __FILE__), array('choose-action-js'), '', true);
        wp_register_script('badili-create-post-js', plugins_url('assets/dist/js/badili-create-post.min.js' . $timestamp, __FILE__), array('choose-action-js'), '', true);
        wp_register_script('badili-create-event-js', plugins_url('assets/dist/js/badili-create-event.min.js' . $timestamp, __FILE__), array('choose-action-js'), '', true);

        wp_register_script('badili-create-link-js', plugins_url('assets/dist/js/badili-create-link.min.js' . $timestamp, __FILE__), array('choose-action-js'), '', true);

    }

    private function load_my_scripts() {
        add_action("wp_enqueue_scripts", function () {
            if (is_page(FILE_UPLOAD_PAGE)) {
                //wp_enqueue_style('bootstrap-css');
                wp_enqueue_style('badili-upload-css');
                wp_enqueue_script('choose-action-js');
                wp_enqueue_script('badili-pdf-upload-js');
                wp_enqueue_script('badili-image-upload-js');
                wp_enqueue_script('badili-create-post-js');
                wp_enqueue_script('badili-create-event-js');
                wp_enqueue_script('badili-create-link-js');

                wp_localize_script('my_utils', "USER_DATA", array(
                    'site_url' => site_url(),
                    'users' => autocomplete_users(),
                    'max_upload_size' => wp_max_upload_size()
                ));
            }
            wp_enqueue_style('badili-upload-css');
            wp_enqueue_script('bootstrap-js', plugins_url("assets/dist/lib/bootstrap/dist/js/bootstrap.min.js", __FILE__), array('jquery'), true);
            wp_enqueue_script('matchheight-js', "http://www.sweetpotatoknowledge.org/wp-content/themes/sweetpotatoknowledge/assets/sweepotato/dist/js/jquery.matchHeight-min.js?ver=1.0", array('jquery'), true);
            wp_enqueue_style('badili-home-css');
        }, 9999);
    }

    private function config() {
        add_filter('language_attributes', function( $attr ) {
            if (is_page(FILE_UPLOAD_PAGE)) {
                return "{$attr} ng-app=\"myApp\"";
            } else {
                return $attr;
            }
        });
    }

}

new BadiliUpload();
