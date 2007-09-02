function quote(post_div_id,textarea_id) 
{
    div = document.getElementById(post_div_id);
    textarea = document.getElementById(textarea_id);

    window.location.hash = "post";
    textarea.value += "<blockquote>" + div.innerHTML + "</blockquote>\n\n";
    textarea.focus();
    setCaretToEnd(textarea);

    return true;
} // end quote

function doForumAdminConfirms(action)
{
    if(action == null) return false;
    
    confirmation_text = '';
    if(action == 'delete_post')
    {
         confirmation_text = 'Are you sure you wish to delete this post?';
    }
    else if(action == 'delete_thread')
    {
        confirmation_text = 'Are you sure you wish to delete this ENTIRE THREAD?';
    }
    else
    {
        return true;
    }

    return confirm(confirmation_text);
} // end doForumAdminConfirms
