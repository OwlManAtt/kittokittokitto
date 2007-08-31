<div align='center'>
    <p>Which pet would you like to {$use_verb} using the <strong>{$item.name}</strong>?</p>

    <form action='{$display_settings.public_dir}/item' method='post'>
        <input type='hidden' name='state' value='use_item' />
        <input type='hidden' name='use[item_id]' value='{$item.id}' />
    
        <table width='20%' class='dataTable'>
            <tr>
                <td class='dataTableSubhead'>
                    <label for='use[username]'>Pet</label>
                </td>
                <td class='dataTableRow'>
                    {html_options name='use[pet_id]' id='use[pet_id]' selected=$user->getActiveUserPetId() options=$pets}
                </td>
            </tr>
            <tr>
                <td colspan='2' class='dataTableRow' align='right'>
                    <input type='submit' value='{$use_verb|capitalize}' />
                </td>
            </tr>
        </table>
    </form>
</div>
