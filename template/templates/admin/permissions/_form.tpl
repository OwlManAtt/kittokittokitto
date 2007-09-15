<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='name_td'>
                <input type='text' name='group[name]' id='name' maxlength='50' size='46' value='{$group.name}' /><br />

                <span class='validate textfieldRequiredMsg'>You must enter a name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='descr'>Description</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead' id='descr_td'>
                <textarea id='descr' name='group[descr]' cols='45' rows='10'>{$group.description}</textarea><br />
                <span class='validate textareaRequiredMsg'>You must enter a message.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='permissions'>Permissions</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='permissions_td'>
                {html_checkboxes name='group[permissions]' options=$permissions selected=$permission_defaults separator='<br />' id='permissions'}<br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>

{literal}
<script type='text/javascript'>
    var name = new Spry.Widget.ValidationTextField("name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var descr = new Spry.Widget.ValidationTextarea('descr_td');
    // var permissions = new Spry.Widget.ValidationCheckbox('permissions_td',{isRequired: false});
</script>
{/literal}
