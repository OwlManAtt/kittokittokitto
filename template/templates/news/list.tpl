<div align='center'>
    {section name=index loop=$news}
    {assign var=item value=$news[index]}
    <div class='news-post'>
        <p class='news-post-header'>{$item.title}</p>
        <div class='news-post-body'>{$item.text}</div> 
        <p class='news-post-footer'>{$item.posted_at} &nbsp;&nbsp;&bull;&nbsp;&nbsp; {kkkurl link_text=$item.user.name slug='profile' args=$item.user.id} &nbsp;&nbsp;&bull;&nbsp;&nbsp; {kkkurl link_text="`$item.comments` `$item.comments_word`" slug='thread' args=$item.thread_id}</p> 
    </div>
    {sectionelse}
    <p align='ceneter'><em>There are no news posts.</em></p>
    {/section}
</div>

<div class='pages'>{$pages}</div>
