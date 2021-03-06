<!DOCTYPE html>
<html>
<head>
    <title>{block "title"}{/block}Game Knight</title>
    
    <link rel="shortcut icon" href="/favicon.png">
    <link rel="apple-touch-icon" href="/apple_logo_512.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple_logo_57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple_logo_72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple_logo_114.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple_logo_144.png">
    <link rel="apple-touch-icon" sizes="256x256" href="/apple_logo_256.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/apple_logo_512.png">
    
    <link rel="stylesheet" href="/css/css.php" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="/js/bootstrap-tab.js"></script>
    <script src="/js/bootstrap-dropdown.js"></script>
    <script src="/js/bootstrap-datepicker.js"></script>
    <script src="/js/bootstrap-timepicker.js"></script>
    <script src="/js/bootstrap-typeahead.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/konrad-requiredFields.js"></script>
    
    <script src="/js/kggamenight.js"></script>
</head>
<body>
    <div id="headnav">
        <a id="title_brand" href="/"><img src="/img/game-knight-logo.png" /></a>
        <div class="navbar navbar-inverse navbar-fixed-top">            
            <div class="navbar-inner">
                <blockquote style="color: gray; font-style: italic;">Because he's the hero Konrad Group deserves, but not the one it needs right now. So we'll hunt him. Because he can take it. Because he's not our hero. He's a silent guardian, a watchful protector. A game knight.</blockquote>
                <div class="container">
                    {if $U}
                    <ul class="nav" style="padding-left: 145px; font-size: 0.9em;">
                        <li><a href="/Events">Events</a></li>
                        <li><a href="/Games">Games</a></li>
                        <li><a href="/Plays">Plays</a></li>
                        <li><a href="/Users">Users</a></li>
                        <li><a href="/MassMail">Mass Email</a></li>
                    </ul>
                    {else}
                    <ul class="nav" style="padding-left: 145px; font-size: 0.9em;">
                        <li><a href="/Login">Login</a></li>
                    </ul>
                    {/if}
                    {block "navbar-top"}{/block}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div id="contentcontainer" class="container">
        {if $U}
            {foreach from=$U->messages() item=row}
                <div class="alert alert-{$row['type']}">
                    {$row['message']|x}
                </div>
            {/foreach}
        {/if}
        {block "content"}You forgot to set the content!{/block}
    </div>
    
    <div class="clearfix"></div>
    
    <footer>
        <div class="container">
            <div id="footer">
                {if $U}
                <div class="pull-right small">
                    Logged in as: <a href="/users/{$U->id}">{$U->name_first} {$U->name_last}</a> - <a href="/logout">logout</a>
                </div>
                {/if}
                &copy; 2013 Konrad Group
            </div>
        </div>
    </footer>
    {if false && Tracker::_trackable()}{Tracker::htmlOut()}{/if}
</body>
</html>
