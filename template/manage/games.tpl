{extends 'layout.tpl'}
{block "navbar-top"}
    <form class="form-search navbar-form pull-right">
        <div class="input-append">
            <input type="text" name="filter" class="input-medium search-query" placeholder="Search Games" />
            <button class="btn" type="submit">Search</button>
        </div>
    </form>
{/block}
{block name="content"}
<h2 class="pull-left"><i class="icon-d20"></i> Games</h2>
<div class="btn-group pull-right">
    <a class="btn" href="/games"><i class="icon-list"></i> List</a>
    <a class="btn" href="/games/new"><i class="icon-plus"></i> Add</a>
</div>
<div class="clearfix"></div>
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            {include file="common/searchheader.tpl" title="Name" col="game_name" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Players" col="min_players" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Length" col="min_avg_len" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Setup" col="setup_time" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Modifiers" nolink=true}
            {include file="common/searchheader.tpl" title="Owners" col="owners_txt" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Weight" col="game_weight" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Rating" col="bgg_rating" baselink="/games" orderby=$orderby direction=$direction filter=$filter}
        </tr>
    </thead>
    <tbody>
        {foreach from=$results item=row}
        <tr class="linkrow" data-link="/games/{$row['game_pk']}/{$row['game_name']}">
            <td>{if $row['parent_fk'] != ''}<a title="This is a game expansion" class="tt" href="#"><i class="icon-fullscreen"></i></a>{/if} {$row['game_name']|x}</td>
            <td>{if $row['min_players'] != 0 || $row['max_players'] != 0}
                    {$row['min_players']|x} - {$row['max_players']|x}
                {/if}</td>
            <td>
                {if $row['min_avg_len'] != 0 || $row['max_avg_len'] != 0}
                {$row['min_avg_len']|x} - {$row['max_avg_len']|x} min
                {/if}
            </td>
            <td>
                {if $row['setup_time'] != 0}
                {$row['setup_time']|x} min
                {/if}
            </td>
            <td>
                {if $row['coop'] == 1}<a title="Co-Op Game" class="tt" href="#"><i class="icon-cogs icon-large"></i></a>{/if}
                {if $row['team'] == 1}<a title="Team Game" class="tt" href="#"><i class="icon-group icon-large"></i></a>{/if}
                {if $row['preorder'] == 1}<a title="The game is currently on pre-order" class="tt" href="#"><i class="icon-fighter-jet icon-large"></i></a>{/if}
            </td>
            <td>{$row['owners_txt']|x}</td>
            <td>{if $row['game_weight'] > 1.00}{$row['game_weight']|x}{/if}</td>
            <td>{if $row['bgg_rating'] > 0}{$row['bgg_rating']|x}{/if}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include file='common/pager.tpl' currentpage=$currentpage pagecount=$pagecount rows=$rows rowcount=$rowcount orderby=$orderby direction=$direction filter=$filter}
{/block}
