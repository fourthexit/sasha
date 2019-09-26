<?php

/**
 * This is the Image Business service
 */

class Image {

    /**
     * This should give some more info about the image
     */
    public function get_image_meta($path) {

        try {
            $meta = exif_read_data($path);
        } catch (Exception $ex) {
            $meta = array();
        }

        return $meta;
    }

}
