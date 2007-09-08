<form action='{$display_settings.public_dir}/new-thread/' method='post'>
    <input type='hidden' name='board_id' value='{$post.board_id}' />
    <input type='hidden' name='error[title]' value='{$post.title}' />
    <input type='hidden' name='error[body]' value='{$post.message}' />
    <input type='submit' value='Go Back' />
</form>
