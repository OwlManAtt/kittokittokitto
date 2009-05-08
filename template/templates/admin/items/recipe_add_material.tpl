<p>You are adding a component for the <strong>{$recipe.name}</strong> recipe.</p> 

<form action='{$display_settings.public_dir}/admin-recipe-add/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='item[id]' value='{$recipe.id}' />

    <div align='center'>
        <table class='inputTable' width='40%'>
            <tr>
                <td class='inputTableSubhead inputTableRow'>Item</td>
                <td class='inputTableRow' id='item_type_id_td'>
                    {include file='_widgets/item_search.tpl' name="item_type_id" id="item_type_id"} 
                </td>
            </tr>
            <tr>
                <td class='inputTableSubhead inputTableRow'>Quantity</td>
                <td class='inputTableRow' id='quantity_td'>
                    <input type='text' name='quantity' id='quantity' size='5' />
                    {literal}
                    <script type='text/javascript'>
                    <!--
                        var field_{/literal}{$i}{literal} = new Spry.Widget.ValidationTextField("quantity_td","integer", {useCharacterMasking:true, validateOn:['change','blur'], minValue: 1});
                    //-->
                    </script>
                    {/literal}

                </td>
            </tr>
            <tr>
                <td class='inputTableRow' colspan='2' style='text-align: right;'> 
                    <input type='submit' value='Save' />
                </td>
            </tr>
        </table>
    </div>
</form>
