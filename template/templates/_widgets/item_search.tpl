<input type='text' id='{$id}' name='dummy_{$name}' value='{$value[1]}' />
<input type='hidden' id='dummy_{$id}' name='{$name}' value='{$value[0]}' />

{literal}
<script type='text/javascript'>
<!--
    $(document).ready(function(){
        var data = '{/literal}{$display_settings.public_dir}{literal}/item-search-ajax';
        $("#{/literal}{$id}{literal}").autocomplete(data,{
            max: 10,
            mustMatch: true,
            formatItem: function(data, i, n, value) 
            {
                return "<img src='" + data[1].split('-_-')[1] + "' alt='' height='14' width='15' />&nbsp;" + data[1].split('-_-')[0];
            },
            formatResult: function(data, value) 
            {
                return data[1].split('-_-')[0];
            }
        });
    });

    $("#{/literal}{$id}{literal}").result(function(event, data, formatted) {
        var hidden = $('#dummy_{/literal}{$id}{literal}');
        hidden.val(data[0]);
    });

    var field = new Spry.Widget.ValidationTextField({/literal}"{$id}_td"{literal}, 'none', {useCharacterMasking:true, validateOn:['change','blur']});
    //-->
</script>
{/literal}

