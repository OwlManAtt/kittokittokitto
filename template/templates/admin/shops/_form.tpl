<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='shop_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='shop_name_td'>
                <input type='text' name='shop[name]' id='shop_name' maxlength='30' value='{$shop.name}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='shop_image'>Image</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead' id='shop_image_td'>
                <input type='text' name='shop[image]' id='shop_image' value='{$shop.image}' maxlength='200' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a shopkeeper image.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='welcome'>Welcome Message</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='shop_welcome_text_td'>
                <textarea id='welcome' name='shop[welcome_text]' cols='45' rows='10'>{$shop.welcome_text}</textarea><br />
                <span class='validate textareaRequiredMsg'>You must enter some welcome text.</span>
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
    var name = new Spry.Widget.ValidationTextField("shop_name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var image = new Spry.Widget.ValidationTextField("shop_image_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var welcome_text = new Spry.Widget.ValidationTextarea('shop_welcome_text_td');
</script>
{/literal}
