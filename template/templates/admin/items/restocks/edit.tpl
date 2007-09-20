<form action='{$display_settings.public_dir}/admin-restock-edit/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='item[id]' value='{$item.id}' />
    <input type='hidden' name='restock[id]' value='{$restock.id}' />
    
    {include file='admin/items/restocks/_form.tpl'}
</form>
