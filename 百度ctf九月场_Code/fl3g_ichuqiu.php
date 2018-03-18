    <?php
/**
 * Created by PhpStorm.
 * Date: 2015/11/16
 * Time: 1:31
 */
error_reporting(E_ALL || ~E_NOTICE);
include('config.php');
function random($length, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++)	{
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

function encrypt($txt,$key){
    for($i=0;$i<strlen($txt);$i++){
        $tmp .= chr(ord($txt[$i])+10);
    }
    $txt = $tmp;
    $rnd=random(4);
    $key=md5($rnd.$key);
    echo "key:".$key."\n";
    $s=0;
    for($i=0;$i<strlen($txt);$i++){
        if($s == 32) $s = 0;
        $x = ++$s;
        $ttmp .= $txt[$i] ^ $key[$x];
        echo ord($txt[$i]),'^',ord($key[$x]),'->', ord($txt[$i] ^ $key[$x]);
        echo "\n";
    }
    echo "\n";
    echo "rnd:".$rnd;
    echo "\n";
    return base64_encode($rnd.$ttmp);
}

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
    $md5_char = "abcdef1234567890";
    $tmp = base64_decode($str);
    $ttmp = substr($tmp,4);
    $rnd = substr($tmp,0,4);
    echo "rnd:".$rnd;
    echo "\n";
    for($i=0;$i<strlen($text);$i++){
        $text[$i] = chr(ord($text[$i])+10);
    }

    //根据guest算出需要用的key的前五位
    $key="-";
    for($i=0;$i<strlen($text);$i++){
        $key.=  ($text[$i]^$ttmp[$i]);
       // echo ord($text[$i]),'^',ord($ttmp[$i]),'->', ord($text[$i]^$ttmp[$i]);
    }

    echo $key;
    echo "\n";
    for($i=0;$i<strlen($decode_str);$i++){
        $decode_str_add_ascii.=chr(ord($decode_str[$i])+10);
    }

    $s=0;
    for($i=0;$i<strlen($decode_str)-1;$i++){
        $text_submit .= $decode_str_add_ascii[$i]^$key[++$s];
    }
    $a = array();
    for($i=0;$i<strlen($md5_char);$i++)
    {
        $a[$i] =  $rnd.$text_submit.($decode_str_add_ascii[strlen($decode_str)-1]^$md5_char[$i]);
    }
     
    //return $rnd.$text_submit;
    return $a;
}

//flag{83f3fb77-26d6-4b73-a40f-f812ad16bb3a}
$submit_str = get_submit("bUxKYRBHDRhI");
$f = fopen("attack-str","w");
for($i=0;$i<16;$i++){
    $str = base64_encode($submit_str[$i]);
    fwrite($f,$str."\n");
}
$f.fclose();
?>