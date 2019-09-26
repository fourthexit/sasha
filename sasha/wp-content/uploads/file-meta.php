<?php

class badiliFileMeta {

    /**
     * This should give some more info about the pdf
     */
    function get_document_meta($filepath) {
        $details = null;
        try {
            include 'controllers/businessService/filetypes/php_libs/vendor/autoload.php';

            // Parse pdf file and build necessary objects.
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($filepath);
            $details = $pdf->getDetails();
            file_put_contents('/tmp/errors.log', PHP_EOL . print_r($details, true), FILE_APPEND);
            echo print_r($details, true);
            return $details;
        }
        catch (Exception $ex) {
            file_put_contents('errors.log', PHP_EOL . print_r($ex->getMessage(), true), FILE_APPEND);
            $response = json_encode(array(
                "message" => print_r($ex->getMessage(), true),
                "status" => "error"
            ));
            echo $response;
            return $response;
        }
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'update_files_meta'));

        //we need motre time and memory
        add_action('init', function () {
            set_time_limit(0);
            ini_set('memory_limit', '-1');
        });
    }

    public function update_files_meta() {
        add_menu_page('UpdateFilesMeta', 'UpdateFilesMeta', 'read', 'update_files_meta', function () {
            $args = array(
                'post_type' => 'files',
                'post_status' => 'publish',
                'posts_per_page' => -1, //get all of them
                'orderby' => 'date',
                'order' => 'ASC'
            );
            //get the files
            $posts = get_posts($args);
            for ($i = 0; $i < count($posts); $i++) {
                $path = get_post_meta($posts[$i]->ID, 'file_link', true);
                if ($path) {
                    if (is_string($path)) {
                        $filepath = preg_replace_callback('/(.+)(\/wp-content\/uploads\/)(.+)/', function ($matches) {
                            $upload_dir = wp_upload_dir();
                            $final = $upload_dir['basedir'] . '/' . $matches[3];
                            return $final;
                        }, $path);

                        //number of pages
                        $meta = self::get_document_meta($filepath);
                        if ($meta != null && $meta['Pages'] && is_int(intval($meta['Pages']))) {

                            update_post_meta($posts[$i]->ID, 'files_number_of_pages', $meta['Pages']);
                        }

                        //files_filesize
                        update_post_meta($posts[$i]->ID, 'files_filesize', filesize($filepath));

                        //the pdf thumbnail
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $filepath);
                        if($mime=='application/pdf') {
                            $local_path = 'assets/uploads/thumb/thumb_' . $posts[$i]->ID . '.png';
                            $current_name = MY_PLUGIN_PATH . $local_path;
                            if (!file_exists($current_name)) {//create it only once coz its expensive
                                $im = new Imagick($path);
                                $im->setimageresolution(640, 480);
                                $im->setIteratorIndex(0);
                                $im->setImageFormat('jpg');
                                //$im->resizeImage(100,100,1,0);                                
                                $im->writeImage($current_name);
                            }
                        }
                    }
                }
            }

            echo 'Done';
        });
    }

}

new badiliFileMeta();
