<div align='center'>
    <h3>{$shop.name}</h3>
    <img src='{$shop.image}' alt='{$shop.name}' border='0' />
    <p>{$shop.welcome}</p>
</div>

{if $notice != ''}<div id='notice' class='fade'>{$notice}</div>{/if}

<hr />

{section name=index loop=$items}
{assign var='item' value=$items[index]}
<div class='item-box' align='center'>
    <p><img src='{$item.image}' alt='{$item.name}' border='0' /></p>
    
    <div class='item-box-detail' style='text-align: center;'> 
        <p style='font-weight: bold;'>{$item.name}</p>
        <p><strong>Price</strong>: {$item.price|number_format}</p>
        <p><strong>Supply</strong>: {$item.quantity|number_format}</p>
    </div>
    
    <form action='{$display_settings.public_dir}/shop/{$shop.id}' method='post'>
        <input type='hidden' name='state' value='buy' />
        <input type='hidden' name='stock_id' value='{$item.id}' />
    
        <table class='inputTable' width='95%' style='font-size: small;'>
            <tr>
                <td class='inputTableRow inputTableSubhead'>
                    <label for='item_{$item.id}'>Quantity</label>
                </td>
                <td class='inputTableRow' id='item_{$item.id}_td'>
                    <input type='text' size='4' name='item[quantity]' id='item_{$item.id}' value='1' />
                </td>
            </tr>
            <tr>
                <td colspan='2' class='inputTableRowAlt' style='text-align: center;'>
                    <input type='submit' value='Buy' />
                </td>
            </tr>
        </table>

        {literal}
        <script type='text/javascript'>
            var item_{/literal}{$item.id} = new Spry.Widget.ValidationTextField("item_{$item.id}{literal}_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1, maxValue: {/literal}{$item.quantity}{literal}});    

        </script>
        {/literal}
    </form>
</div>
{sectionelse}
<p align='center'><em>This store's good have sold out. Check back soon!</em></p>
{/section}
