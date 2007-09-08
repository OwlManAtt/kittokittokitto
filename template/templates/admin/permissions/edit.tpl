<form action='{$display_settings.public_dir}/admin-permissions-edit/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='group[id]' value='{$group.id}' />
    
    {include file='admin/permissions/_form.tpl'}
</form>
