{section name=pet loop=$species}
    {include file='pets/_createpet_box.tpl' pet=$species[pet]}
{sectionelse}
<p>Ah...there appear to be no pets available!</p>
{/section}
