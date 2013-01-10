{extends 'layout.tpl'}
{block name="content"}

<div class="span6 offset3">
    <form class="form-horizontal" method="post">
        <legend>Login</legend>
        {if isset($error)}
            <div class="alert alert-error">{$error}</div>
        {/if}
        <div class="control-group">
            <label class="control-label" for="inputEmail">Email</label>
            <div class="controls">
                <input type="email" name="email" id="inputEmail" placeholder="Email" value="{$email|default:''}">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputPassword">Password</label>
            <div class="controls">
                <input type="password" name="password" id="inputPassword" placeholder="Password">
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button type="submit" class="btn btn-primary">Sign in</button>
            </div>
        </div>
    </form>
    <div><a href="/Register">Register</a></div>
</div>

{/block}