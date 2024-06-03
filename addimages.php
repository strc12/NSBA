
<?php
#header("Location:admin.php");
print_r($_POST);
try{
	include_once('connection.php');
	array_map("htmlspecialchars", $_POST);
    $ext = pathinfo($_FILES["imagey"]["name"], PATHINFO_EXTENSION);
    $name = str_replace(' ', '', $_FILES["imagey"]["name"]);
    
   
    
	$stmt = $conn->prepare("INSERT INTO TblImages(ImageID,filename,dateadded,type)VALUES 
    (NULL,:fn, :dateadded,:type)");
    $stmt->bindParam(':fn', $_FILES["imagey"]["name"]);
    $stmt->bindParam(':dateadded', $_POST["dateofupload"]);
    $stmt->bindParam(':type', $_POST["typeofdoc"]);
    $stmt->execute();
    $target_dir = "images/";

    $target_file = $target_dir . basename($name);
    echo $target_file;
    
    

   
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if (move_uploaded_file($_FILES["imagey"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["imagey"]["name"])). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    #cropper
    $imagePath = "./images/".$name;
    list($originalWidth, $originalHeight, $imageType) = getimagesize($imagePath);
    
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($imagePath);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($imagePath);
            break;
        default:
            die('Unsupported image type');
    }
    
    // Set the desired aspect ratio
    if ($_POST["typeofdoc"]=="Panorama"){
        $targetAspectRatio = 20 / 9;
    }elseif ($_POST["typeofdoc"]=="Landscape"){
        $targetAspectRatio = 4 / 3;
    }elseif ($_POST["typeofdoc"]=="Portrait"){
        $targetAspectRatio = 3 / 4;
    }else{
        $targetAspectRatio = 1 / 1;
    }
    
    // Calculate the new dimensions
    $originalAspectRatio = $originalWidth / $originalHeight;
    echo($originalHeight ." orig ". $originalWidth."<br>");
    if ($originalAspectRatio > $targetAspectRatio) {
        // Original image is wider than target aspect ratio
        $newHeight = $originalHeight;
        $newWidth = $originalHeight * $targetAspectRatio;
        $cropX = ($originalWidth - $newWidth) / 2;
        echo($newHeight ." wide ". $newWidth."<br>");
        #$cropY = 0;
    } 
    if ($originalAspectRatio <= $targetAspectRatio){
        // Original image is taller than target aspect ratio
        $newWidth = $originalWidth;
        $newHeight = $originalWidth / $targetAspectRatio;
        #$cropX = 0;
        $cropY = ($originalHeight - $newHeight) / 2;
        echo($newHeight ." tall ". $newWidth."<br>");
    }
    if (!isset($cropX)){
        $cropX=0;
    }
    if (!isset($cropY)){
        $cropY=0;
    }
    // Create a new image canvas with the new dimensions
    $croppedImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Copy and resize the original image to the new canvas
    imagecopyresampled($croppedImage, $image, 0, 0, $cropX, $cropY, $newWidth, $newHeight, $newWidth, $newHeight);
    
    // Save or output the cropped image
    $outputPath = "./images/".$name;
    imagejpeg($croppedImage, $outputPath, 90);
    
    // Clean up
    imagedestroy($image);
    imagedestroy($croppedImage);
    
    echo "Image cropped and saved to $outputPath";

    /* // load your source image
    $bigim="./images/".$name;
Echo($bigim);
//getting the image dimensions
list($width, $height) = getimagesize($bigim);

//saving the image into memory (for manipulation with GD Library)
$myImage = imagecreatefromjpeg($bigim);

// calculating the part of the image to use for thumbnail
if ($width > $height) {
  $y = 0;
  $x = ($width - $height) / 2;
  $smallestSide = $height;
} else {
  $x = 0;
  $y = ($height - $width) / 2;
  $smallestSide = $width;
}

// copying the part into thumbnail
$thumbSize = 300;
$thumb = imagecreatetruecolor($thumbSize, $thumbSize);
imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);

//final output
#header('Content-type: image/jpeg');
imagejpeg($thumb,$bigim); */
}
catch(PDOException $e)
{
    echo "error".$e->getMessage();
}
$conn=null; 
?>