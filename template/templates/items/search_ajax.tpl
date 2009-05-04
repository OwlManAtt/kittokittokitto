{section name=item_index loop=$items}
{assign var='item' value=$items[item_index]}
{$item.id}|{$item.name}-_-{$item.image_url}
{/section}
