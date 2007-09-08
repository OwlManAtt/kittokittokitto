<form action='{$display_settings.public_dir}/admin-pet-colors-edit/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='color[id]' value='{$color.id}' />
    
    {include file='admin/pets/colors/_form.tpl'}
</form>
