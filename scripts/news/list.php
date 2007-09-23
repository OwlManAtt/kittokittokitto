<?php
/**
 * News aggregation page.
 *
 * This page uses the News class to aggregate forum posts from news-
 * source boards into a convinient bulletin list. 
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
 * @subpackage News 
 * @version 1.0.0
 **/

/** 
 * The total number of news items to show per page. Do not show too many
 * per page, because there are 2-3 queries done per item, and that quickly
 * adds up.
 **/
$max_items_per_page = 10;

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

// Where do we slice the record set? (Note: Don't worry about
// LIMIT X,Y starting from zero - that'll be abstracted away).
$start = (($page_id - 1) * $max_items_per_page);
$end = (($page_id - 1) * $max_items_per_page) + $max_items_per_page;

// Generate the pagination. 
$pagination = pagination('news',News::grabNewsSize($db),$max_items_per_page,$page_id);

$NEWS = array();
$news_items = News::grabNews($start,$end,$db);

foreach($news_items as $item)
{
    $NEWS[] = array(
        'thread_id' => $item->getThreadId(),
        'title' => $item->getTitle(),
        'user' => array(
            'name' => $item->getUserName(),
            'id' => $item->getUserId(),
        ),
        'posted_at' => $item->getDatetime(), 
        'text' => $item->getText(),
        'comments' => $item->getComments(),
        'comments_word' => ($item->getComments != 1) ? 'comments' : 'comment',
    );
} // end item loop

$renderer->assign('pages',$pagination);
$renderer->assign('news',$NEWS);
$renderer->display('news/list.tpl');
?>
