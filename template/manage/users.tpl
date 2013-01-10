{extends 'layout.tpl'}
{block "navbar-top"}
    <form class="form-search navbar-form pull-right" method="get" action="/users">
        <div class="input-append">
            <input type="text" name="filter" class="input-medium search-query" placeholder="Search Users" />
            <button class="btn" type="submit">Search</button>
        </div>
    </form>
{/block}
{block name="content"}

<h2 class="pull-left">Users</h2>
<div class="btn-group pull-right">
    <a class="btn" href="/users"><i class="icon-list"></i> List</a>
    <a class="btn" href="/users/new"><i class="icon-plus"></i> Add</a>
</div>
<div class="clearfix"></div>

<table class="table table-striped">
    <thead>
        <tr>
            {include file="common/searchheader.tpl" title="Name" col="name_last" baselink="/users" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Email" col="email" baselink="/users" orderby=$orderby direction=$direction filter=$filter}
            {include file="common/searchheader.tpl" title="Phone" col="phone" baselink="/users" orderby=$orderby direction=$direction filter=$filter}
        </tr>
    </thead>
    <tbody>
        {foreach from=$results item=row}
        <tr class="linkrow" data-link="/users/{$row['user_pk']}/{$row['name_first']|prettyurlencode}_{$row['name_last']|prettyurlencode}">
            <td>{$row['name_first']|x} {$row['name_last']|x}</td>
            <td>{$row['email']|x}</td>
            <td>{$row['phone']|x}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
{include file='common/pager.tpl' currentpage=$currentpage pagecount=$pagecount rows=$rows rowcount=$rowcount orderby=$orderby direction=$direction filter=$filter}
{/block}
