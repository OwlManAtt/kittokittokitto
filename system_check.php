<?php
/**
 * A quick system check to ensure that your server is 
 * Kitto-compatible. 
 *
 * This file is part of 'Kitto_Kitto_Kitto'.
 *
 * 'Kitto_Kitto_Kitto' is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 3 of the License,
 * or (at your option) any later version.
 * 
 * 'Kitto_Kitto_Kitto' is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General
 * Public License along with 'Kitto_Kitto_Kitto'; if not,
 * write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @author Nicholas 'Owl' Evans <owlmanatt@gmail.com>
 * @copyright Nicolas Evans, 2007
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @package Kitto_Kitto_Kitto
 * @subpackage Core
 * @version 1.0.0
 **/

// Better yellow for white BG.
$yellow = '#9F8300';

$php_version = PHP_VERSION;
$version_compatible = version_compare('5.0.0',$php_version,'<=');

// Required modules.
$MODULES = array(
    'GD' => extension_loaded('gd'),
    'MySQL' => extension_loaded('mysql'),
    'PCRE' => extension_loaded('pcre'),
    'Sessions' => extension_loaded('session'),
);

// Warnings.
$WARNING = array(
    'register_globals' => array(
        'value' => ( ini_get('register_globals') == false ? true : false ),
        'help' => "Register_globals being turned on is considered bad practice. You <em>should</em> add ini_set('register_globals','Off'); to your Kitto config file after you successfully complete the installation.",
    ),
    'display_errors' => array(
        'value' => ini_get('display_errors'),
        'help' => "PHP errors will not be sent to the screen - only to an error_log somewhere on your server. This may be a major inconvenience to you as your set Kitto up. If you have problems with a blank white screen, you are probably getting PHP errors, and they are being surpressed. Add ini_set('display_errors','On'); to your Kitto config file to address this.",
    ),
);

$INFO = array(
    'Base Directory' => str_replace('/system_check.php',null,$_SERVER['SCRIPT_FILENAME']),
);

$content = "<?xml version=\"1.0\"?>\n";
$content .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n\t\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
$content .= "<html>\n";
    $content .= "<head>\n";
        $content .= "<meta http-equiv='Content-type' content='text/html; charset=UTF-8' />\n";
        $content .= "<title>KittoKittoKitto System Check</title>\n";
    $content .= "</head>\n";
    $content .= "<body>\n";
        $content .= "<p>Any 'status' fields marked in <span style='color: red;'>red</span> indicate that you have a serious problem and it must be addressed for Kitto to work. <span style='color: $yellow;'>Yellow</span> 'status' fields indicate a warning - Kitto will work, but you probably want to take the suggested corrective action. <span style='color: green;'>Green</span> means OK. The base directory is provided here for your reference - you need it during installation.</p>\n";

        $content .= "<p>Make sure you visit the <a href='http://kittokittokitto.yasashiisyndicate.org/'>KittoKittoWiki</a> often - new releases, news, and developer guides are available there.</p>\n";
        $content .= "<p>If everything looks green, you can proceed with <a href='http://kittokittokitto.yasashiisyndicate.org/wiki/Installing'>installing Kitto</a>.</p>\n";

        $content .= "<div align='center'>\n";
            $content .= "<table style='border: 1px solid black; border-collapse: collapse;' width='50%' border='1' cellpadding='5'>\n";
                $content .= "<tr>\n";
                    $content .= "<td colspan='2' align='center' style='font-weight: bold; font-size: large;'>KittoKittoKitto System Check</td>\n";
                $content .= "</tr>\n";

                $content .= "<tr>\n";
                    $content .= "<td align='center' style='font-weight: bold;' width='50%'>Setting</td>\n";
                    $content .= "<td align='center' style='font-weight: bold;' width='50%'>Status</td>\n";
                $content .= "</tr>\n";

                $content .= "<tr>\n";
                    $content .= "<td>PHP Version</td>\n";
                    $content .= "<td>\n";
                        
                        $color = 'green';
                        if($version_compatible == false)
                        {
                            $color = 'red';
                        }
                        
                        $content .= "<span style='color: $color'>$php_version</span>";
                    $content .= "</td>\n";
                $content .= "</tr>\n";

                foreach($MODULES as $module => $status)
                {
                    $content .= "<tr>\n";
                        $content .= "<td>Extension - $module</td>\n";
                        $content .= "<td>\n";
                            
                            $color = 'green';
                            $status = 'Installed';
                            if($status == false)
                            {
                                $color = 'red';
                                $status = 'Not Installed';
                            }
                            
                            $content .= "<span style='color: $color'>$status</span>";
                        $content .= "</td>\n";
                    $content .= "</tr>\n";
                } // end modules loop

                foreach($WARNING as $option => $STUFF)
                {
                    $content .= "<tr>\n";
                        $content .= "<td>Option $option</td>\n";
                        $content .= "<td>\n";
                            
                            $color = 'green';
                            $status = 'OK';
                            if($STUFF['value'] == false)
                            {
                                $color = $yellow;
                                $status = $STUFF['help'];
                            }
                            
                            $content .= "<span style='color: $color'>$status</span>";
                        $content .= "</td>\n";
                    $content .= "</tr>\n";
                } // end modules loop

                foreach($INFO as $label => $data)
                {
                    $content .= "<tr>\n";
                        $content .= "<td>$label</td>\n";
                        $content .= "<td>\n";
                            
                            $color = 'green';
                            $content .= "<span style='color: $color'>$data</span>";
                        $content .= "</td>\n";
                    $content .= "</tr>\n";
                } // end modules loop

           $content .= "</table>\n";
        $content .= "</div>\n";
    $content .= "</body>\n";
$content .= "</html>\n";

print $content;
?>
