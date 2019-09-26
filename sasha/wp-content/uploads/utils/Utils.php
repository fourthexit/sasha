<?php

/**
 * These are other utilities not very related to file upload
 *
 * @author Telewa
 */
class Utils {

    /**
     * When creating a post, each user(non-admins) can only see their stuff in media/gallery
     */
    private function restrict_media_files_access_to_user() {
        add_filter('ajax_query_attachments_args', function ($query) {
            $user_id = get_current_user_id();
            if ($user_id) {

                if (!current_user_can('manage_options')) {
                    $query['author'] = $user_id;
                }
            }
            return $query;
        });
    }

    public function __construct() {
        $this->restrict_media_files_access_to_user();

        //hide admin bar from non-admins
        add_action('after_setup_theme', 'remove_admin_bar');
        function remove_admin_bar() {
            if (!current_user_can('administrator') && !is_admin()) {
                //show_admin_bar(false);
                add_filter('show_admin_bar', '__return_false');
            }
        }

    }

}
