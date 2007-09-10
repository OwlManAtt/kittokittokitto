<div align='center'>
    <div class='item-box' style='float: none;'>
        <div align='center'>
            <p><img src='{$item.image}' border='0' alt='{$item.name}' /></p>
            <p style='font-weight: bold;'>{$item.name}: {$item.description}</p>
        </div>
        
        <form action='{$display_settings.public_dir}/item' method='get' onSubmit='return runSpryValidationsToo(this.dd[this.dd.selectedIndex].value,this);'>
            <input type='hidden' name='state' value='action' />
            <input type='hidden' name='action[item_id]' value='{$item.id}' />
        
            <table width='100%' border='0'>
                <tr>
                    <td id='action_td'>
                        {html_options name='action[type]' id='dd' options=$item.actions}
                    </td>
                    <td>
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
</script>
{/literal}
