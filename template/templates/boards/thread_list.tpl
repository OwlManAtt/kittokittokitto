<div id='breadcrumb-trail'>{kkkurl link_text='Boards' slug='boards'} &raquo; {$board.name}</div>

{if $board_notice != ''}<p align='center' id='board_notice' class='{$fat} notice-box'>{$board_notice}</p>{/if}

<table class='dataTable' width='85%'>
    <tr>
        <td class='dataTableSubhead' align='center' width='60%'>Topic</td>
        <td class='dataTableSubhead' align='center'>Poster</td>
        <td class='dataTableSubhead' align='center'>Last Post</td>
        <td class='dataTableSubhead' align='center'>Replies</td>
    </tr>
    {section name=index loop=$threads}
    {assign var='thread' value=$threads[index]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center'>{if $thread.sticky == 1}Sticky: {/if}{kkkurl link_text=$thread.topic slug='thread' args=$thread.id}{if $thread.last_page > 1} <small>({kkkurl link_text='Last Page' slug='thread' args=`$thread.id`/`$thread.last_page`})</small>{/if}</td>
        <td class='{$class}' align='center'>{kkkurl link_text=$thread.poster_username slug='profile' args=$thread.poster_id}</td>
        <td class='{$class}' align='center'>{$thread.last_post_at}</td>
        <td class='{$class}' align='right'>{$thread.posts}</td>
    </tr>
    {sectionelse}
    <tr>
        <td align='center' colspan='4' class='dataTableRow'><em>There are no threads.</em></td>
    </tr>
    {/section}
</table>

{if $board.locked != 1}<br clear='all' />
<div id='new-post'>
    <form action='{$display_settings.public_dir}/new-thread/' method='get'>
        <input type='hidden' name='board_id' value='{$board.id}' />
        <input type='submit' value='Create Thread' />
    </form>
</div>{/if}

<br clear='all' />
<div class='pages'>{$pagination}</div>
