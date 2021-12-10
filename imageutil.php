<?php
    // this is where functions go to handle image files

    require_once('fileconstants.php');

    function validateImageFile() {

        $error_message = "";

        // Check for $_FILES being set and no errors..
        if (isset($_FILES) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) 
        {
            // check for uploaded file < MAX FILE SIZE  and an acceptable image type
            if ($_FILES['image_file']['size'] > IMG_MAX_SIZE) 
            {
                $error_message = "The image file must be less than " . IMG_MAX_SIZE . " Bytes";
            }

            // img type check 
            $image_type = $_FILES['image_file']['type'];

            if 
            ($image_type != 'image/jpg' && $image_type != 'image/jpeg' && $image_type != 'image/pjpeg' && $image_type != 'image/png' && $image_type != 'image/gif') 
            {

                if (empty($error_message)) 
                {
                    $error_message = "The image file must be of type jpg, jpeg, pjpeg, png, or gif.";
                } 
                else 
                {
                    $error_message = ", and be an image of type jpg, jpeg, pjpeg, png, or gif.";
                }
            }
            
        } elseif (isset($_FILES) && $_FILES['image_file']['error'] != UPLOAD_ERR_NO_FILE && $_FILES['image_file']['error'] != UPLOAD_ERR_OK) 
        {

            $error_message = "Error uploading image file.";
        }

        return $error_message;
    }


    function addImageFileReturnPathLocation() {
        $image_file_path = "";

        // Check for $_FILES being set and no errors..
        if (isset($_FILES) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
            $image_file_path = IMG_MAX_SIZE . $_FILES['image_file']['name'];

            if (!move_uploaded_file($_FILES['image_file']['tmp_name'],
            $image_file_path)) {
                $image_file_path = "";
            }
        }

        return $image_file_path;
    }

    function removeImageFile($image_file_path) {
        @unlink($image_file_path);
    }
?>