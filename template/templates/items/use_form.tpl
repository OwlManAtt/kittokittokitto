<div align='center'>
    <p>Which pet would you like to {$use_verb} using the <strong>{$item.name}</strong>?</p>

    <form action='{$display_settings.public_dir}/item' method='post'>
        <input type='hidden' name='state' value='use_item' />
        <input type='hidden' name='use[item_id]' value='{$item.id}' />
    
        <table width='20%' class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='pet'>Pet</label>
                </td>
                <td class='inputTableRow' id='pet_td'>
                    {html_options name='use[pet_id]' id='pet' selected=$user->getActiveUserPetId() options=$pets}
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt'>&nbsp;</td>
                <td class='inputTableRowAlt' align='right'>
                    <input type='submit' value='{$use_verb|capitalize}' />
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var pet = new Spry.Widget.ValidationSelect('pet_td',{validateOn:['change','blur']});
</script>
{/literal}
