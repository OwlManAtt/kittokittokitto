{* Shouldn't be a post...but it's the quick-n-dirty way to prevent someone *}
{* from clicking a malicious link and not noticing their profile says "I am *}
{* a plonker" before saving it. *}
<form action='' method='post'>
    <input type='hidden' name='default[gender]' value='{$info.gender}' />
    <input type='hidden' name='default[age]' value='{$info.age}' />
    <input type='hidden' name='default[editor]' value='{$info.editor}' />
    <input type='hidden' name='default[profile]' value='{$info.profile}' />
    <input type='hidden' name='default[signature]' value='{$info.signature}' />
    <input type='hidden' name='default[avatar_id]' value='{$info.avatar_id}' />
    <input type='hidden' name='default[timezone]' value='{$info.timezone}' />
    <input type='hidden' name='default[datetime_format]' value='{$info.datetime_format}' />
    <input type='hidden' name='default[show_online_status]' value='{$info.show_online_status}' />
    <input type='submit' value='Go back' />
</form>
