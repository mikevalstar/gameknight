{extends 'layout.tpl'}
{block "navbar-top"}
{/block}
{block name="content"}

<h2 class="pull-left">Game Plays</h2>
<div class="btn-group pull-right">
    <a class="btn" href="/plays"><i class="icon-list"></i> List</a>
    <!-- <a class="btn" href="/plays/new"><i class="icon-plus"></i> Add</a> -->
</div>
<div class="clearfix"></div>

<table class="table table-striped">
    <thead>
        <tr>
            {include file="common/searchheader.tpl" title="Game" col="game_name" baselink="/plays" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Played" col="started" baselink="/plays" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Duration" col="playtime" baselink="/plays" orderby=$orderby direction=$direction filter=$filter}
        </tr>
    </thead>
    <tbody>
        {foreach from=$results item=row}
        <tr class="linkrow" data-link="/play/{$row['play_pk']}/{$row['game_name']|prettyurlencode}">
            <td>{$row['game_name']|x}</td>
            <td>{$row['started']|x}</td>
            <td>{$row['playtime']|x} Min</td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include file='common/pager.tpl' currentpage=$currentpage pagecount=$pagecount rows=$rows rowcount=$rowcount orderby=$orderby direction=$direction filter=$filter}
{/block}
