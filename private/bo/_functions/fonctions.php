<?php
function noAccent($text){
    $text = mb_strtolower($text, 'UTF-8');
    $text = str_replace(
        array(
            'à', 'â', 'ä', 'á', 'ã', 'å',
            'î', 'ï', 'ì', 'í',
            'ô', 'ö', 'ò', 'ó', 'õ', 'ø',
            'ù', 'û', 'ü', 'ú',
            'é', 'è', 'ê', 'ë',
            'ç', 'ÿ', 'ñ',
        ),
        array(
            'a', 'a', 'a', 'a', 'a', 'a',
            'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u',
            'e', 'e', 'e', 'e',
            'c', 'y', 'n',
        ),
        $text
    );
    $text = preg_replace('#([^.a-z0-9]+)#i', '-', $text);
    $text = preg_replace('#-{2,}#','-',$text);
    $text = preg_replace('#-$#','',$text);
    $text = preg_replace('#^-#','',$text);
    return $text;
}
?>