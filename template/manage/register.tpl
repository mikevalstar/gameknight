{extends 'layout.tpl'}
{block name="content"}

<div class="span6 offset3">
    <form class="form-horizontal" method="post">
        <legend>Register</legend>
        {if isset($error)}
            <div class="alert alert-error">{$error}</div>
        {/if}
        <div class="control-group">
            <label class="control-label" for="inputEmail">Email</label>
            <div class="controls">
                <input type="text" class="validate_email" name="email" id="inputEmail" placeholder="Email" value="{$email|default:''}">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
                <input type="password" class="required" name="password" id="inputPassword" placeholder="Password">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputFName">First Name</label>
            <div class="controls">
                <input type="text" class="required" name="name_first" id="inputFName" placeholder="First Name" value="{$name_first|default:''}">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputLName">Last Name</label>
            <div class="controls">
                <input type="text" class="required" name="name_last" id="inputLName" placeholder="Last Name" value="{$name_last|default:''}">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPhone">Phone</label>
            <div class="controls">
                <input type="text" class="required" name="phone" id="inputPhone" placeholder="Phone Number" value="{$phone|default:''}">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary">Sign up</button>
            </div>
        </div>
    </form>
</div>

{/block}