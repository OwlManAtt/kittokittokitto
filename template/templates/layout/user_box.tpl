<div id='user-box'>
    <ul id='user-info'>
        <li><strong>Username</strong>: {kkkurl link_text=$user->getUserName() slug='profile' args=$user->getUserId()}</li>
        <li><strong>{$currency_plural}</strong>: {$user->getCurrency()|number_format}</li>
        <li><strong>Pet</strong>: {if $active_pet == null}<em>None!</em>{else}{kkkurl link_text=$active_pet->getPetName() slug='pet' args=$active_pet->getUserPetId()}{/if}</li>
    </ul>
    <ul id='user-actions'>
        <li>{kkkurl link_text='Search' slug='search'}</li>
        <li>{kkkurl link_text='Preferences' slug='preferences'}</li>
        <li>{kkkurl link_text='Logout' slug='logoff'}</li>
    </ul>
</div>
