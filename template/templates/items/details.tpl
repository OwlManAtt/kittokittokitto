<div align='center'>
    <div class='item-box' style='float: none;'>
        <div align='center'>
            <p><img src='{$item.image}' border='0' alt='{$item.name}' /></p>
            <p style='font-weight: bold;'>{$item.name}: {$item.description}</p>
        </div>
        
        <form action='{$display_settings.public_dir}/item' method='get' {literal}onSubmit='if(this.dd[this.dd.selectedIndex].value == "destroy") { return confirm("Are you sure you want to destroy this?"); }'{/literal}>
            <input type='hidden' name='state' value='action' />
            <input type='hidden' name='action[item_id]' value='{$item.id}' />
        
            <table width='100%' border='0'>
                <tr>
                    <td>
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
