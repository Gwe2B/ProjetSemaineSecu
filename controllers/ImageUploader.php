<?php

/**
 * Manage the upload of the images
 * @author Gwenaël
 * @version 1
 */
abstract class ImageUploader {
    /**
     * The accepted files extensions
     * @var array(string)
     */
    private const VALID_EXT = array(
        "TIF", "TIFF", "BMP", "JPG", "JPEG", "GIF", "PNG"
    );

    /**
     * Max size that the uploaded file can reach (in octets) (here ~= 10Mo)
     * TODO: See to change the value
     * @var int
     */
    private const MAX_SIZE = 100000000;

    public static function uploadImage(string $destFolder, array $file) : ?string {
        $result = null;

        if(!(file_exists($destFolder) && is_dir($destFolder))) {
            mkdir($destFolder);
        }

        if($file['error'] == 0 && $file['size'] <= self::MAX_SIZE) {
            $fileExt = pathinfo($file['name'])['extension'];
            $fileMime = mime_content_type($file['tmp_name']);

            if(in_array(strtoupper($fileExt), self::VALID_EXT) && preg_match("#^image/#", $fileMime)) {
                $result = $destFolder.DIRECTORY_SEPARATOR.uniqid(date('Ymd-')).'.'.$fileExt;
                move_uploaded_file($file['tmp_name'], 'uploads'.DS.$result);
            }
        }

        return $result;
    }
}