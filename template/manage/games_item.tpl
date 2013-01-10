{extends 'layout.tpl'}
{block name="content"}

{if !$game->isnew()}
<form id="iownthis" method="post">
    <input type="hidden" name="action" value="{if $U->iown($game->id)}remove_owner{else}add_owner{/if}" />
    <input type="hidden" name="user_pk" value="{$U->id}" />
</form>
{/if}

{if !$game->isnew()}
    {assign 'bgg' $game->bgg()}
{/if}

<div class="row">
    <div class="span12">
        <h2 class="pull-left">{$game->game_name|x}</h2>
        
        {if !$game->isnew()}
        <div class="btn-group pull-right">
            <form id="form_game_delete" method="post">
                <input type="hidden" name="action" value="delete" />
                <button class="btn btn-danger" data-submitfor="#form_game_delete"><i class="icon-trash icon-white"></i> Delete</button>
            </form>
        </div>
        {/if}
        
        <div class="clearfix"></div>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#game" data-toggle="tab">Game</a></li>
            <li><a href="#owners" data-toggle="tab">Owners</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="game">
                <form method="post">
                    <input type="hidden" name="action" value="save" />
                    <div class="row">
                        <div class="span8">
                            {if isset($bgg) && $bgg}
                                <div class="pull-right">
                                    <img src="{$bgg['thumbnail']}" />
                                </div>
                            {/if}
                            <div class="control-group">
                                <label class="control-label" for="game_name">Name</label>
                                <div class="controls">
                                    <input type="text" id="game_name" name="game_name" class="span5 required" value="{$game->game_name|x}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputNotes">Parent Game</label>
                                <div class="controls">
                                    <input type="hidden" id="parent_fk" name="parent_fk" value="{$game->parent_fk}" />
                                    <input type="text" name="parent" class="fillgamename span4" data-idfill="#parent_fk" value="{if $game->parent_fk != ''}{$game->parent()->game_name}{/if}" placeholder="Game Name - This marks the game as an expansion" autocomplete="off" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputNotes">Notes</label>
                                <div class="controls">
                                    <textarea id="inputNotes" class="span8" name="notes" rows="20">{$game->notes|x}</textarea>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputOwnerstxt">Owners</label>
                                <div class="controls">
                                    {if !$game->isnew()}
                                    <a href="#" class="btn {if $U->iown($game->id)}btn-success{/if}" data-submitfor="#iownthis"><i class="icon-ok"></i> I Own This</a> 
                                    {/if}
                                    {$game->owners_txt|x}
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" for="bgg_id">BGG Game ID</label>
                                <div class="controls">
                                    <input type="text" id="bgg_id" name="bgg_id" class="span1" value="{$game->bgg_id|x}" /> 
                                    <br/> Weight: {$game->game_weight} 
                                    <br/> Rating: {$game->bgg_rating}
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_min_players">Players</label>
                                <div class="controls">
                                    <input type="text" id="game_min_players" name="min_players" class="span1" value="{$game->min_players|x}" />
                                    -
                                    <input type="text" id="game_max_players" name="max_players" class="span1" value="{$game->max_players|x}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_min_avg_len">Game Length</label>
                                <div class="controls">
                                    <div class="input-append span1" style="margin-left: 0;">
                                        <input type="text" id="game_min_avg_len" name="min_avg_len" style="width:3em" value="{$game->min_avg_len|x}" />
                                        <span class="add-on">Min</span>
                                    </div>
                                    <div class="input-append span1">
                                        <input type="text" id="game_max_avg_len" name="max_avg_len" style="width:3em" value="{$game->max_avg_len|x}" />
                                        <span class="add-on">Min</span>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_setup_time">Setup Time</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input type="text" id="game_setup_time" name="setup_time" style="width:3em" value="{$game->setup_time|x}" />
                                        <span class="add-on">Min</span>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_scoring_type">Scoring Type</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <select id="game_scoring_type" name="game_scoring_type">
                                            <option value="1" {if $game->scoring_type == 1}selected="selected"{/if}>Scoring</option>
                                            <option value="2" {if $game->scoring_type == 2}selected="selected"{/if}>Win/Loss</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_coop">Co-Op</label>
                                <div class="controls">
                                    <input type="checkbox" id="game_coop" name="coop" value="1" {if $game->coop == 1}checked="checked"{/if} />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_team">Teams</label>
                                <div class="controls">
                                    <input type="checkbox" id="game_team" name="team" value="1" {if $game->team == 1}checked="checked"{/if} />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="game_preorder">Preorder</label>
                                <div class="controls">
                                    <input type="checkbox" id="game_preorder" name="preorder" value="1" {if $game->preorder == 1}checked="checked"{/if} />
                                </div>
                            </div>
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
            </div>
            <div class="tab-pane" id="owners">
                  {include file="sublists/owners_table.tpl" results=$game->ownerslist()->results_all() addowner=true removeowner=true}
            </div>
        </div>
    </div>
</div>
{/block}
