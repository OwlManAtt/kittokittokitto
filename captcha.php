<?php
/**
 * This program is free software; you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation; either version 2 
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
 * GNU General Public License for more details: 
 * http://www.gnu.org/licenses/gpl.html
 *
 * @author Simon Jarvis
 * @copyright 2006 Simon Jarvis
 * @date 03/08/06
 * @updated 07/02/07
 * @requirements PHP 4/5 with GD and FreeType libraries
 * @package Kitto_Kitto_Kitto
 * @subpackage Core
 * @link http://www.white-hat-web-design.co.uk/articles/php-captcha.php
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GPLv2
 **/

ob_start();
session_start();
require("includes/config.inc.php");

/**
 * CaptchaSecurityImages 
 * 
 * @package Kitto_Kitto_Kitto
 * @subpackage Captcha 
 * @author: Simon Jarvis
 * @copyright: 2006 Simon Jarvis
 * @license GNU GPL v2 {@link http://www.gnu.org/licenses/gpl-2.0.txt}
 **/
class CaptchaSecurityImages {

   function generateCode($characters) {
      /* list all possible characters, similar looking characters and vowels have been removed */
      $possible = '345789cfkmnpstwxyz';
      $code = '';
      $i = 0;
      while ($i < $characters) { 
         $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
         $i++;
      }
      return $code;
   }

    function CaptchaSecurityImages($width='120',$height='40',$characters='6',$font) {
        global $font,$font_fallback;
        $code = $this->generateCode($characters);

        /* font size will be 75% of the image height */
        $font_size = $height * 0.75;
        $image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
        /* set the colours */
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 20, 40, 100);
        $noise_color = imagecolorallocate($image, 100, 120, 180);
        /* generate random dots in background */
		/*
        for( $i=0; $i<($width*$height)/12; $i++ ) {
            imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
        }
		*/
        /* generate random lines in background */
        for( $i=0; $i<($width*$height)/150; $i++ ) {
            imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
        }
        $x = 4;
        $y = 5;

        if(function_exists('imagettfbbox') == true)
        {
            imagettfbbox($font_size, 0, $font, $code);
            $textbox = imagettfbbox($font_size, 0, $font, $code);
            $x = ($width - $textbox[4])/2;
            $y = ($height - $textbox[5])/2;
            imagettftext($image, $font_size, 0, $x, $y, $text_color, $font, $code);
        } 
        else 
        {
            imagestring($image, $font_fallback, $x, $y, $code, $text_color);
        }
        
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
        $_SESSION['security_code'] = $code;
    }

}

$width = 90;
$height = 30;
$characters = 6;

$font = $APP_CONFIG['base_path'].'/resources/fonts/monofont.ttf';
$font_fallback = imageloadfont($APP_CONFIG['base_path'].'/resources/fonts/captchafont.gdf');

$captcha = new CaptchaSecurityImages($width,$height,$characters,$font);

?>
