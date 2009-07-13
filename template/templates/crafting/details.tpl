<form action='{$display_settings.public_dir}/craft/{$item.id}' method='post'>
    <input type='hidden' name='state' value='craft' />

    <table class='dataTable' width='40%'>
        <tr>
            <td colspan='3' align='center' class='dataTableRow'>
                <p><img src='{$item.image}' alt='{$item.name}' /></p>
                <p style='font-weight: bold;'>{$item.name}</p>
                <p>{$item.description}</p>
            </td>
        </tr>
        <tr>
            <td class='dataTableRowAlt dataTableSubhead'>Produces</td>
            <td class='dataTableRowAlt'>{$product.quantity} {$product.name}</td>
            <td class='dataTableRowAlt' rowspan='3' align='center'>
                <img src='{$product.image}' alt='{$product.name}' />
            </td>
        </tr>
        <tr>
            <td class='dataTableRow dataTableSubhead'>You can make</td>
            <td class='dataTableRow'>{if $item.max_batch > 0}{$item.max_batch} Batch{if $item.max_batch != 1}es{/if}{else}<em>None</em>{/if}</td>
        </tr>
        <tr>
            <td class='dataTableRowAlt dataTableSubhead'>Create</td>
            <td class='dataTableRowAlt' id='quantity_td'>
                {if $item.unique_lock == 0}
                <input type='text' name='quantity' id='quantity' value='{$item.max_batch}' size='1' />
                {else}
                <em>Unique</em>
                {/if}
            </td>
        </tr>
        <tr>
            <td class='dataTableRow' colspan='2' align='right'>
                {if $item.unique_lock == 0}
                <input type='submit' value='Craft!' />
                {else}
                <em>Already owned.</em>
                {/if}
            </td>
            <td class='dataTableRow'>&nbsp;</td>
        </tr>
    </table>
</form>

<script type='text/javascript'>
{literal}
<!-- 
    var quantity = new Spry.Widget.ValidationTextField("quantity_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1, maxValue: {/literal}{$item.max_batch}{literal}});    
//-->
{/literal}
</script>

<br />
<table class='dataTable' width='40%'>
    <tr>
        <td class='dataTableHead' colspan='3'>Bill of Materials</td>
    </td>
    <tr>
        <td class='dataTableRow dataTableSubhead' align='center'>Item</td>
        <td class='dataTableRow dataTableSubhead' align='center'>Needed Qty</td>
        <td class='dataTableRow dataTableSubhead' align='center'>You Have</td>
    </tr>

    {section name='i' loop=$materials}
    {assign var=material value=$materials[i]}
    {cycle values='dataTableRowAlt,dataTableRow' assign=class}
    <tr>
        <td class='{$class}' align='center'>{$material.name}</td>
        <td class='{$class}' align='center'>{$material.need_quantity}</td>
        <td class='{$class}' align='center'>{$material.have_quantity}</td>
    </tr>
    {sectionelse}
    <tr>
        <td class='dataTableRow' colspan='3' align='center'><em>This recipe has no materials!</em></td>
    </tr>
    {/section}
</table>
