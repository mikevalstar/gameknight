{extends 'layout.tpl'}
{block name="content"}
{assign 'game' $play->game()}

<h2 class="pull-left">Play {$play->id}</h2>

<div class="btn-group pull-right">
    <form id="form_game_delete" method="post">
        <input type="hidden" name="action" value="delete" />
        <button class="btn btn-danger" data-submitfor="#form_game_delete"><i class="icon-trash icon-white"></i> Delete</button>
    </form>
</div>

<div class="clearfix"></div>

<div class="row">
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="inputNotes">Game</label>
            <div class="controls">
                {$game->game_name}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="started">Started</label>
            <div class="controls">
                {$play->started|extract_date} {$play->started|extract_time}
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="playtime">Length</label>
            <div class="controls">
                {$play->playtime} Min
            </div>
        </div>
    </div>
    <div class="span8">
        <table class="table">
            <thead>
                <th>Player</th>
                <th>Score</th>
                <th>Outcome</th>
            </thead>
            <tbody>
                {foreach from=$play->playerslist() item=row}
                <tr>
                    <td>{$row['name_first']|x} {$row['name_last']|x}</td>
                    <td>{$row['score']}</td>
                    <td>{if $row['win'] == 1}Win{else}Loss{/if}</td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>


{/block}