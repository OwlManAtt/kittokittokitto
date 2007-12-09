<div align='center'>
    <p>You have <strong>{$quantity.max} {$item_name}</strong>. Who would you like to give {if $quantity.max == 1}a{else}some of your{/if} {$item_name} to?</p>

    <form action='{$display_settings.public_dir}/item' method='post'>
        <input type='hidden' name='state' value='give_process' />
        <input type='hidden' name='give[item_id]' value='{$item_id}' />
    
        <table width='20%' class='inputTable'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='username'>Username</label>
                </td>
                <td class='inputTableRow' id='username_td'>
                    <input type='text' name='give[username]' id='username' maxlength='25' /><br />
                    <span class='textfieldRequiredMsg valid'>You must pick a user.</span>
                </td>
            </tr>
            <tr>
                <td class='inputTableRowAlt inputTableSubhead'>
                    <label for='quantity'>Quantity</label>
                </td>
                <td class='inputTableRowAlt' id='quantity_td'>
                    <input type='text' name='give[quantity]' id='quantity' size='4' value='{$quantity.default}' /><br />
                </td>
            </tr>
            <tr>
                <td colspan='2' class='inputTableRow' style='text-align: right;'>
                    <input type='submit' value='Give' />
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script type='text/javascript'>
    var user_name = new Spry.Widget.ValidationTextField("username_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var quantity = new Spry.Widget.ValidationTextField("quantity_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1, maxValue: {/literal}{$quantity.max}{literal}});    
    
</script>
{/literal}
