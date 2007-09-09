{if $notice != ''}<p id='user_notice' class='{$fat} notice-box'>{$notice}</p>{/if}

<div align='center'>
    <form action='{$display_settings.public_dir}/admin-users/' method='post'>
        <input type='hidden' name='state' value='save' />
        <input type='hidden' name='user_id' value='{$user_info.id}' />
        
        <table class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>Username</td>
                <td class='inputTableRow'>{$user_info.name}</td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='status'>Status</label>
                </td>
                <td class='inputTableRowAlt'>
                    {html_options id='status' name='user[status]' options=$statuses selected=$user_info.status}
                </td>
            </tr>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='title'>Title</label> 
                </td>
                <td class='inputTableRow'>
                    <input type='text' id='title' name='user[title]' value='{$user_info.title}' maxlength='20' /> 
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='profile'>Profile</label>
                </td>
                <td class='inputTableRowAlt'>
                    <textarea id='profile' name='user[profile]' cols='45' rows='10'>{$user_info.profile}</textarea>
                </td>
            </tr>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='signature'>Signature</label>
                </td>
                <td class='inputTableRow'>
                    <textarea id='signature' name='user[signature]' cols='45' rows='10'>{$user_info.signature}</textarea>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>Groups</td>
                <td class='inputTableRowAlt'>
                    {html_checkboxes options=$groups selected=$user_info.groups separator='<br />' name='user[groups]'} 
                </td>
            </tr>
            <tr>
                <td class='inputTableRow' colspan='2' style='text-align: right;'>
                    <input type='submit' value='Save' /> 
                </td>
            </tr>
        </table>
    </form>
</div>
