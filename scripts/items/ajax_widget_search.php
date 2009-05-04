<?php
/**
 * Find some item types. 
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

header('Content-type: text/plain');

// Remove % from the query to prevent fuckups in autosuggestion.
$query = stripinput($_REQUEST['q']);
$query = str_replace('%','\%',$query);

// Turn the limit into useful parameters for findBy.
$limit = (int)stripinput($_REQUEST['limit']);
$start = null;
$end = null;
if($limit != 0)
{
    $start = 0;
    $end = $limit;
}

$items = new ItemType($db);

if($query != null)
{
    $items = $items->findBy(array(
        array(
            'table' => 'item_type',
            'column' => 'item_name',
            'like' => true, 
            'value' => $query.'%',
        ),
    ),null,false,$start,$end);
}
else
{
    $items = array();
}

$ITEM_LIST = array();
foreach($items as $item)
{
    $ITEM_LIST[] = array(
        'id' => $item->getItemTypeId(),
        'name' => $item->getItemName(),
        'image_url' => $item->getImageUrl(),
    );
}

$renderer->assign('items',$ITEM_LIST);
$renderer->display('items/search_ajax.tpl');
?>
