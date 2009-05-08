<div align='center'>
    <table class='inputTable'>
        <tr>
            <td class='inputTableRow inputTableSubhead'>Type</td>
            <td class='inputTableRow'>{$item.type}{if $item.show_edit_materials == 1} {kkkurl slug='admin-recipe' link_text='(Manage Materials)' args="?item[id]=`$item.id`"}{/if}</td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='item_name'>Name</label>
            </td>
            <td class='inputTableRowAlt' id='item_name_td'>
                <input type='text' name='item[name]' id='item_name' maxlength='50' value='{$item.name}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter a name.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRow inputTableSubhead'>
                <label for='description'>Description</label>
            </td>
            <td class='inputTableRow' id='item_description_td'>
                <textarea name='item[description]' id='description' cols='45' rows='10'>{$item.description}</textarea><br />
                <span class='validate textareaRequiredMsg'>You must enter a description.</span>
            </td>
        </tr>
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='item_image'>Image Name</label>
            </td>
            <td class='inputTableRowAlt' id='item_image_td'>
                <input type='text' name='item[image]' id='item_image' maxlength='200' value='{$item.image}' /><br />
                <span class='validate textfieldRequiredMsg'>You must enter an image.</span>
            </td>
        </tr>
        {section name=index loop=$extra_fields}
        {cycle values='inputTableRow,inputTableRowAlt' assign=class}
        {assign var='field' value=$extra_fields[index]}
        <tr>
            <td class='inputTableRowAlt inputTableSubhead'>
                <label for='{$field.name}'>{$field.label}</label>
            </td>
            <td class='inputTableRowAlt' id='{$field.name}_td'>
                {include file='admin/items/_extra_field.tpl' field=$field value=$item[$field.name]}
            </td>
        </tr>
        {/section}
        <tr>
            <td class='inputTableRow' colspan='2' style='text-align: right;'> 
                <input type='submit' value='Save' />
            </td>
        </tr>
    </table>
</div>

{literal}
<script type='text/javascript'>
    var name = new Spry.Widget.ValidationTextField("item_name_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
    var descr = new Spry.Widget.ValidationTextarea('item_description_td');
    var image = new Spry.Widget.ValidationTextField("item_image_td", "none", {useCharacterMasking:true, validateOn:['change','blur']});    
</script>
{/literal}
