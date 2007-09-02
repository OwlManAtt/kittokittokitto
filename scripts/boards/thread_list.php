<?php
/**
 *  
 **/

/**
 * Be careful setting this higher - each thread
 * is a few queries a piece (done because I'm a lazy
 * developer not worrying too much about scalability,
 * so I may as well make the ORM do something it isn't
 * too good at to save me time!), so they add up quick.
 **/
$max_threads_per_page = 15;

// This is the number of posts shown per page in a thread.
// It is used to determine whether or not to show a link to 
// the thread's last page.
$max_posts_per_page = 15; 

// Handle the page ID for slicing and dicing the inventory up.
$page_id = stripinput($_REQUEST['page']);
if($page_id == null || $page_id <= 0)
{
    $page_id = 1;
}

// Where do we slice the record set? (Note: Don't worry about
// LIMIT X,Y starting from zero - that'll be abstracted away).
$start = (($page_id - 1) * $max_threads_per_page);
$end = (($page_id - 1) * $max_threads_per_page) + $max_threads_per_page;

// Load the board.
$board_id = stripinput($_REQUEST['board_id']);
$board = new Board($db);
$board = $board->findOneByBoardId($board_id);

if($board == null)
{
    draw_errors('Invalid board specified.');
}
else
{
    $BOARD_DATA = array(
        'id' => $board->getBoardId(),
        'name' => $board->getBoardName(),
        'locked' => $board->getBoardLocked($User),
    );
    
    // Generate the pagination. 
    $pagination = pagination("threads/{$board->getBoardId()}",$board->grabThreadsSize(),$max_threads_per_page,$page_id);
    
    $THREAD_LIST = array();
    $threads = $board->grabThreads($start,$end);

    foreach($threads as $thread)
    {
        $THREAD_LIST[] = array(
            'id' => $thread->getBoardThreadId(),
            'topic' => $thread->getThreadName(),
            'posts' => $thread->grabPostsSize() - 1,
            'created_at' => $User->formatDate($thread->getThreadCreatedDatetime()),
            'poster_username' => $thread->getUserName(),
            'poster_id' => $thread->getUserId(),
            'last_post_at' => $User->formatDate($thread->getThreadLastPostedDatetime()),
            'last_page' => ceil($thread->grabPostsSize() / $max_posts_per_page),
            'sticky' => $thread->getStickied(),
            'locked' => $thread->getLocked(),
        );
    } // end thread loop

    if($_SESSION['board_notice'] != null)
    {
        $renderer->assign('board_notice',$_SESSION['board_notice']);
        unset($_SESSION['board_notice']);
    } // end notice

    $renderer->assign('board',$BOARD_DATA);    
    $renderer->assign('threads',$THREAD_LIST);
    $renderer->assign('pagination',$pagination);
    $renderer->display('boards/thread_list.tpl');
} // end board exists
?>
