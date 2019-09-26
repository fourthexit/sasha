<?php

/**
 * Add all post types that are public to the global search
 *
 * @author Telewa Emmanuel
 */
class Search {

    public function __construct() {
        add_action('badili_pre_get_posts', function ($query) {

            // Check to verify it's search page
            if (is_search()) {
                // Get post types
                $post_types = get_post_types(array('public' => true, 'exclude_from_search' => false), 'objects');
                $searchable_types = array();
                // Add available post types
                if ($post_types) {
                    foreach ($post_types as $type) {
                        $searchable_types[] = $type->name;
                    }
                }
                $searchable_types[]="nav_menu_item";

                $query->set('post_type', $searchable_types);
            }
            return $query;
        },9999);
    }

}

new Search();
