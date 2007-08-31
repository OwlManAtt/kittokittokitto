<p>There are a number of excellent merchants in town. Where would you like to shop?</p>

{* There's probably a better UI for this...the classic image map of a town *}
{* is better suited than a list, but I don't have the artistic talent for it. *}
{if $shops_available == 1}<ul>{/if}
{section name=shop loop=$shops}
    <li>{kkkurl slug='shop' link_text=$shops[shop].name args=$shops[shop].id}</li>
{sectionelse}
<p><em>There are no merchants selling goods at this time.</em></p>
{/section}
{if $shops_available == 1}</ul>{/if}
