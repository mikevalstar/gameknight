<?php

function x($string) {
    return htmlspecialchars($string);
}

function xnl2br($string) {
    return nl2br(htmlspecialchars($string));
}
