<?php
  @session_start();
  $_ENC_Before = '';
  $_ENC_shuffle = array();
  function AddToShuffle ($text){
    global $_ENC_shuffle;
    $_ENC_shuffle[] = $text; 
  }
  function ShuffleText () {
    global $_ENC_shuffle;
    $result = '';
    foreach ($_ENC_shuffle as $t) {
      if (rand(0,100) < 50) {
        $result .= $t;
      } else {
        $result = $t.$result ;
      }
    }
    return $result;
  }
  
  function EncodeJsString($string, $level=0){
    global $_ENC_string;
    global $_ENC_Before;
    $i = 0;
    $result = '';
    
      while ($i <= strlen($string)) {
        $char = substr($string, $i, 1);
        if (($i > 0) and ($i < strlen($string) - 1)) {
          if (rand(0,100) < 20) {
            $d = dechex(ord($char));
            $c = (strlen($d) == 1) ? '0'.$d : $d;
            $char = '\x'.$c;
          } else if (rand(0,100) < 20) {
            $char = "'+/*".substr(md5(uniqid()) , 0 ,rand(1,32) )."*/'".$char;
          } else if (((rand(0,100) < 20) && ($level < 3)) && (true)) {
             $val = substr('bf'.md5(uniqid()) , 0 ,rand(10,32) );
             $func = substr('bf'.md5(uniqid()) , 0 ,rand(10,32) );
             $c = rand(0, strlen($string) - 2 - $i);
             if ($c > 0) {
               $char = substr($string, $i, $c);
               $i += $c-1;
               $ftext =  'function '.$func.'(){var '.$val.'=\''.EncodeJsString($char, $level+1).'\'; return '.$val.';}'."\n";
               $char = "'+".$func."()+'";
               AddToShuffle($ftext);
             }
          } 
        }
        $i ++;
        $result .= $char;
      }
    
    return $result;
  }
  
  function SetEasyNoCaptcha($_protect = 3, $_form = 'form') {
    global $_ENC_string;
    global $_ENC_Before;    
    $_ENC_string = substr('bf'. base64_encode( md5( uniqid() ) ),0, rand(13,32) );
    $_ENC_this = substr('bf'. base64_encode( md5( uniqid() ) ),0, rand(13,32) );
    $_ENC_form = substr('bf'. base64_encode( md5( uniqid() ) ),0, rand(13,32) );
    $_ENC_l = substr('bf'. base64_encode( md5( uniqid() ) ),0, rand(13,32) );
    $_ENC_c = substr('bf'. base64_encode( md5( uniqid() ) ),0, rand(13,32) );

    $_ENC_function = 'function';


    $CODE = substr(md5(uniqid()) , 0 ,rand(10,32) );
    $_SESSION['MYHASH'][$CODE] = substr(md5(uniqid()) , 0 ,rand(10,32) );
    $_ADDNOCAPTHCA =  substr( base64_encode( md5( uniqid() ) ),0, rand(10,32) );   
    $_SETNOCAPTHCA =  substr( base64_encode( md5( uniqid() ) ),0, rand(10,32) );   

    $result = 
    "$(".EncodeJsString("'document'", 2).")[".EncodeJsString("'ready'",2)."](function(){"."\n".
      "var ".$_ENC_l."=0,s=0,$_ENC_form;"."\n".
      "setTimeout(".$_ENC_function."(){"."\n".
        "$(".EncodeJsString("'".$_form."'").")[".EncodeJsString("'each'")."](".$_ENC_function."(){"."\n".
          "".$_ENC_form." = this;"."\n".
          "if(!$(".$_ENC_form.")[".EncodeJsString("'hasClass'")."]('".$_ADDNOCAPTHCA."')){"."\n".
              "$(".$_ENC_form.")[".EncodeJsString("'on'")."](".EncodeJsString("'click keydown keyup mouseenter mouseleave mousemove'").",function(){"."\n".
                "var ".$_ENC_this." = this;"."\n".
                "var ".$_ENC_c." = ".$_protect." + $(".$_ENC_this.")[".EncodeJsString("'find'")."](".EncodeJsString("'input, textarea, select'").")[".EncodeJsString("'length'")."];"."\n".
                "".$_ENC_l."++;"."\n".
                "if(".$_ENC_l." > ".$_ENC_c."){"."\n".
                  "if(!$(".$_ENC_this.")[".EncodeJsString("'hasClass'")."]('".$_SETNOCAPTHCA."')){"."\n".
                    "$(".$_ENC_this.")[".EncodeJsString("'append'")."](".EncodeJsString("'<input type=\"hidden\" name=\"HASH\" value=\"".$_SESSION['MYHASH'][$CODE]."\">'").");"."\n".
                    "$(".$_ENC_this.")[".EncodeJsString("'append'")."](".EncodeJsString("'<input type=\"hidden\" name=\"HASHCODE\" value=\"".$CODE."\">'").");"."\n".
                    "$(".$_ENC_this.")[".EncodeJsString("'addClass'")."]('".$_SETNOCAPTHCA."');"."\n".
                  "};"."\n".
                "};"."\n".
              "});"."\n".
              "$(".$_ENC_form.")[".EncodeJsString("'addClass'")."]('".$_ADDNOCAPTHCA."');"."\n".
          "}"."\n".
        "})"."\n".
      "},1000);"."\n".
    "});"."\n";
    AddToShuffle($result);
    $result = str_replace("\n", '', ShuffleText());
    
    return '<script type="text/javascript">'.$result.'</script>';
  }

  /******************************************************/
  function CheckEasyNoCaptha () {
    $result = false;
    if ($_REQUEST['HASHCODE'] != '') {
      if (($_SESSION['MYHASH'][$_REQUEST['HASHCODE']] == $_REQUEST['HASH']) && ($_SESSION['MYHASH'][$_REQUEST['HASHCODE']] != '')){
        $result = true;
      };
      unset($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]);
    };
    return $result;
    
  }
?>
