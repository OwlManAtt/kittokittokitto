<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='specie_name'>Name</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='specie_name_td'>
                <input type='text' name='specie[name]' id='specie_name' maxlength='30' value='{$specie.name}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='specie_descr'>Description</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead' id='specie_descr_td'>
                <textarea id='specie_descr' name='specie[descr]' cols='45' rows='10'>{$specie.description}</textarea><br />
                <span class='validate textareaRequiredMsg'>You must enter a description.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='specie_dir'>Image Directory</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='image_dir_td'>
                <input type='input' name='specie[image_dir]' id='specie_dir' value='{$specie.image_dir}' maxlength='200'/><br />
                <span class='validate textfieldRequiredMsg'>You must enter an image directory.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='specie_hunger'>Max Hunger</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead' id='specie_hunger_td'>
                <input type='text' name='specie[hunger]' id='specie_hunger' maxlength='3' size='3' value='{$specie.max_hunger}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a max hunger level.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='specie_happiness'>Max Happiness</label>
            </td>
            <td class='inputTableRow inputTableSubhead' id='specie_happiness_td'>
                <input type='text' name='specie[happiness]' id='specie_happiness' maxlength='3' size='3' value='{$specie.max_happiness}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a max happiness level.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='specie_available'>Available</label>
            </td>
            <td class='inputTableRowAlt inputTableSubhead' id='specie_available_td'>
                {html_options id='specie_available' name='specie[available]' options=$available_options selected=$specie.available}<br />
                <span class='validate selectRequiredMsg'>You must specify whether or not this specie is available.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>

{literal}
<script type='text/javascript'>
    var specie_name = new Spry.Widget.ValidationTextField("specie_name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var specie_descr = new Spry.Widget.ValidationTextarea('specie_descr_td');
    var image_dir = new Spry.Widget.ValidationTextField("image_dir_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var hunger = new Spry.Widget.ValidationTextField("specie_hunger_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var happiness = new Spry.Widget.ValidationTextField("specie_happiness_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1});    
    var available = new Spry.Widget.ValidationSelect('specie_available_td',{validateOn:['blur','change']});
</script>
{/literal}
