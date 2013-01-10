<!DOCTYPE html>
<html>
<head>
    <title>BLEACHERS</title>
    <link rel="stylesheet" href="/css/css.php" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="/js/modernizr.js"></script>
    <script src="/js/jquery.calendario.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/bootstrap-popover.js"></script>
    <script src="/js/bootstrap-transition.js"></script>
    <script src="/js/bootstrap-carousel.js"></script>
    <script src="/js/konrad-weather.js"></script>
    <script src="/js/requiredFields.js"></script>
    <script src="/js/B.js"></script>
</head>
<body>

<div class="container" id="N">
    <div class="row">
        <div class="span4">
            <a href="/"><img src="/img/Bleachers2.png" /></a>
        </div>
        <div class="span8">
            <ul>
                <li><a href="/tour">take the tour</a></li>
                <li><a href="/join">join us</a></li>
                <li><a target="_BLANK" href="https://gobleachers.zendesk.com/home">get support</a></li>
                <li><a href="/blog">read the blog</a></li>
                <li><a href="/contact">contact us</a></li>
                {if $U}
                <li><a class="b" href="/logout">sign out</a></li>
                {else}
                <li><a class="b" href="/login">sign in</a></li>
                {/if}
            </ul>
        </div>
    </div>
</div>
<div class="container" id="C">
    {if $U}
        {foreach from=$U->messages() item=row}
            <div class="alert alert-{$row['type']}">
                {$row['message']|x}
            </div>
        {/foreach}
    {/if}
    
    {block "content"}You forgot to set the content!{/block}
</div>
<div id="FO">
    <div class="container" id="F">
        <div class="row">
            <div class="span12">
                <ul class="pull-right social">
                    <li class="first"><a href="#">&copy; The Bleachers Corporation 2012</a></li>
                    <li><a href="http://twitter.com/BleachersCorp"><i class="icon-twitter"></i></a></li>
                    <li><a href="https://www.facebook.com/BleachersCorp"><i class="icon-facebook"></i></a></li>
                    <li><a href="http://www.youtube.com/user/bleachersvideo"><i class="icon-youtube"></i></a></li>
                </ul>
                <ul class="nav">
                    <li class="first"><a href="/about">about us</a></li>
                    <li><a href="/tos">terms of use</a></li>
                    <li><a href="/privacy">privacy policy</a></li>
                    <li><a href="/contact">contact us</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
{if Tracker::_trackable()}{Tracker::htmlOut()}{/if}

<script type="text/javascript">

 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', 'UA-36813147-1']);
 _gaq.push(['_trackPageview']);

 (function() {
   var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
   ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 })();

</script>
</body>
</html>
