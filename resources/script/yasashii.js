/**
 * Useful javascript functions. 
 *
 * This file is part of 'KittoKittoKitto'.
 *
 * 'KittoKittoKitto' is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 3 of the License,
 * or (at your option) any later version.
 * 
 * 'KittoKittoKitto' is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE.  See the GNU General Public
 * License for more details.
 * 
 * You should have received a copy of the GNU General
 * Public License along with 'KittoKittoKitto'; if not,
 * write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package KittoKittoKitto
 * @version 1.0.0
 **/

/**
 * Dustin Diaz's most excellent getElementByClass.
 * 
 * @author Dustin Diaz
 * @copyright Dustin Diaz <dustin[AT]dustindiaz[DOT]com>
 * @link http://www.dustindiaz.com/getelementsbyclass/
 * @license CC-Attribution-Share Alike 2.5 <http://creativecommons.org/licenses/by-sa/2.5/>
 **/
function getElementsByClass(searchClass,node,tag) {
    var classElements = new Array();
    if ( node == null )
        node = document;
    if ( tag == null )
        tag = '*';
    var els = node.getElementsByTagName(tag);
    var elsLen = els.length;
    var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
    for (i = 0, j = 0; i < elsLen; i++) {
        if ( pattern.test(els[i].className) ) {
            classElements[j] = els[i];
            j++;
        }
    }
    return classElements;
} // end getElementByClass

function quotePlain(post_div_id,sTextarea_id) 
{
    div = document.getElementById(post_div_id);
    textarea = document.getElementById(sTextarea_id);

    window.location.hash = "post";
    textarea.value += "<blockquote>" + div.innerHTML + "</blockquote>\n\n";
    textarea.focus();
    setCaretToEnd(textarea);

    return true;
} // end quote

function quoteTinyMce(post_div_id,tinyMCE)
{
    div = document.getElementById(post_div_id);
    
    window.location.hash = "post";
    tinyMCE.execInstanceCommand('mce_editor_0','mceInsertContent',false,"<blockquote>" + div.innerHTML + "</blockquote>\n\n");
}

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

function tickAll(class_name)
{
    elements = getElementsByClass(class_name);

    for(i=0;i < elements.length;i++)
    {
        box = elements[i];
        
        if(box.checked == true) { box.checked = false; }
        else { box.checked = true; }
    } // end loop
    
    return true;
} // end tickAll

function addToField(container_id)
{
    maxTo = 5;
    button = document.getElementById('add_to');
    container = document.getElementById(container_id);
    if(container == null) return false;
    
    fields = getElementsByClass('to_field',container);
    if(fields.length >= maxTo) 
    {
        button.style.display = 'none'; 
        return false;
    }

    copy = fields[0].cloneNode(false);
    copy.value = ''; 

    if((fields.length + 1) == maxTo) button.style.display = 'none'; 

    br = document.createElement('BR');
    container.appendChild(br);
    container.appendChild(copy);

    return true;
} // end addToField

function imagePicker(file,url_base,image_id,hide_if_null)
{
    if(hide_if_null == null) hide_if_null = true;
    
    image = document.getElementById(image_id);
    if(image == null) return false;

    if(file == '')
    {
        if(hide_if_null == true) image.style.display = 'none';
        return true;
    }
    
    image.src = url_base + file;
    image.style.display = '';

    return true;
} // end avatarPicker

function setCaretToEnd(element) 
{
    var pos = element.value.length;
    if(element.createTextRange) 
    {
        var range = element.createTextRange();
        range.moveStart('character', pos);
        range.select();
    } 
    else if(element.setSelectionRange) 
    {
        element.setSelectionRange(pos, pos);
    }
} // end setCaretToEnd
