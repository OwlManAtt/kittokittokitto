<div align='center'>
    <p>You have <strong>{$quantity.max} {$item.name}</strong>. Which pet would you like to {$use_verb}?</p>

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
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='quantity'>Quantity</label>
                </td>
                <td class='inputTableRowAlt' id='quantity_td'>
                    {if $item.force_one == 'Y'}1{else}
                    <input type='text' name='use[quantity]' id='quantity' size='4' value='{$quantity.default}' />
                    {/if}
                </td>
            </tr>
            <tr>
                <td class='inputTableRow' colspan='2' style='text-align: right;'>
                    <input type='submit' value='{$use_verb|capitalize}' />
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var pet = new Spry.Widget.ValidationSelect('pet_td',{validateOn:['change','blur']});

    {/literal}
    {if $item.force_one == 'N'}
    {literal}
    var quantity = new Spry.Widget.ValidationTextField("quantity_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1, maxValue: {/literal}{$quantity.max}{literal}});    
    {/literal}
    {/if}
</script>
