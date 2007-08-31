<div align='center'>
    <p>Who would you like to give your <strong>{$item_name}</strong> to?</p>

    <form action='{$display_settings.public_dir}/item' method='post'>
        <input type='hidden' name='state' value='give_process' />
        <input type='hidden' name='give[item_id]' value='{$item_id}' />
    
        <table width='20%' class='dataTable'>
            <tr>
                <td class='dataTableSubhead'>
                    <label for='give[username]'>Username</label>
                </td>
                <td class='dataTableRow'>
                    <input type='text' name='give[username]' id='give[username]' maxlength='25' />
                </td>
            </tr>
            <tr>
                <td colspan='2' class='dataTableRow' align='right'>
                    <input type='submit' value='Give' />
                </td>
            </tr>
        </table>
    </form>
</div>
