<?PHP

function prettyurlencode($string){
    return urlencode(
        str_replace(" ", "_", $string)
    );
}