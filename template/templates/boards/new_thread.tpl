<div id='breadcrumb-trail'>{kkkurl link_text='Boards' slug='boards'} &raquo; {kkkurl link_text=$board.name slug='threads' args=$board.id} &raquo; Create Thread</div> 

<div align='center'>
    <div class='quick-reply'>
        <form action='{$display_settings.public_dir}/new-thread/' method='post'>
            <input type='hidden' name='state' value='post' /> 
            <input type='hidden' name='board_id' value='{$board.id}' />
            
            <table style='border: 0;'>
                <tr>
                    <td style='font-weight: bold; font-size: large;'>
                        <label for='post[title]'>Title</title>
                    </td>
                    <td colspan='2'>
                        <input type='text' name='post[title]' id='post[title]' maxlength='255' size='81' />
                    </td>
                </tr>
                <tr>
                    <td style='vertical-align: top; font-weight: bold; font-size: large;'>
                        <label for='post[text]'>Message</label>
                    </td>
                    <td colspan='2'>
                        <textarea name='post[text]' id='post[text]' cols='80' rows='15'></textarea>
                    </td>
                </tr>
                <tr>
                    <td align='right' colspan='3'>
                        <input type='submit' value='Create' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
