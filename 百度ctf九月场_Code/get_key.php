<?php

function decrypt($txt,$key){
    $txt=base64_decode($txt);
    $rnd = substr($txt,0,4);
    $txt = substr($txt,4);
    $key=md5($rnd.$key);

    $s=0;
    for($i=0;$i<strlen($txt);$i++){
        if($s == 32) $s = 0;
        $tmp .= $txt[$i]^$key[++$s];
    }
    for($i=0;$i<strlen($tmp);$i++){
        $tmp1 .= chr(ord($tmp[$i])-10);
    }
    return $tmp1;
}

function get_submit($str){

    $decode_str = "system";
    $text = "guest";
    $tmp = base64_decode($str);
    $ttmp = substr($tmp,4);
    $rnd = substr($tmp,0,3);
    $s=0;

    for($i=0;$i<strlen($text);$i++){
        $text[$i] = chr(ord($text[$i]+10));
    }

    //根据guest算出需要用的key的前五位
    for($i=0;$i<strlen($text);$i++){
        $key[++$s]=$text[$i]^$ttmp[$i];
    }

    for($i=0;$i<strlen($decode_str);$i++){
        $decode_str_add_ascii.=chr(ord($decode_str($i))+10);
    }

    $s=0;
    for($i=0;$i<strlen($decode_str)-1;$i++){
        $text_submit .= $decode_str_add_ascii[$i]^$key[++$s];
    }
    return $rnd.$text_submit;

}

 
