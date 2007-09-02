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
