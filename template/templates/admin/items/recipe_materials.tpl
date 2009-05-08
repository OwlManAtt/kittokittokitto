<p>You are modifying materials for the <strong>{$recipe.name}</strong> recipe.</p> 
{if $notice != ''}<div id='notice' class='fade'>{$notice}</div>{/if}

<form action='{$display_settings.public_dir}/admin-recipe/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='item[id]' value='{$recipe.id}' />

    <div align='center'>
        <table class='inputTable' width='30%' id='materials_list'>
            <tr id='materials_header'>
                <td class='dataTableSubhead' width='5%'>Remove?</td>
                <td class='dataTableSubhead'>Item</td>
                <td class='dataTableSubhead'>Quantity</td>
            </tr>
            {section name=index loop=$materials}
            {assign var=material value=$materials[index]}
            {assign var=i value=$smarty.section.index.index}
            <tr id='material_{$i}_row'>
                <td class='dataTableRow' id='material_{$i}_delete_td'>
                    <input type='hidden' name='material[{$i}][material_id]' value='{$material.id}'
                    <input type='checkbox' name='material[{$i}][delete]' value='1' />
                </td>
                <td class='dataTableRow' id='material_{$i}_td'>
                    {include file='_widgets/item_search.tpl' name="material[`$i`][id]" value=$material.item id="material_`$i`"}
                </td>
                <td class='dataTableRow' id='material_{$i}_quantity_td'>
                    <input type='text' name='material[{$i}][quantity]' value='{$material.quantity}' size='5' />
                    {literal}
                    <script type='text/javascript'>
                    <!--
                        var field_{/literal}{$i}{literal} = new Spry.Widget.ValidationTextField({/literal}"material_{$i}_quantity_td"{literal},"integer", {useCharacterMasking:true, validateOn:['change','blur'], minValue: 1});
                    //-->
                    </script>
                    {/literal}
                </td>
            </tr>
            {sectionelse}
            <tr>
                <td class='dataTableRow' colspan='3' align='center'>
                    <em>There are no materials.</em>
                </td>
            </tr>
            {/section}
            <tr>
                <td class='inputTableRow' colspan='2' style='text-align: left;'>
                    <input type='button' value='Add Item' onClick='window.location.href="{$display_settings.public_dir}/admin-recipe-add/?item[id]={$recipe.id}"' />
                </td>
                <td class='inputTableRow' colspan='1' style='text-align: right;'> 
                    <input type='submit' value='Save' />
                </td>
            </tr>
        </table>
    </div>
</form>
