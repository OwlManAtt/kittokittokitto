<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='shop_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <input type='text' name='shop[name]' id='shop_name' maxlength='30' value='{$shop.name}' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='shop_image'>Image</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead'>
                <input type='text' name='shop[image]' id='shop_image' value='{$shop.image}' maxlength='200' />
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='welcome'>Welcome Message</label>
            </td>
            <td class='inputTableRow inputTableSubhead'>
                <textarea id='welcome' name='shop[welcome_text]' cols='45' rows='10'>{$shop.welcome_text}</textarea>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>
