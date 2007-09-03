<?php
/**
 *  
 **/

/** 
 *
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
