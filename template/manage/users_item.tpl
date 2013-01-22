{extends 'layout.tpl'}
{block name="content"}

{assign 'gl' $user->gameslist()->results_all()}

<h2 class="pull-left"><i class="icon-user"></i> {$user->name_first|x} {$user->name_last|x}</h2>

{if !$user->isnew()}
<div class="btn-group pull-right">
    <form id="form_user_delete" method="post">
        <input type="hidden" name="action" value="delete" />
        <button class="btn btn-danger" data-submitfor="#form_user_delete"><i class="icon-trash icon-white"></i> Delete</button>
    </form>
</div>
{/if}

<div class="clearfix"></div>

<ul class="nav nav-tabs">
            <li class="active"><a href="#user" data-toggle="tab">User</a></li>
            <li><a href="#games" data-toggle="tab">Games {if !$user->isnew()}({count($gl)}){/if}</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="user">
                <form method="post">
                <input type="hidden" name="action" value="save" />
                <div class="row">
                    <div class="span6">
                        <div class="control-group">
                            <label class="control-label" for="name_first">First Name</label>
                            <div class="controls">
                                <input type="text" id="name_first" name="name_first" class="span5 required" value="{$user->name_first|x}" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name_last">Last Name</label>
                            <div class="controls">
                                <input type="text" id="name_last" name="name_last" class="span5 required" value="{$user->name_last|x}" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_email">Email</label>
                            <div class="controls">
                                <input type="text" id="user_email" name="email" class="span4 validate_email" value="{if isset($email)}{$email}{else}{$user->email|x}{/if}" />
                                <input type="checkbox" name="notify_email" value="1" {if $user->notify_email == 1}checked="checked"{/if} /> Notify
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_phone">Phone</label>
                            <div class="controls">
                                <input type="text" id="user_phone" name="phone" class="span4 validate_phone" value="{$user->phone|x}" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_password">New Password</label>
                            <div class="controls">
                                <input type="password" id="user_password" name="new_password" class="span5" value="" />
                            </div>
                        </div>
                    </div>
                    <div class="span6">
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
            
            <div class="tab-pane" id="games">
                {include file="sublists/games_table.tpl" results=$gl}
            </div>
        </div>


{/block}
