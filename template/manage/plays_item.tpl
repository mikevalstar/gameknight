{extends 'layout.tpl'}
{block name="content"}
{assign 'game' $play->game()}

<h2 class="pull-left">Play {$play->id}</h2>

<h1>DO NOT USE THIS INPUT METHOD, CURRENTLY BROKEN, ENTER FROM GAME PAGE</h1>

{if !$play->isnew()}
<div class="btn-group pull-right">
    <form id="form_game_delete" method="post">
        <input type="hidden" name="action" value="delete" />
        <button class="btn btn-danger" data-submitfor="#form_game_delete"><i class="icon-trash icon-white"></i> Delete</button>
    </form>
</div>
{/if}

<div class="clearfix"></div>

<form method="post">
<input type="hidden" name="action" value="save" />
<input type="hidden" name="game_fk" id="game_fk" value="{if $game}{$game->id}{/if}" />
<div class="row">
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="inputNotes">Game</label>
            <div class="controls">
                <input type="text" name="parent" class="fillgamename span4 required" data-idfill="#game_fk" value="{if $game}{$game->game_name}{/if}" placeholder="Game Name - Required" autocomplete="off" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="started">Started Date</label>
            <div class="controls">
                <input type="text" id="started" name="started" class="span2 datepicker required" value="{$play->started|extract_date}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="started_time">Started Time</label>
            <div class="controls">
                <input type="text" id="started_time" name="started_time" class="span2 timepicker required" value="{$play->started|extract_time}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="playtime">Length</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="playtime" name="playtime" style="width:4em" value="{$play->playtime}" />
                    <span class="add-on">Min</span>
                </div>
            </div>
        </div>
    </div>
    <div class="span8">
        {if $game}
        <table class="table">
            <thead>
                <th>Player</th>
                <th>Score</th>
                <th>Outcome</th>
            </thead>
            <tbody>
                {for $x=1 to $game->max_players}
                <tr>
                    <td>
                        <input type="hidden" id="user{$x}" name="user_fk[]" value="{$game->parent_fk}" />
                        <input type="text" id="player{$x}" name="player[]" class="filluseremail" data-idfill="#user{$x}" autocomplete="off" value="" />
                    </td>
                    <td><input type="text" id="score{$x}" name="score[]" value="" /></td>
                    <td>
                        <select name="win[]">
                            <option value="1">Win</option>
                            <option value="0">Loss</option>
                        </select>
                    </td>
                </tr>
                {/for}
            </tbody>
        </table>
        {/if}
    </div>
</div>
<div class="row">
    <div class="span12">
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
</div>
</form>

{/block}