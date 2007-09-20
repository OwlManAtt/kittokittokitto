<p>The price of an item will be set to a random number between the minimum and maximum price. The number of items added to the shop's stock each restock will be a number between the minimum and maximum quantity. The maximum number of the item that the shop will have in stock at once is the quantity ceiling.</p>

<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>Item</td>
            <td class='inputTableRow'>{$item.name}</td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='shop_id'>Shop</label>
            </td>
            <td class='inputTableRowAlt' id='shop_id_td'>
                {html_options name='restock[shop_id]' id='shop_id' options=$shops selected=$restock.shop_id} 
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='frequency'>Restock Frequency</label>
            </td>
            <td class='inputTableRow' id='frequency_td'>
                <input type='text' name='restock[frequency]' id='frequency' value='{$restock.frequency}' size='8' /> seconds<br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='minimum_price'>Minimum Price</label>
            </td>
            <td class='inputTableRowAlt' id='minimum_price_td'>
                <input type='text' name='restock[minimum_price]' id='minimum_price' value='{$restock.minimum_price}' size='8' /><br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='maximum_price'>Maximum Price</label>
            </td>
            <td class='inputTableRow' id='maximum_price_td'>
                <input type='text' name='restock[maximum_price]' id='maximum_price' value='{$restock.maximum_price}' size='8' /><br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='minimum_quantity'>Minimum Quantity</label>
            </td>
            <td class='inputTableRowAlt' id='minimum_quantity_td'>
                <input type='text' name='restock[minimum_quantity]' id='minimum_quantity' value='{$restock.minimum_quantity}' size='8' /><br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='maximum_quantity'>Maximum Quantity</label>
            </td>
            <td class='inputTableRow' id='maximum_quantity_td'>
                <input type='text' name='restock[maximum_quantity]' id='maximum_quantity' value='{$restock.maximum_quantity}' size='8' /><br />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='quantity_cap'>Quantity Ceiling</label>
            </td>
            <td class='inputTableRowAlt' id='quantity_cap_td'>
                <input type='text' name='restock[quantity_cap]' id='quantity_cap' value='{$restock.quantity_cap}' size='8' /><br />
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
    var shoppe = new Spry.Widget.ValidationSelect('shop_id_td',{validateOn:['blur','change']});
    var frequency = new Spry.Widget.ValidationTextField('frequency_td', "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var min_price = new Spry.Widget.ValidationTextField('minimum_price_td', "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var max_price = new Spry.Widget.ValidationTextField('maximum_price_td', "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var min_qty = new Spry.Widget.ValidationTextField('minimum_quantity_td', "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var max_qty = new Spry.Widget.ValidationTextField('maximum_quantity_td', "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var qty_cap = new Spry.Widget.ValidationTextField('quantity_cap_td', "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
</script>
{/literal}
