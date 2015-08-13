<?php
ini_set('display_error',0);
error_reporting(0);


/*
Dynamic Dummy Image Generator - DummyImage.com
Copyright (c) 2010 Russell Heimlich

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

if ($_REQUEST['size']=='') $_REQUEST['size'] = '150x150';  
if ($_REQUEST['bg_color']=='') $_REQUEST['bg_color'] = 'dddddd';  
if ($_REQUEST['fg_color']=='') $_REQUEST['fg_color'] = '888888';  
if ($_REQUEST['file_format']) $_REQUEST['file_format'] = 'png';
if ($_REQUEST['text']=='') $_REQUEST['text'] = 'no thumbnail';

$size = $_REQUEST['size'];
$bg_color = $_REQUEST['bg_color'];
$fg_color = $_REQUEST['fg_color'];
$file_format = $_REQUEST['file_format'];
$class = $_REQUEST['class'];


include "color.class.php";

 //Ruquay K Calloway http://ruquay.com/sandbox/imagettf/ made a better function to find the coordinates of the text bounding box so I used it.
function imagettfbbox_t($size, $text_angle, $fontfile, $text){
    // compute size with a zero angle
    $coords = imagettfbbox($size, 0, $fontfile, $text);
    
  // convert angle to radians
    $a = deg2rad($text_angle);
    
  // compute some usefull values
    $ca = cos($a);
    $sa = sin($a);
    $ret = array();
    
  // perform transformations
    for($i = 0; $i < 7; $i += 2){
        $ret[$i] = round($coords[$i] * $ca + $coords[$i+1] * $sa);
        $ret[$i+1] = round($coords[$i+1] * $ca - $coords[$i] * $sa);
    }
    return $ret;
}
  

$background = new color();
$background->set_hex($bg_color);

$foreground = new color();
$foreground->set_hex($fg_color);


//Find the image dimensions
$dimensions = explode('x',$size); //dimensions are always the first paramter in the URL.
$width = preg_replace('/[^\d]/i', '',$dimensions[0]);
$height = $width;
if ($dimensions[1]) {
  $height = preg_replace('/[^\d]/i', '',$dimensions[1]);
}

$area = $width * $height;
if ($area >= 16000000) { //Limit the size of the image to no more than an area of 16,000,000.
  die("Too big of an image!"); //If it is too big we kill the script.
}

$text_angle = 0; //I don't use this but if you wanted to angle your text you would change it here.

$font = "THSarabun.ttf"; // If you want to use a different font simply upload the true type font (.ttf) file to the same directory as this PHP file and set the $font variable to the font file name. I'm using the M+ font which is free for distribution -> http://www.fontsquirrel.com/fonts/M-1c

$img = imageCreate($width,$height); //Create an image.
$bg_color = imageColorAllocate($img, $background->get_rgb('r'), $background->get_rgb('g'), $background->get_rgb('b'));
$fg_color = imageColorAllocate($img, $foreground->get_rgb('r'), $foreground->get_rgb('g'), $foreground->get_rgb('b'));

if (!isset($_REQUEST['text'])) $_REQUEST['text'] = $iab_name." ".$width." × ".$height;
$text = $_REQUEST['text'];

$text = preg_replace('/\|/i', "\n", $text);

//Ric Ewing: I modified this to behave better with long or narrow images and condensed the resize code to a single line.
//$fontsize = max(min($width/strlen($text), $height/strlen($text)),5); //scale the text size based on the smaller of width/8 or hieght/2 with a minimum size of 5.

$fontsize = max(min($width/strlen($text)*1.15, $height*0.5) ,16);

$textBox = imagettfbbox_t($fontsize, $text_angle, $font, $text); //Pass these variable to a function that calculates the position of the bounding box.

$textWidth = ceil( ($textBox[4] - $textBox[1]) * 1.07 ); //Calculates the width of the text box by subtracting the Upper Right "X" position with the Lower Left "X" position.

$textHeight = ceil( (abs($textBox[7])+abs($textBox[1])) * 1 ); //Calculates the height of the text box by adding the absolute value of the Upper Left "Y" position with the Lower Left "Y" position.

$textX = ceil( ($width - $textWidth)/2 ); //Determines where to set the X position of the text box so it is centered.
$textY = ceil( ($height - $textHeight)/2 + $textHeight ); //Determines where to set the Y position of the text box so it is centered.

imageFilledRectangle($img, 0, 0, $width, $height, $bg_color); //Creates the rectangle with the specified background color.

imagettftext($img, $fontsize, $text_angle, $textX, $textY, $fg_color, $font, $text);   //Create and positions the text http://us2.php.net/manual/en/function.imagettftext.php

header('Content-type: image/'.$file_format);
  
$imgFile = $size.'.'.$file_format;
//Create the final image based on the provided file format.
switch ($file_format) {
    case 'gif':
    imagegif($img);
      break;
    case 'png':
     imagepng($img);
        break;
  case 'jpg':
    imagejpeg($img);
    break;
  case 'jpeg':
    imagejpeg($img);
    break;
}


imageDestroy($img);//Destroy the image to free memory.

?>