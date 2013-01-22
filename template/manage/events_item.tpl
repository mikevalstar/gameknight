{extends 'layout.tpl'}
{block name="content"}

<div class="row">
    <div class="span12">
        <h2 class="pull-left"><i class="icon-calendar"></i> {$event->event_name|x}</h2>
        
        {if !$event->isnew()}
        <div class="btn-group pull-right">
            <form id="form_event_delete" method="post">
                <input type="hidden" name="action" value="delete" />
                <button class="btn btn-danger" data-submitfor="#form_event_delete"><i class="icon-trash icon-white"></i> Delete</button>
            </form>
        </div>
        {/if}
        
        <div class="clearfix"></div>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#event" data-toggle="tab">Event</a></li>
            {if !$event->isnew()}
            <li><a href="#participants" data-toggle="tab">Participants</a></li>
            <li><a href="#games" data-toggle="tab">Games</a></li>
            <li><a href="#food" data-toggle="tab">Food</a></li>
            {/if}
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="event">
                <form method="post">
                    <input type="hidden" name="action" value="save" />
                    <div class="row">
                        <div class="span8">
                            <div class="control-group">
                                <label class="control-label" for="event_name">Name</label>
                                <div class="controls">
                                    <input type="text" id="event_name" name="event_name" class="span8 required" value="{$event->event_name|x}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="location">Location</label>
                                <div class="controls">
                                    <input type="text" id="location" name="location" class="span6 required" value="{$event->location|x}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="inputNotes">Notes</label>
                                <div class="controls">
                                    <textarea id="inputNotes" class="span8" name="event_text" rows="10">{$event->event_text|x}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <div class="control-group">
                                <label class="control-label" for="event_start">Start</label>
                                <div class="controls">
                                    <input type="text" id="event_start" name="event_start" class="span2 required datepicker" value="{$event->event_start|extract_date}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="event_start_time">Start Time</label>
                                <div class="controls">
                                    <input type="text" id="event_start_time" name="event_start_time" class="span2 required timepicker" value="{$event->event_start|extract_time}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="event_end">End</label>
                                <div class="controls">
                                    <input type="text" id="event_end" name="event_end" class="span2 datepicker" value="{$event->event_end|extract_date}" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="event_end_time">End Time</label>
                                <div class="controls">
                                    <input type="text" id="event_end_time" name="event_end_time" class="span2 timepicker" value="{$event->event_end|extract_time}" />
                                </div>
                            </div>
                        </div>
                        <div class="span12">
                        {if !$event->isnew()}
                            {assign 'coord' $event->coordinator()}
                            Coordinated by: {$coord->name_first} {$coord->name_last}
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
                
            </div>
            <div class="tab-pane" id="participants">
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
                
                {if $event->invite_sent == 0}
                <h3>Send Out Invite</h3>
                <form method="post" class="form-inline">
                    <input type="hidden" name="action" value="send_invite" />
                    
                    <div class="control-group">
                        <label class="control-label" for="invite_type">Invite Type</label>
                        <div class="controls">
                            <select name="invite_type">
                                <option value="cordial">Cordial Invite</option>
                                <option value="forceful">Forceful Invite</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Send Invite</button>
                    </div>
                </form>
                {else}
                Invite has been sent
                {/if}
            </div>
            <div class="tab-pane" id="games">
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
            <div class="tab-pane" id="food">
                  Coming Soon...
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
            <p class="small">{$row['comment']|xnl2br}</p>
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
