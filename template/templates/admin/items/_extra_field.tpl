{if $field.type == 'text'}
<input type='text' name='extra[{$field.name}]' id='{$field.name}' maxlength='{$field.max_length}' value='{$value}' size='{$field.size}' /><br />
<span class='validate textfieldRequiredMsg'>You must enter a value.</span>
<span class='validate textfieldMinValueMsg'>You must enter a positive number.</span>

{literal}
<script type='text/javascript'>
    var field = new Spry.Widget.ValidationTextField({/literal}"{$field.name}_td"{literal}, {/literal}"{$field.validation_type}"{literal}, {useCharacterMasking:true, validateOn:['change','blur']{/literal}{if $field.validation_type == 'integer'}, minValue: 1{/if}{literal}});
</script>
{/literal}
{elseif $field.type == 'select'}
{html_options name="extra[`$field.name`]" id=$field.name options=$field.values selected=$value}<br />
<span class='validate selectRequiredMsg'>You must pick an option.</span>

{literal}
<script type='text/javascript'>
    var field = new Spry.Widget.ValidationSelect({/literal}"{$field.name}_td"{literal},{ validateOn:['change','blur'], invalidValue: '0'});
</script>
{/literal}
{elseif $field.type == 'item'}
{include file='_widgets/item_search.tpl' name="extra[`$field.name`]" value="`$value`" id="`$field.name`"}
{/if}
