<?php
namespace Upload;

class UploadHandler {
    /**
     * @var array - file uploaded
     */
    private $file;

    /**
     * @return array
     */
    public function get_file() : array
    {
        return $this->file;
    }

    /**
     * @param $file - file to set as the $file value
     */
    private function set_file($file) : void
    {
       $this->file = $file;
    }

    /**
     * Checks the super global $_FILES and stores the file as $file
     * @return void
     */
    public function verify_upload_info() : void
    {
        try {
            // Check $_FILES['uploadedFile']['error'] value.
            switch ($_FILES['uploadedFile']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }

            // You should also check filesize here.
            if ($_FILES['uploadedFile']['size'] > 1000000) {
                throw new \RuntimeException('Exceeded filesize limit.');
            }

            // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
            // Check MIME Type by yourself.
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search(
                    $finfo->file($_FILES['uploadedFile']['tmp_name']),
                    array(
                        'csv' => 'text/csv',
                        'text' => 'text/plain'
                    ),
                    true
                )) {
                throw new \RuntimeException('The file type uploaded was not a CSV. Please upload a CSV file.');
            }

            $parsed_upload = array_map('str_getcsv', file($_FILES['uploadedFile']['tmp_name']));

            //Set the file private value
            $this->set_file($parsed_upload);

        } catch (\RuntimeException $e) {
            echo $e->getMessage();
        }
    }
}
