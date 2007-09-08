<div id='breadcrumb-trail'>{kkkurl link_text='Boards' slug='boards'} &raquo; {kkkurl link_text=$board.name slug='threads' args=$board.id} &raquo; {if $thread.sticky == 1}Sticky: {/if}{kkkurl link_text=$thread.name slug='thread' args=`$thread.id`/`$page`#`$post_id`} &raquo; Edit Post</div>

<div align='center'>
    <div class='quick-reply'>
        <form action='{$display_settings.public_dir}/edit-post/' method='post'>
            <input type='hidden' name='state' value='save' />
            <input type='hidden' name='post_id' value='{$post_id}' />
            <input type='hidden' name='page' value='{$page}' />

            <table border='0'>
                <tr>
                    <td style='vertical-align: top; font-weight: bold; font-size: large;'>Message</td>
                    <td colspan='2'>
                        <textarea name='post_text' id='post_text' cols='60' rows='15'>{kkkp2nl text=$text}</textarea>
                    </td>
                </tr>
                <tr>
                    <td align='right' colspan='3'>
                        <input type='submit' value='Save' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
