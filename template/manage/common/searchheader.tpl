{function sh_link_builder}
{$baselink}?filter={$filter}&orderby={$reorder}&direction={$dir}
{/function}


{if isset($nolink)}
    <th>{$title}</th>
{else}

    {if $orderby == $col && $direction == "desc"}
    <th class="linkcol" data-link="{sh_link_builder reorder=$col dir="asc"}">
    {else}
    <th class="linkcol" data-link="{sh_link_builder reorder=$col dir="desc"}">
    {/if}
        {$title}
        {if $orderby == $col && $direction == "desc"}
        <i class="icon icon-chevron-down"></i>
        {else if $orderby == $col}
        <i class="icon icon-chevron-up"></i>
        {/if}
    </th>
    
{/if}