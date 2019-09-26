<?php

/**
 * Description of PDF
 *
 * @author emmanuelt
 */

class PDF {

    /**
     * This code will get the meta data from the pdf
     * http://www.pdfparser.org/documentation
     * 
     * @param type $path
     * @return type
     */
    static function get_pdf_meta($path) {
        include 'php_libs/vendor/autoload.php';

        // Parse pdf file and build necessary objects.
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($path);

        // Retrieve all details from the pdf file.
        $details = $pdf->getDetails();

        return $details;
    }

}
