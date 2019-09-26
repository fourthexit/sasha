<?php
/**
 * This is the Link Business service
 */
class Link {
    /**
     * Do the actual post creation
     */
    public function create_my_link() {

        $post_title = filter_input(INPUT_POST, "post_title");
        $link_url = filter_input(INPUT_POST, "link_url");
        $topics = filter_input(INPUT_POST, "topics");
        $topics_tax = filter_input(INPUT_POST, "topics_tax");

        //special fields
        $topics = json_decode($topics, false);
        $topics_tax = json_decode($topics_tax, false);

        

        //create the wp_post
        // insert the post and set the category
        $post_id = wp_insert_post(array(
            'post_type' => 'external-links',
            'post_title' => $post_title,
            //'post_content' => $post_content,
            'post_status' => 'publish',
            'comment_status' => 'open', // if you prefer
            'ping_status' => 'open', // if you prefer
            'post_author' => get_current_user_id()
                ), TRUE);

        if ($post_id) 
        {
            //the link
            add_post_meta($post_id, 'link_url', $link_url);
            add_post_meta($post_id, 'link_author', get_current_user_id());

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

            try {
                $date_of_publication = new DateTime();
                update_post_meta($post_id, 'file_pub_year', $date_of_publication->format('Y'));
                update_post_meta($post_id, 'file_pub_month', $date_of_publication->format('m'));
                update_post_meta($post_id, 'file_pub_day', $date_of_publication->format('d'));
            } catch (Exception $ex) {
                //do nothing
                //Failed to parse time string (Invalid date) at position 0 (I):
            }


            
            $resp = array(
                "status" => "success",
                "url" => get_permalink($post_id)
            );
        } else 
        {
            $resp = array(
                "status" => "failure",
            );
        }
        echo json_encode($resp);

        exit(0);
    }

}
