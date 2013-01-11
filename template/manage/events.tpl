{extends 'layout.tpl'}
{block "navbar-top"}
    <form class="form-search navbar-form pull-right">
        <div class="input-append">
            <input type="text" name="filter" class="input-medium search-query" placeholder="Search Events" />
            <button class="btn" type="submit">Search</button>
        </div>
    </form>
{/block}
{block name="content"}
<h2 class="pull-left">Events</h2>
<div class="btn-group pull-right">
    <a class="btn" href="/events"><i class="icon-list"></i> List</a>
    <a class="btn" href="/events/new"><i class="icon-plus"></i> Add</a>
</div>
<div class="clearfix"></div>
<table class="table table-striped">
    <thead>
        <tr>
            {include file="common/searchheader.tpl" title="Name" col="event_name" baselink="/events" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Start" col="event_start" baselink="/events" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Participants" col="participants" baselink="/events" orderby=$orderby direction=$direction filter=$filter}
        </tr>
    </thead>
    <tbody>
        {foreach from=$results item=row}
        <tr class="linkrow" data-link="/events/{$row['event_pk']}/{$row['event_name']|prettyurlencode}">
            <td class="b">{$row['event_name']|x}</td>
            <td>{$row['event_start']|x}</td>
            <td>{$row['participants']}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include file='common/pager.tpl' currentpage=$currentpage pagecount=$pagecount rows=$rows rowcount=$rowcount orderby=$orderby direction=$direction filter=$filter}
{/block}
