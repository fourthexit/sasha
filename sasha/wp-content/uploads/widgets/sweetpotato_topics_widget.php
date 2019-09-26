<?php

/**
 * Topic selection widget
 */
class sweetpotato_topics_widget extends WP_Widget {

    function __construct() {
        parent::__construct('sweetpotato_topics_widget', 'Sweetpotato topics', array('description' => 'Topic selection widget'));
    }

    /**
     * Convert the worpress terms object to hierarchical array
     * 
     * @return type
     */
    private function buildTree(array &$elements, $parentId = 0) {
        $branch = array();

        foreach ($elements as &$element) {
            $element = (array) $element;
            if ($element['parent'] == $parentId) {
                $children = self::buildTree($elements, $element['term_id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['term_id']] = $element;
                unset($element);
            }
        }
        return $branch;
    }

    private function get_topic_folders($topic) {
        $args = array(
            'post_type' => 'folders',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'topics',
                    'field' => 'term_id',
                    'terms' => $topic
                ),
            ),
        );
        $folders = new WP_Query($args);
        if (count($folders->posts) > 0) {
            foreach ($folders->posts as $key => $folder) {
                $fid = $folder->ID;
                $topic_folders[$topic][] = array(
                    'id' => $fid,
                    'title' => $folder->post_title
                );
            }
        }

        return json_encode($topic_folders);
    }

// Creating widget front-end
// This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        if (is_page(FILE_UPLOAD_PAGE)) {//this widget is useful only in the file upload page
            if (!empty($title)) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            // This is where you run the code and display the output
            ?>
            <div id='badili_topics'>
                <?php
                $args = array(
                    'orderby' => 'name',
                    'title_li' => '',
                    'taxonomy' => 'topics'
                );
                $cats = get_terms($args);
                $array = (array) $cats;
                $tree = self::buildTree($array, 0);
                ?>
                <ol>
                    <?php
                    foreach ($tree as $tree_item) {
                        $main_topic_folders = self::get_topic_folders($tree_item['term_id']);
                        ?>
                        <div>
                            <input id="<?php echo $tree_item['term_id']; ?>" type="checkbox" name ='topics[]' value="<?php echo $tree_item['term_id']; ?>" data-topic-folders='<?php echo $main_topic_folders ?>' data-topic-title='<?php echo $tree_item['name']; ?>' data-topic-id='<?php echo $tree_item['term_id']; ?>'/>
                            <label for="<?php echo $tree_item['term_id']; ?>">
                                <?php echo $tree_item['name']; ?>
                            </label>

                            <?php
                            if ($tree_item['children']) {
                                ?>
                                <ol>
                                    <?php
                                    //print the children
                                    foreach ($tree_item['children'] as $child) {
                                        $child_topic_folders = self::get_topic_folders($child['term_id']);
                                        ?>
                                        <li>
                                            <input type="checkbox" name ='topics[]' value="<?php echo $child['name']; ?>" data-topic-folders='<?php echo $child_topic_folders ?>' data-topic-title='<?php echo $child['name']; ?>' data-topic-id='<?php echo $child['term_id']; ?>' id="<?php echo $child['term_id']; ?>"/>
                                            <label for="<?php echo $child['term_id']; ?>"><?php echo $child['name']; ?></label>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ol>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </ol>
            </div>
            <?php
        }

        echo $args['after_widget'];
    }

// Widget Backend 
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = 'Sweetpotato topics';
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

}

// Class sweetpotato_topics_widget ends here
// Register and load the widget
add_action('widgets_init', function () {
    register_widget('sweetpotato_topics_widget');
});
