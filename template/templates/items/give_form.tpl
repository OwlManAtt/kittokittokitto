<div align='center'>
    <p>Who would you like to give your <strong>{$item_name}</strong> to?</p>

    <form action='{$display_settings.public_dir}/item' method='post'>
        <input type='hidden' name='state' value='give_process' />
        <input type='hidden' name='give[item_id]' value='{$item_id}' />
    
        <table width='20%' class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='give[username]'>Username</label>
                </td>
                <td class='inputTableRow'>
                    <input type='text' name='give[username]' id='give[username]' maxlength='25' />
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt'>&nbsp;</td>
                <td class='inputTableRowAlt' align='right'>
                    <input type='submit' value='Give' />
                </td>
            </tr>
        </table>
    </form>
</div>
