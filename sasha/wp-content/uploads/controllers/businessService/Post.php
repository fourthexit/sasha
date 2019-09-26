<?php

/**
 * This is the Post Business service
 */
class Post {

    /**
     * Do the actual post creation
     */
    public function create_my_post() {

        $post_title = filter_input(INPUT_POST, "post_title");
        $post_content = filter_input(INPUT_POST, "post_content");
        $topics = filter_input(INPUT_POST, "topics");
        $topics_tax = filter_input(INPUT_POST, "topics_tax");

        //special fields
        $topics = json_decode($topics, false);
        $topics_tax = json_decode($topics_tax, false);

        //create the wp_post
        // insert the post and set the category
        $post_id = wp_insert_post(array(
            'post_type' => 'post',
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => 'auto-draft',
            'comment_status' => 'open', // if you prefer
            'ping_status' => 'open', // if you prefer
            'post_author' => get_current_user_id()
                ), TRUE);

        if ($post_id) {

            //the folders
            foreach ($topics as $topic) {
                if (strlen($topic) > 0) {
                    add_post_meta($post_id, 'folder_parent', intval($topic));
                }
            }

            if (isset($topics_tax)) {
                for ($i = 0; $i < count($topics_tax); $i++) {
                    $topics_tax[$i] = intval($topics_tax[$i]); //they must be integers
                }
                $resp = wp_set_object_terms($post_id, $topics_tax, 'topics', false);
            }

            $resp = array(
                "status" => "success",
                "url" => get_permalink($post_id)
            );
        } else {
            $resp = array(
                "status" => "failure",
            );
        }
        echo json_encode($resp);

        exit(0);
    }

}
