{extends 'layout.tpl'}
{block "navbar-top"}{/block}
{block name="content"}

<h2>KG Game Knight</h2>


<div class="row">
    <div class="span4">
        <h3><i class="icon-calendar"></i> Upcoming Events</h3>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event</th>                    
                    <th>Start</th>
                    <th>People</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$upcoming item=row}
                <tr class="linkrow" data-link="/events/{$row['event_pk']}/{$row['event_name']|prettyurlencode}">
                    <td class="b">{$row['event_name']|x}</td>
                    <td>{$row['event_start']|x}</td>
                    <td>{$row['participants']}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <div class="span4">
        <h3><i class="icon-d20"></i> New Games</h3>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Game</th>                    
                    <th>Owners</th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$newgames item=row}
                <tr class="linkrow" data-link="/games/{$row['game_pk']}/{$row['game_name']}">
                    <td>{$row['game_name']|x}</td>
                    <td>{$row['owners_txt']|x}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>

{/block}
