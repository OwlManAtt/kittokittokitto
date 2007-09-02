<div id='breadcrumb-trail'>{kkkurl link_text='Boards' slug='boards'} &raquo; {kkkurl link_text=$board.name slug='threads' args=$board.id} &raquo; {$thread.name}</div>

{section name=index loop=$posts}
{include file='boards/_post.tpl' post=$posts[index]}
{sectionelse}
<p>There are no posts in this thread. That's quite odd...</p>
{/section}

{if $thread.locked == 'N'}<div align='center'>
    <a name='post'>&nbsp;</a>
    <div class='quick-reply'>
        <form action='{$display_settings.public_dir}/thread-reply/' method='post'>
            <input type='hidden' name='thread_id' value='{$thread.id}' />

            <table border='0'>
                <tr>
                    <td style='vertical-align: top; font-weight: bold; font-size: large;'>Message</td>
                    <td colspan='2'>
                        <textarea name='post[text]' id='post_text' cols='80' rows='15'></textarea>
                    </td>
                </tr>
                <tr>
                    <td align='right' colspan='3'>
                        <input type='submit' value='Reply' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>{/if}

<br clear='all' />
<div class='pages'>{$pagination}</div>
