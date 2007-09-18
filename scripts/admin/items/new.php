<?php
/**
 * Begin adding an item.
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
 * @subpackage Items 
 * @version 1.0.0
 **/

switch($_REQUEST['state'])
{
    default:
    {
        $TYPES = array('' => 'Select one...',);
        $classes = new ItemClass($db);
        $classes = $classes->findBy(array(),'ORDER BY item_class.class_descr');
        foreach($classes as $class)
        {
            $TYPES[$class->getItemClassId()] = $class->getClassDescr();
        }
        
        $renderer->assign('classes',$TYPES); 
        $renderer->display('admin/items/new.tpl');

        break;
    } // end default

    case 'save':
    {
        $ERRORS = array();
        $class_id = stripinput($_POST['item']['class_id']);
        
        if($class_id == null)
        {
            $ERRORS[] = 'No class specified.';
        }
        else
        {
            $class = new ItemClass($db);
            $class = $class->findOneByItemClassId($class_id);

            if($class == null)
            {
                $ERRORS[] = 'Invalid class specified.';
            }
        } // end id given

        if(sizeof($ERRORS) > 0)
        {
            draw_errors($ERRORS);
        }
        else
        {
            $item = new ItemType($db);
            $item->setItemClassId($class->getItemClassId());
            $item->save();
            
            redirect(null,null,"admin-items-edit/?item[id]={$item->getItemTypeId()}");
        } // end no errors

        break;
    } // end save
} // end state switch
?>
