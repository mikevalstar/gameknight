{function pager_link_builder}
?rows={$rows}&page={$page}&filter={$filter}&orderby={$orderby}&direction={$direction}
{/function}

{assign "pager_start" max($currentpage - 3, 1)}
{assign "pager_end" min($pager_start + 6, $pagecount)}
{if $pager_end - $pager_start < 6 && $pager_start > 1}
    {assign "pager_start" max($pager_start - 6 + ($pager_end - $pager_start), 1)}
{/if}


<div class="table-search-toolbar">
    <div class="pagination pagination-centered">
        <ul>
            {if $currentpage > 1}
            <li><a href="{pager_link_builder page=$currentpage - 1}">&laquo; Prev</a></li>
            {else}
            <li class="disabled"><a href="#">&laquo; Prev</a></li>
            {/if}
            
            {if $pager_start > 1}
                <li class="disabled"><a href="#">...</a></li>
            {/if}
            
            {for $pg = $pager_start to $pager_end}
                {if $currentpage == $pg}
                    <li class="disabled"><a href="#">{$pg}</a></li>
                {else}
                    <li><a href="{pager_link_builder page=$pg}">{$pg}</a></li>
                {/if}
            {/for}
            
            {if $pager_end < $pagecount}
                <li class="disabled"><a href="#">...</a></li>
            {/if}
            
            {if $currentpage >= $pagecount}
            <li class="disabled"><a href="#">Next &raquo;</a></li>
            {else}
            <li><a href="{pager_link_builder page=$currentpage + 1}">Next &raquo;</a></li>
            {/if}
        </ul>
        <span class="records-count">{max(($currentpage - 1) * $rows + 1, 0)} &ndash; {min($rowcount, $rows * $currentpage)} of {$rowcount}</span>
    </div>
</div>
