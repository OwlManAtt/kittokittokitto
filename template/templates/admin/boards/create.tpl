
{if $notice != ''}<p id='pref_notice' class='{$fat} notice-box'>{$notice}{/if}

<div align='center'>
    <div style='width: 80%; margin-right: auto; margin-left: auto;'>
        <form action='{$display_settings.public_dir}/admin-boards-create/' method='post'>
            <input type='hidden' name='state' value='create' />
        
            <table class='inputTable' width='100%'>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        <label for='board[name]'>Board Name</label>
                    </td>
                    <td width='80%' class='inputTableRow'>
                        <input type='text' name='board[name]' id='board[name]' />
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt inputTableSubhead'>
                        <label for='board[descr]'>Board Description</label>
                    </td>
                    <td class='inputTableRowAlt'>
                        <input type='text' name='board[descr]' id='board[descr]' />
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                        Locked
                    </td>
                    <td class='inputTableRow'>
                        <input type='radio' name='board[locked]' id='board[locked][Y]' value='Y' />
                            <label for='board[locked][Y]'>Yes</label>
                        <input type='radio' name='board[locked]' id='board[locked][N]' value='N' checked="checked" />
                            <label for='board[locked][N]'>No</label>
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt inputTableSubhead'>
                        News Source
                    </td>
                    <td class='inputTableRowAlt'>
                        <input type='radio' name='board[news_source]' id='board[news_source][Y]' value='Y' />
                            <label for='board[news_source][Y]'>Yes</label>
                        <input type='radio' name='board[news_source]' id='board[news_source][N]' value='N' checked="checked" />
                            <label for='board[news_source][N]'>No</label>
                    </td>
                </tr>
                <tr>
                    <td width='20%' class='inputTableRow inputTableSubhead'>
                       <label for='board[order_by]'>Order By</label>
                    </td>
                    <td class='inputTableRow'>
                       <input type='text' name='board[order_by]' id='board[order_by]' /> 
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt'>&nbsp;</td>
                    <td class='inputTableRowAlt' style='text-align: right;' colspan='2'>
                        <input type='submit' value='Save' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
