<p class='page-subnav'>{kkkurl link_text='Inventory' slug='items'} | Crafting</p>

{if $notice != ''}<div id='item-notice' class='{$fat} notice-box'>{$notice}</div>{/if}
<table class='dataTable' width='85%'>
    <tr>
        <td class='dataTableSubhead'>&nbsp;</td>
        <td class='dataTableSubhead' align='center'>Recipe</td>
        <td class='dataTableSubhead' align='center'>QuickCraft</td>
    </tr>
    {section name=i loop=$inventory}
    {assign var=item value=$inventory[i]}
    {cycle values='dataTableRow,dataTableRowAlt' assign=class}
    <tr>
        <td class='{$class}' align='center'>
            <img src='{$item.image}' alt='{$item.name}' />
        </td>
        <td class='recipe-desc {$class}'>
            <p class='recipe-name'>{kkkurl link_text="`$item.name`" slug='craft' args=$item.id}</p>
            {$item.description}
        </td>
        <td class='{$class}' id='qc_{$item.id}_td' style='text-align: center; vertical-align: center;'> 
            {if $item.craftable == 1}
            {if $item.can_make_batch == 0}<em>Insufficient materials</em>{else}
            <form action='{$display_settings.public_dir}/craft/{$item.id}' method='post' onSubmit='return confirm("Are you sure you want to craft this?");'>
                <input type='hidden' name='state' value='craft' />
                <input type='hidden' name='id' value='{$item.id}' />
                Qty <input type='text' name='quantity' value='{$item.max_batch}' size='1' /><br />
                <input type='submit' value='Make' />
            </form>

            <script type='text/javascript'>
            {literal}
            <!-- 
                var quantity = new Spry.Widget.ValidationTextField("qc_{/literal}{$item.id}{literal}_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1, maxValue: {/literal}{$item.max_batch}{literal}});    
            //-->
            {/literal}
            </script>
            {/if}
            {else}
            <em>Unique</em><br />
            <em>Already owned.<em>
            {/if}
        </td>
    </tr>
    {sectionelse}
    <tr>
        <td class='inputTableRow' colspan='3' style='text-align: center;'>
            <p><em>You do not own any crafting patterns!</em></p>
        </td>
    </tr>
    {/section}
</table>

<br clear='all'>
<div class='pages'>{$pagination}</div>
