{extends 'layout.tpl'}
{block name="content"}

<div class="row">
    <div class="span12">
        <h2><i class="icon-calendar"></i> {$event->event_name|x}</h2>
        <div class="clearfix"></div>
        
        <div class="row">
            <div class="span4">
                <h3>Details</h3>
                <h4>Location: {$event->location|x}</h4>
                <h4>{$event->event_start|x} - {$event->event_end|x}</h4>
                
                <p>{$event->event_text|nl2br}</p>
                
                {assign 'coord' $event->coordinator()}
                <h5>Coordinated by: {$coord->name_first} {$coord->name_last}</h5>
            </div>
            <div class="span4">
                <h3>Participants</h3>
                <ul>
                    {foreach from=$event->participants() item=row}
                    <li>{$row['name_first']} {$row['name_last']} - {$row['response']}</li>
                    {/foreach}
                </ul>
                {if !$event->has_participated($U->id)}
                <h5>Record your response:</h5>
                {else}
                <h5>Update your response:</h5>
                {/if}
                <form method="post">
                    <input type="hidden" name="action" value="im_coming" />
                    <input type="submit" class="btn btn-primary" name="coming" value="Yes" />
                    <input type="submit" class="btn btn-danger" name="coming" value="No" />
                    <input type="submit" class="btn" name="coming" value="Maybe, cause I'm a bitch" />
                </form>
            </div>
            <div class="span4">
                <h3>Games</h3>
                <ul class="game_list">
                    {foreach from=$event->games($U->id) item=row}
                    <li>
                        <form method="post">
                            [{$row['votes']}] {$row['game_name']}
                            {if $row['has_voted'] == 1}
                            <input type="hidden" name="action" value="unvote_game" />
                            <input type="hidden" name="game_fk" value="{$row['game_pk']}"/>
                            <button type="submit" class="btn btn-success"><i class="icon-ok"></i> Voted</button>
                            {else}
                            <input type="hidden" name="action" value="vote_game" />
                            <input type="hidden" name="game_fk" value="{$row['game_pk']}"/>
                            <input type="submit" class="btn" value="Vote" />
                            {/if}
                        </form>
                    </li>
                    {/foreach}
                </ul>
                <p>Enter in a game you would like to play or vote for one someone else has already added.</p>
                <form method="post">
                    <input type="hidden" name="action" value="vote_game" />
                    <input type="hidden" name="game_fk" value="" id="add_game_fk" />
                    <div class="input-append">
                        <input type="text" name="game_to_add_name" class="fillgamename span3" data-idfill="#add_game_fk" value="" placeholder="Game Name" autocomplete="off" />
                        <input type="submit" class="btn" value="Add" />
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<div class="row">
    <div class="span12"><div id="comments">
        <h2><i class="icon-comments"></i> Comments</h2>
        
        {foreach from=$event->comments()->results_all() item=row}
        <div class="comment">
            <div class="pull-right gray small">{$row['created_when']}</div>
            <h4>{$row['author']} <small>wrote</small></h4>
            <i class="icon-quote-left icon-4x pull-left icon-muted"></i>
            <p class="small">{$row['comment']|xnl2br}</p>
            <div class="clearfix"></div>
        </div>
        {/foreach}
        
        <form method="post">
            <input type="hidden" name="action" value="comment" />
            <textarea name="comment" placeholder="Comment on this event..." style="width: 99%; height: 6em;"></textarea>
            <input type="submit" class="btn" value="Post Comment" />
        </form>
    </div></div>
</div>
{/block}
