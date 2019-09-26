<?php

/**
 * This is just the main controller
 */
class MainController {

    public function __construct() {
        require_once 'businessService/Document.php';
        require_once 'businessService/Post.php';
        require_once 'businessService/Link.php';
        require_once 'businessService/Event.php';


        add_action('wp_ajax_get_document_meta', array("Document", 'get_document_meta'));
        add_action('wp_ajax_upload_document', array("Document", 'upload_document'));

        add_action('wp_ajax_create_my_post', array("Post", 'create_my_post'));
        add_action('wp_ajax_create_my_link', array("Link", 'create_my_link'));
        add_action('wp_ajax_create_my_event', array("Event", 'create_my_event'));
    }

}

?>