<!DOCTYPE html>
<html>

<body>



    <?php
    if ($_POST) {

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $isUploadOK = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                echo "<br>";
                echo "width is" . $check[0];
                echo "height is" . $check[1];

                $isUploadOK = 1;
            } else {
                echo "File is not an image.";
                $isUploadOK = 0;
            }
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $isUploadOK = false;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $isUploadOK = false;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($isUploadOK == false) {
            echo "Sorry, your file was not uploaded."; // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    ?>



    <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form>







</body>

</html>