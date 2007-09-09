<form action='{$display_settings.public_dir}/admin-shops-edit/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='shop[id]' value='{$shop.id}' />
    
    {include file='admin/shops/_form.tpl'}
</form>
