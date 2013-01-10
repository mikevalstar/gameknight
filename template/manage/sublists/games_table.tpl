<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Players</th>
            <th>Length</th>
            <th>Setup</th>
            <th>Modifiers</th>
            <th>Weight</th>
            <th>Rating</th>
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
            <td>{if $row['game_weight'] > 1.00}{$row['game_weight']|x}{/if}</td>
            <td>{if $row['bgg_rating'] > 0}{$row['bgg_rating']|x}{/if}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
