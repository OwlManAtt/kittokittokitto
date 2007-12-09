<div align='center'>
    <div class='item-box' style='float: none;'>
        <div align='center'>
            <p><img src='{$item.image}' border='0' alt='{$item.name}' /></p>
            <p style='font-weight: bold;'>{$item.quantity} {$item.name}</p>
            <p>{$item.description}</p>
        </div>
        
        <form action='{$display_settings.public_dir}/item' method='get' onSubmit='return runSpryValidationsToo(this.dd[this.dd.selectedIndex].value,this);'>
            <input type='hidden' name='state' value='action' />
            <input type='hidden' name='action[item_id]' value='{$item.id}' />
        
            <table class='inputTable'> 
                <tr>
                    <td class='inputTableRow inputTableSubhead'>
                        <label for='dd'>Action</label>
                    </td>
                    <td class='inputTableRow' id='action_td'>
                        {html_options name='action[type]' id='dd' options=$item.actions}
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRowAlt inputTableSubhead'>
                        <label for='action_quantity'>Quantity</label>
                    </td>
                    <td class='inputTableRow' id='action_quantity_td'>
                        <input type='text' name='action[quantity]' id='action_quantity' size='4' value='1' />
                    </td>
                </tr>
                <tr>
                    <td class='inputTableRow' colspan='2' style='text-align: right;'> 
                        <input type='submit' value='Go' />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

{literal}
<script type='text/javascript'>
    function runSpryValidationsToo(value,form) 
    { 
        if(Spry.Widget.Form.validate(form) == false) return false;
        
        if(value == "destroy") 
        { 
            return confirm("Are you sure you want to destroy this?"); 
        } 
    } // end anon

    var dd = new Spry.Widget.ValidationSelect('action_td',{validateOn:['change']});
    var quantity = new Spry.Widget.ValidationTextField("action_quantity_td", "integer", {useCharacterMasking:true, validateOn:['change','blur'], allowNegative: false, minValue: 1, maxValue: {/literal}{$item.quantity}{literal}});    
</script>
{/literal}
