<form action='{$display_settings.public_dir}/admin-items-add/' method='post'>
    <input type='hidden' name='state' value='save' />
    
    <table class='inputTable' width='20%'>
        <tr>
            <td class='inputTableRow' colspan='2' style='text-align: center;'>
                Pick an item type to proceed.
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='class_id'>Type</label>
            </td>
            <td class='inputTableRowAlt' id='class_id_td'>
                {html_options id='class_id' name='item[class_id]' options=$classes}
            </td>
        </tr>
        <tr>
            <td class='inputTableRow' colspan='2' style='text-align: right;'>
                <input type='submit' value='Next' />
            </td>
        </tr>
    </table>
</form>
