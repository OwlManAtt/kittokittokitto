<form action='{$display_settings.public_dir}/admin-items-edit/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='item[id]' value='{$item.id}' />
    
    {include file='admin/items/_form.tpl'}
</form>
