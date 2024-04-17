<?php
$message = '';
$filename = '';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_FILES['image']['error'] === 0)
    {
        $message = 'File: ' . $_FILES['image']['name'] . '<br>';
        $message .= 'Size: ' . $_FILES['image']['size'];
    }
    else
    {
        $message = 'The file could not be uploaded.';
    }

    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = preg_replace( '/^[A-z]0-9]/', '.', $extension);
    $filepath = 'img/' . $basename . '.' . $extension;

    $a = 0;
    while(file_exists('img/' . $filename))
    {
        $a = $a + 1;
        $filename = $basename . $a . '.' . $extension;
    }

    $error = ($_FILES['image']['size'] <= 5243880) ? '' : 'Too big';
    $allowed_types = ['image/jpeg'];
    $type = mime_content_type($_FILES['image']['tmp_name']);
    $error = in_array($type, $allowed_types) ? '' : 'Wrong file type';
    $allowed_exts = ['jpeg', 'jpg'];
    $filename = strtolower($_FILES['image']['name']);
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $error .= in_array($ext, $allowed_exts) ? '' : 'Wrong extension';

    $move_to = 'img/' . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $move_to);

    /*//grab the dimensions of a given file and return it as an array. [0] is width and [1] is height
    $size = getimagesize("Uploads/$filename");
    //calculate aspect ratio of the source image
    $ratio = $size[0] / $size[1];
    //create the new scaled-down width and height
    $new_width = 300 * $ratio;
    $new_height = 300;
    //get 'image' data from the jpeg at the specified path
    $image = imagecreatefromjpeg("Uploads/$filename");
    //create blank 'image' at a given width
    $image_p = imagecreatetruecolor($new_width, $new_height);
    //take data from $image and put it into $image_p at a specified location and crop
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);
    //convert the cropped 'image' $image_p back into a jpeg at the given path
    imagejpeg($image_p, "Uploads/$filename");*/

    $size = getimagesize("img/$filename");
    $image = imagecreatefromjpeg("img/$filename");

    $image_p = imagecreatetruecolor($size[0], $size[1]);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $size[0], $size[1], $size[0], $size[1]);

    //$image_final = imagecrop($image_p, ["x" => (($new_width-$width)/2), "y" => (($new_height-$width)/2), "width" => $width, "height" => $width]);
    
    ob_start(); //open a buffer to capture the raw data of the imagejpeg
    imagejpeg($image_p, NULL, 100);  //convert the image with no output destination and 100% quality
    $rawImageBytes = ob_get_clean(); //put the contents of the buffer into a variable, then delete the buffer
    echo "<img src='data:image/jpeg;base64," . base64_encode( $rawImageBytes ) . "' />"; //use the base64 data to show the resized image
}

?>
<!DOCTYPE html>
<html>
    <body>
      <form method="post" action="ImageUploadPage.php" enctype="multipart/form-data">

      <label for="image"><b>Upload File:</b></label>
      <input type="file" name="image" accept="image/*"><br>
      <input type="submit" value="Upload">

      <!--<img src="Uploads/<?= $filename?>">-->
    </body>
</html>