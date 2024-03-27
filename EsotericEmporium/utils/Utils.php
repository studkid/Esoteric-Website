<?php

class Utility{
    //resize an image to a given size, then crop it to a square 
    public static function ResizeImage(string $imageSource, int $size) : string //returns an echoable <img> tag of the resized image
    {
        $width = $size;

        //grab the dimensions of a given file and return it as an array. [0] is width and [1] is height
        $size = getimagesize($imageSource);
        //calculate aspect ratio of the source image
        $ratio = $size[0] / $size[1];
        //get 'image' data from the jpeg at the specified path
        $image = imagecreatefromjpeg($imageSource);

        //calculate the rezised image dimensions
        if($ratio < 1)
        {
            $new_width = $width;
            $new_height = $width / $ratio;
        }
        else
        {
            $new_width = $width * $ratio;
            $new_height = $width;
        }

        //create blank 'image' at a given width and height
        $image_p = imagecreatetruecolor($new_width, $new_height);
        //take data from $image and put it into $image_p at a specified size
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);

        //crop the resized image into a square
        $image_final = imagecrop($image_p, ["x" => (($new_width-$width)/2), "y" => (($new_height-$width)/2), "width" => $width, "height" => $width]);
        
        ob_start(); //open a buffer to capture the raw data of the imagejpeg function
        imagejpeg($image_final, NULL, 100);  //convert the image with no output destination and 100% quality
        $rawImageBytes = ob_get_clean(); //put the contents of the buffer into a variable, then delete the buffer
        
        return "<img src='data:image/jpeg;base64," . base64_encode( $rawImageBytes ) . "' />"; //use the base64 data to display the resized image
    }
}
?>