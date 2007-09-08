<p>Welcome to the site administration panel.</p>

<ul>
    {section name=index loop=$links}
    {assign var=link value=$links[index]}
    <li>{kkkurl link_text=$link.text slug=$link.slug}</li>
    {sectionelse}
    <li>You do not have access to any sections!</li>
    {/section}
</ul>
