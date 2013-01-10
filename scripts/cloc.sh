cloc --exclude-dir=lib,.git,tmp,less/bootstrap \
    --not-match-f="(jquery|bootstrap|modernizr|flowplayer).*(js|less|swf)" \
    --force-lang=html,tpl \
    --force-lang=css,less \
    .
