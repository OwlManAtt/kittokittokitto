<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='group[name]'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <input type='text' name='group[name]' id='group[name]' maxlength='50' size='46' value='{$group.name}' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='group[descr]'>Description</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead'>
                <textarea id='group[descr]' name='group[descr]' cols='45' rows='10'>{$group.description}</textarea>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='group[permissions]'>Permissions</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                {html_checkboxes name='group[permissions]' options=$permissions selected=$permission_defaults separator='<br />'}
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>
