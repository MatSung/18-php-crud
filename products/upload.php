
<?php
$target_dir = "products/files/";
$target_file = '';
$uploadOk = 1;
$imageFileType = '';

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {

    $target_file = $target_dir . basename($_FILES["myfile"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["myfile"]["tmp_name"]);
    if ($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        //echo "File is not an image.";
        $uploadOk = 0;
    }
    // Check if file already exists
    //if (file_exists($target_file)) {
    //    echo "Sorry, file already exists.";
    //    $uploadOk = 0;
    //}

    // Check file size
    if ($_FILES["myfile"]["size"] > 500000) {
        //echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        //echo $_FILES["myfile"]["tmp_name"]. "  to  " . $target_file;
        if (move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_file)) {
            //echo "The file " . htmlspecialchars(basename($_FILES["myfile"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
        //print_r($_FILES);
    }
}


?>
