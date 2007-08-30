<div id='user-box'>
    <ul id='user-info'>
        <li><strong>Username</strong>: {kkkurl link_text=$user->getUserName() slug='profile' args=$user->getUserId()}</li>
        <li><strong>{$currency_plural}</strong>: {$user->getCurrency()|number_format}</li>
        <li><strong>Active Pet</strong>: {if $active_pet == null}<em>None!</em>{else}{$active_pet->getPetName()}{/if}</li>
    </ul>
    <ul id='user-actions'>
        <li>{kkkurl link_text='Preferences' slug='preferences'}</li>
        <li>{kkkurl link_text='Logout' slug='logoff'}</li>
    </ul>
</div>
