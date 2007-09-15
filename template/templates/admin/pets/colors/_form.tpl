<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='color_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='color_name_td'>
                <input type='text' name='color[name]' id='color_name' maxlength='30' value='{$color.name}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='color_image'>Image</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead' id='color_image_td'>
                <input type='text' name='color[image]' id='color_image' value='{$color.image}' maxlength='200' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter an image name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='color_base'>Base Color</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='color_base_td'>
                {html_options id='color_base' name='color[base]' options=$base_options selected=$color.base}<br />
                <span class='validate selectRequiredMsg'>You must specify whether or not this is a base color.</span>
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
    var color_name = new Spry.Widget.ValidationTextField("color_name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var image_name = new Spry.Widget.ValidationTextField("color_image_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var base = new Spry.Widget.ValidationSelect('color_base_td',{validateOn:['blur','change']});
</script>
{/literal}
