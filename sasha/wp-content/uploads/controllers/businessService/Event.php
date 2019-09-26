<?php

class Event {

	public function create_my_event(){
	    $post_title = filter_input(INPUT_POST, "post_title");
        $post_content = filter_input(INPUT_POST, "post_content");
        $topics = filter_input(INPUT_POST, "topics");
        $topics_tax = filter_input(INPUT_POST, "topics_tax");        

        //special fields
        $topics = json_decode($topics, false);
        $topics_tax = json_decode($topics_tax, false);

        $event_start_date = filter_input(INPUT_POST, "event_start_date");
        $event_start_time = filter_input(INPUT_POST, "event_start_time");
        $event_end_date = filter_input(INPUT_POST, "event_end_date");
        $event_end_time = filter_input(INPUT_POST, "event_end_time");

        $event_start=strtotime($event_start_date." ".$event_start_time);
        $event_end=strtotime($event_end_date." ".$event_end_time);

        $event_start=date("Y-m-d H:i:s", $event_start);
        $event_end=date("Y-m-d H:i:s", $event_end);

        $all_day_event = filter_input(INPUT_POST, "all_day_event");
        $event_url = filter_input(INPUT_POST, "event_url");
        $event_cost = filter_input(INPUT_POST, "event_cost");
        $currency_symbol = filter_input(INPUT_POST, "currency_symbol");
        $venue_address = filter_input(INPUT_POST, "venue_address");
        $venue_city = filter_input(INPUT_POST, "venue_city");
        $venue_country = filter_input(INPUT_POST, "venue_country");        

		// create the wp_post as an event
        // insert the post and set the category
        $post_id = wp_insert_post(array(
            'post_type' => 'tribe_events',
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_name' => sanitize_title($post_title),
            'post_status' => 'publish',
            'comment_status' => 'open', // if you prefer
            'ping_status' => 'open', // if you prefer
            'post_author' => get_current_user_id()
                ), TRUE);

		if ($post_id) 
        {
            //the link            
            add_post_meta($post_id, '_EventStartDate', $event_start);
            add_post_meta($post_id, '_EventEndDate', $event_end);
            add_post_meta($post_id, '_EventAllDay', $all_day_event);
            add_post_meta($post_id, '_EventURL', $event_url);
            add_post_meta($post_id, '_EventCost', $event_cost);
            add_post_meta($post_id, '_EventCurrencySymbol', $currency_symbol);
            add_post_meta($post_id, '_VenueCountry', $venue_country);
            add_post_meta($post_id, '_VenueAddress', $venue_address);
            add_post_meta($post_id, '_VenueCity', $venue_city);

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
?>	