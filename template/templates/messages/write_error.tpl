<form action='{$display_settings.public_dir}/write-new-message/' method='post'>
    <input type='hidden' name='error' value='true' />
    {foreach from=$to item=to_user}
    <input type='hidden' name='to[]' value='{$to_user}' />
    {/foreach}
    <input type='hidden' name='message[title]' value='{$message.title}' />
    <input type='hidden' name='message[body]' value='{$message.body}' />
    <input type='submit' value='Go Back' />
</form>
