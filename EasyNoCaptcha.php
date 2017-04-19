<?php
  function SetEasyNoCaptcha() {
    $CODE = substr(md5(uniqid()) , 0 ,rand(10,32) );
    $_SESSION['MYHASH'][$CODE] = substr(md5(uniqid()) , 0 ,rand(10,32) );
    $_ADDNOCAPTHCA = base64_encode( substr( md5( uniqid() ),0, rand(10,32) ) );   
    $_SETNOCAPTHCA = base64_encode( substr( md5( uniqid() ),0, rand(10,32) ) );   
    
    $result = "".
    "$(document).ready(function(){".
      "var l = 0, s = 0, f, ae='appe', ap = 'key', cd='use', sq='ve', hc='hasC', b='bi', ls='lic', ac='addC', cl ='lass', x='nd';".
      "setTimeout(function(){".
        "$('form').each(function(){".
          "f = this;".
          "if (!$(f)[hc+cl]('".$_ADDNOCAPTHCA."')) {".
              "$(f)[b+x]('c'+ls+'k '+ap+'do'+'wn '+ap+'up mo'+cd+'en'+'ter'+' '+'mo'+cd+'lea'+sq+' mo'+cd+'mo'+sq+'', function() {".
                "var t = this;".
                "var c = 3 + $(t).find('input, textarea, select').length;".
                "l++;".
                "if (l > c) {".
                  "if (!$(t)[hc+cl]('".$_SETNOCAPTHCA."')) {".
                    "$(t)[ae+x]('<input type=\"hidden\" name=\"HASH\" value=\"".$_SESSION['MYHASH'][$CODE]."\">');".
                    "$(t)[ae+x]('<input type=\"hidden\" name=\"HASHCODE\" value=\"".$CODE."\">');".
                    "$(t)[ac+cl]('".$_SETNOCAPTHCA."');".
                  "};".
                "};".
              "});".
              "$(f)[ac+cl]('".$_ADDNOCAPTHCA."');".
          "}".
        "})".
      "},1000);".
    "});".
"";
    return '<script type="text/javascript">eval(window.atob("'.base64_encode($result).'"));</script>';
  }

  /******************************************************/
  function CheckEasyNoCaptha () {
    $result = false;
    if ($_REQUEST['HASHCODE'] != '') {
      if (($_SESSION['MYHASH'][$_REQUEST['HASHCODE']] == $_REQUEST['HASH']) && ($_SESSION['MYHASH'][$_REQUEST['HASHCODE']] != '')){
        $result = true;
      };
      $_SESSION['MYHASH'][$_REQUEST['HASHCODE']] = '';
    };
    return $result;
    
  }
