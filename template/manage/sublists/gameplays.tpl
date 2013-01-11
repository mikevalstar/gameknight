<table class="table table-striped">
    <thead>
        <tr>
            <th>When</th>
            <th>Length</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$results item=row}
        <tr class="linkrow" data-link="/plays/{$row['game_play_pk']}">
            <td>When</td>
            <td>Length</td>
        </tr>
        {/foreach}
    </tbody>
</table>

<h3>Enter a Play</h3>

<form method="post" action="/plays/new">
<div class="row">
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="game_fk" value="{$game->id}" />
    <div class="span4">
        <div class="control-group">
            <label class="control-label" for="started">Started Date</label>
            <div class="controls">
                <input type="text" id="started" name="started" class="span2 datepicker required" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="started_time">Started Time</label>
            <div class="controls">
                <input type="text" id="started_time" name="started_time" class="span2 timepicker required" value="" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="playtime">Length</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="playtime" name="playtime" style="width:4em" value="" />
                    <span class="add-on">Min</span>
                </div>
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
                {for $x=1 to $game->max_players}
                <tr>
                    <td>
                        <input type="hidden" id="user{$x}" name="user_fk[]" value="" />
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