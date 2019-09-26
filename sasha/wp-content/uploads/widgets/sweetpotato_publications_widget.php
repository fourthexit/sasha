<?php

/**
 * Topic selection widget
 */
class sweetpotato_publications_widget extends WP_Widget {

    function __construct() {
        parent::__construct('sweetpotato_publications_widget', 'Sweetpotato publications', array('description' => 'Publications selection widget'));
    }

// Creating widget front-end
// This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        $limit = $instance['items_count'];
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        // This is where you run the code and display the output
        ?>
        <div id='badili_publications'>
            <?php
            $sql = "select q1.post_id from  (select post_id,meta_key, meta_value  from wp_postmeta where meta_key = 'file_pub_year') as q1 right join  (select post_id,meta_key, meta_value from wp_postmeta where meta_key = 'file_pub_month') as q2 on q1.post_id = q2.post_id inner join wp_posts on q1.post_id = wp_posts.ID  where wp_posts.post_status ='publish' order by q1.meta_value desc,q2.meta_value desc limit $limit";

            $sql = "select q3.id as post_id, q1.meta_value as pub_year, q2.meta_value as pub_month, q4.meta_value as pub_day from  
                        wp_posts as q3
                        inner join (select post_id,meta_key, meta_value  from wp_postmeta where meta_key = 'file_pub_year') as q1 on q1.post_id = q3.id 
                        left join (select post_id,meta_key, meta_value from wp_postmeta where meta_key = 'file_pub_month') as q2 on q2.post_id = q3.id 
                        left join (select post_id,meta_key, meta_value from wp_postmeta where meta_key = 'file_pub_day') as q4 on q4.post_id = q3.id 
                        where q3.post_status ='publish' and q3.post_type = 'files' 
                        order by q1.meta_value desc,q2.meta_value desc";

            /**$sql = "select q1.post_id "
                . "from (select post_id,meta_key, meta_value from wp_postmeta where meta_key = 'file_pub_year') as q1 "
                . "left join (select post_id,meta_key, meta_value from wp_postmeta where meta_key = 'file_pub_month') as q2 on q1.post_id = q2.post_id "
                . "left join (select post_id,meta_key, meta_value from wp_postmeta where meta_key = 'file_pub_day') as q3 on q1.post_id = q3.post_id "
                . "inner join wp_posts on q1.post_id = wp_posts.ID "
                . "where wp_posts.post_status = 'publish' and q2.meta_value is not NULL and q3.meta_value is not NULL "
                . "order by q1.meta_value desc, q2.meta_value desc, q3.meta_value desc limit $limit";
            */

            global $wpdb;
            $latest_publications = $wpdb->get_results($sql, ARRAY_A);
            $excerpt_length=40; 
            ?>
            <ul>
                <?php
                $today = new DateTime();
                for($i=0, $z=0; $z < $limit; $i++) {
                    $post = $latest_publications[$i];
                    // $latest_publications as $key => $post;
                    //Date of Publication:
                    $year = $post['pub_year'];
                    $month = $post['pub_month'];
                    $day = $post['pub_day'];

                    $final_date = $year;
                    $final_date .= (strlen($month) > 1 ) ? '-' . $month : '-01';
                    $final_date .= (strlen($day) > 1) ? '-' . $day : '-01';
                    //print (new DateTime($final_date))->format('M Y');
                    $pub_date = new DateTime($final_date);
                    if($pub_date > $today){
                        continue;
                    }
                    else{
                        $z++;
                    }
                    
                    $file = get_post($post['post_id']);
                    ?>
                    <li class="publications-widget-list">
                    
                        <?php
                        $default_image=false;
                        /*$url_path = get_post_meta($file->ID, 'file_link', true);
                        $local_path = 'assets/uploads/thumb/thumb_' . $file->ID . '.png';
                        $current_name = MY_PLUGIN_PATH . $local_path;
                        if (!file_exists($current_name)) {//create it only once coz its expensive
                           
                            try {
                                    $im = new Imagick($url_path);
                                    $im->setimageresolution(640, 480);
                                    $im->setIteratorIndex(0);
                                    $im->setImageFormat('jpg');
                                    //$im->resizeImage(100,100,1,0);                                
                                    $im->writeImage($current_name);
                                } 
                            catch (ImagickException $e) 
                                {
                                    //var_dump($e);
                                    $default_image=true;
                                }  
                        }*/
                        ?>
                        
                        <div class="publications-details-wrapper">
                            <?php $title=ucfirst(strtolower($file->post_title));?>
                            <div class="append-elipses publication-list-title">
                                <a href="<?php echo get_permalink($file->ID); ?>" title="<?=$title?>" >
                                    <span><?php echo $title //mb_strimwidth($title, 0, intval($excerpt_length), '...'); ?></span>
                                </a>
                            </div>
                            <div class="publication-author">
                                <?php
                                $authors = get_post_meta($file->ID, 'file_author');
                                if (is_array($authors) && count($authors) > 0) {
                                    ?>
                                    <span class="descriptionlabel">
                                    <i class="fa fa-user"></i> 
                                            <?php
                                            $authors_link = [];
                                            for ($j = 0; ($j < count($authors) && $j < 2); $j++) {
                                                $authors_link[] = author_link($authors[$j]);
                                            }
                                            print implode(", ", $authors_link);
                                            print count($authors) > 2 ? ' et al.' : '';
                                            ?>                                        
                                    </span>
                                <?php } ?>
                            </div>

                            <div class="publication-author">
                                <span class="descriptionlabel">
                                <i class="fa fa-clock-o"></i> 
                                    <?php print $pub_date->format('F d, Y'); ?>
                                </span>
                            </div>

                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>        

        </div>
        <?php
        echo $args['after_widget'];
    }

// Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'Sweetpotato Publications';
        }
        if (isset($instance['items_count'])) {
            $items_count = $instance['items_count'];
        } else {
            $items_count = 5;
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('items_count'); ?>"><?php _e('Number of Listings:', 'realty_widget'); ?></label>
            <select id="<?php echo $this->get_field_id('items_count'); ?>"  name="<?php echo $this->get_field_name('items_count'); ?>">
                <?php for ($x = 1; $x <= 10; $x++): ?>
                    <option <?php echo $x == $items_count ? 'selected="selected"' : ''; ?> value="<?php echo $x; ?>"><?php echo $x; ?></option>
                <?php endfor; ?>
            </select>
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['items_count'] = (!empty($new_instance['items_count']) ) ? strip_tags($new_instance['items_count']) : '';

        return $instance;
    }

}
// Class sweetpotato_publications_widget ends here
// Register and load the widget
add_action('widgets_init', function () {
    register_widget('sweetpotato_publications_widget');
});