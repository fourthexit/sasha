<?php

/**
 * This is the Document Business service
 */
class Document {

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
            preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG|pdf|doc|docx|xls|xlsx)/', $file, $matches);
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
     * This should give some more info about the pdf
     */
    public function get_document_meta() {
        try{
            $filedata = filter_input(INPUT_POST, "filedata");
            $filename = filter_input(INPUT_POST, "filename");
            $filetype = filter_input(INPUT_POST, "filetype");

            $path = self::base64_to_file($filedata, $filename); //for the server

            if (preg_match("/^application\/pdf$/", $filetype)) {
                require_once 'filetypes/PDF.php';
                $pdf = new PDF();
                $meta = $pdf->get_pdf_meta($path);
            } else if (preg_match("/^image\/.+/", $filetype)) {
                require_once 'filetypes/Image.php';
                $image = new Image();
                $meta = $image->get_image_meta($path);
            } else {
                //we do not know how to extract metadata from those
                $meta = [];
            }

            $new_meta = array();
            foreach($meta as $key => $value){
                $new_meta[$key] = utf8_encode($value);
            }

            echo json_encode(array(
                "meta" => $new_meta,
                "filename" => $filename,
                "filetype" => $filetype,
                "status" => "success"
            ));

            //delete it afterwards to save space
            unlink($path);
            exit(0);
        }
        catch (Exception $ex) {
            file_put_contents('errors.log', PHP_EOL . print_r($ex->getMessage(), true), FILE_APPEND);
            echo json_encode(array(
                "message" => print_r($ex->getMessage(), true),
                "status" => "error"
            ));
        }
    }

    /**
     * This is the code that does the final file upload
     */
    public function upload_document() {
        $title = filter_input(INPUT_POST, "title");
        $abstract = filter_input(INPUT_POST, "abstract");
        $credits = filter_input(INPUT_POST, "credits");
        $subject = filter_input(INPUT_POST, "subject");
        $authors = filter_input(INPUT_POST, "authors");
        $contributors = filter_input(INPUT_POST, "contributors");
        $topics = filter_input(INPUT_POST, "topics");
        $topics_tax = filter_input(INPUT_POST, "topics_tax");
        $publisher = filter_input(INPUT_POST, "publisher");
        $rights = filter_input(INPUT_POST, "rights");


        $creation_date = filter_input(INPUT_POST, "creation_date"); //date of publication
        $keywords = filter_input(INPUT_POST, "keywords");

        $language = filter_input(INPUT_POST, "language");

        $filedata = filter_input(INPUT_POST, "filedata");
        $filename = filter_input(INPUT_POST, "filename");
        $filetype = filter_input(INPUT_POST, "filetype");

        $file_biblio = filter_input(INPUT_POST, "file_biblio"); //citation
        $file_identifier = filter_input(INPUT_POST, "file_identifier"); //citation
        $filesize = filter_input(INPUT_POST, "filesize");
        $number_of_pages = filter_input(INPUT_POST, "number_of_pages");

        //special fields
        $authors = json_decode($authors, false);
        $contributors = json_decode($contributors, false);
        $topics = json_decode($topics, false);
        $topics_tax = json_decode($topics_tax, false);

        //create the wordpress post type file
        $post_id = wp_insert_post(array(
            'post_type' => 'files',
            'post_title' => $title,
            'post_content' => $abstract,
            //'post_status' => 'pending',
            'post_status' => 'publish',
            'comment_status' => 'closed', // if you prefer
            'ping_status' => 'closed', // if you prefer
            'post_author' => get_current_user_id()
                ), TRUE);

        $path = self::base64_to_file($filedata, $filename); //for the server
        //do the formal upload of the document to wordpress
        if ($path != null && $path != "") {

            $url = plugins_url() . '/badili_upload/assets/uploads/' . $filename;
            $id = self::attach_document_to_post($url, $post_id);

            $document_url = wp_get_attachment_url($id);

            update_post_meta($post_id, 'file_link', $document_url);
        }

        update_post_meta($post_id, 'file_link', $document_url);
        update_post_meta($post_id, 'credits', $credits); //for the images/video
        update_post_meta($post_id, 'file_biblio', $file_biblio);
        update_post_meta($post_id, 'content_visibility', 'public');
        update_post_meta($post_id, 'file_identifier', $file_identifier);

        //what if we have no date??
        if ($creation_date && $creation_date != 'Invalid date') {//maybe the user never put in a date or something
            try {
                $date_of_publication = new DateTime($creation_date);
                update_post_meta($post_id, 'file_pub_year', $date_of_publication->format('Y'));
                update_post_meta($post_id, 'file_pub_month', $date_of_publication->format('m'));
                update_post_meta($post_id, 'file_pub_day', $date_of_publication->format('d'));
            } catch (Exception $ex) {
                //do nothing
                //Failed to parse time string (Invalid date) at position 0 (I):
            }
        }


        //reference: /var/www/html/sasha/wp-content/themes/sahifa/plugins/sasha_manager/admin/post_types/folders/files/files.php 380
        foreach ($authors as $author) {
            if (strlen($author) > 0) {
                if (strpos($author, '[')) {
                    $author = substr($author, strpos($author, '[') + 1, -1);
                }
                add_post_meta($post_id, 'file_author', $author); //put here all the authors
            }
        }
        foreach ($contributors as $contributor) {
            if (strlen($author) > 0) {
                if (strpos($contributor, '[')) {
                    $contributor = substr($contributor, strpos($contributor, '[') + 1, -1);
                }
                add_post_meta($post_id, 'file_contributor', $contributor);
            }
        }

        //the folders
        foreach ($topics as $topic) {
            if (strlen($topic) > 0) {
                add_post_meta($post_id, 'folder_parent', intval($topic));
            }
        }

        if (isset($topics_tax)) {
            for($i=0;$i<count($topics_tax);$i++) {
                $topics_tax[$i] = intval($topics_tax[$i]);//they must be integers
            }
            $resp = wp_set_object_terms($post_id, $topics_tax, 'topics', false);
        }


        update_post_meta($post_id, 'files_subject', $subject);
        update_post_meta($post_id, 'files_rights', $rights);
        wp_set_post_tags($post_id, $keywords, false); //keywords

        update_post_meta($post_id, 'files_publisher', $publisher);
        update_post_meta($post_id, 'files_language', $language);

        //need to be added to the front end
        update_post_meta($post_id, 'files_filesize', $filesize);
        update_post_meta($post_id, 'files_number_of_pages', $number_of_pages);

        echo json_encode(array(
            "file_path" => $document_url,
            "filename" => $filename,
            "filetype" => $filetype,
            "url" => get_permalink($post_id),
            "status" => "success"
        ));

        //delete it afterwards to save space
        unlink($path);
        exit(0);
    }

}
