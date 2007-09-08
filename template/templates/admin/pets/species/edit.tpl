<form action='{$display_settings.public_dir}/admin-pet-species-edit/' method='post'>
    <input type='hidden' name='state' value='save' />
    <input type='hidden' name='specie[id]' value='{$specie.id}' />
    
    {include file='admin/pets/species/_form.tpl'}
</form>
