<?php

/**
 * Common functions for the Document and Image classes
 *
 * @author emmanuelt
 */
abstract class Common {

    /**
     * Attach this file to this post
     * 
     * @param type $file    The file path
     * @param type $post_id The post ID 
     * @param type $desc
     * @return type
     */
    static function attach_document_to_post($file, $post_id, $desc = null) {
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        if (!empty($file)) {
            // Download file to temp location
            $tmp = download_url($file);
            // Set variables for storage
            // fix file filename for query strings
            preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|pdf)/', $file, $matches);
            $file_array['name'] = basename($matches[0]);
            $file_array['tmp_name'] = $tmp;
            // If error storing temporarily, unlink
            if (is_wp_error($tmp)) {

                file_put_contents('/tmp/test1.php', $file_array['tmp_name']);
                @unlink($file_array['tmp_name']);
                $file_array['tmp_name'] = '';
            }
            // do the validation and storage stuff
            $id = media_handle_sideload($file_array, $post_id, $desc);
            // If error storing permanently, unlink
            if (is_wp_error($id)) {
                @unlink($file_array['tmp_name']);
            }
            //update_post_meta($post_id, '_thumbnail_id', $id);//this will seta thumbnail. we do not need this

            return $id;
        }
    }

    /**
     * Create a file to disk from this base64 string
     * 
     * 
     * @param type $base64_string
     * @param type $output_file
     * @return type
     */
    static function base64_to_file($base64_string, $filename) {
        $output_file = MY_PLUGIN_PATH . 'assets/uploads/' . $filename;

        $my_data = explode(',', $base64_string);
        $image_data = base64_decode($my_data[1]);

        $ifp = fopen($output_file, "wb");
        fwrite($ifp, $image_data);

        fclose($ifp);

        return $output_file;
    }

    /**
     * @deprecated since version 1
     * We are not using filebase anymore
     * 
     * @global type $wpdb
     * @param type $filename
     * @param type $author
     * @param type $date_published
     * @param type $keywords
     * @param type $abstract
     * @return type
     */
    static function update_file_base_db($filename, $author, $date_published, $keywords, $abstract) {

        global $wpdb;

        $sql = "UPDATE  `wp_wpfb_files` SET  `file_author` =  '%s',`file_tags` = '%s',`file_date`='%s',`file_description`='%s'   WHERE  `wp_wpfb_files`.`file_name` ='%s'";

        $stmt = $wpdb->prepare($sql, $author, $keywords, $date_published, $abstract, $filename);
        $resp = $wpdb->query($stmt);

        //file_put_contents("/tmp/test.sql", $stmt);
        return $resp;
    }

}
