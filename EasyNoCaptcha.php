<?php
@session_start();
if (!class_exists('ENCv2')) {
    class ENCv2  {
        private $_ENC_GoogleRecaptcha_key = '';
        private $_ENC_GoogleRecaptcha_SecretKey = '';

        private $_ENC_Before = '';
        private $_ENC_shuffle = array();
        private $_ENC_AllReadyFunc = array();
        private $_ENC_string = array();
        /**********************/
        function AddToShuffle ($text){
            $this->_ENC_shuffle[] = $text;
        }

        /**********************/
        function ShuffleText () {
            $result = '';
            foreach ($this->_ENC_shuffle as $t) {
                if (rand(0,100) < 50) {
                    $result .= $t;
                } else {
                    $result = $t.$result ;
                }
            }
            return $result;
        }
        /**********************/
        function EncodeJsString($string, $level=0) {

            $result = '';
            $needEncode = true;
            if ($level >= 3) {
                $needEncode = false;
                $result = $string;
            }

            while ($needEncode) {
                $needEncode = false;
                $i = 0;
                while ($i <= strlen($string)) {
                    $char = substr($string, $i, 1);
                    if (($i > 0) and ($i < strlen($string) - 1)) {
                        if ((rand(0,100) < 20) || ($char == '<')) {
                            $d = dechex(ord($char));
                            $c = (strlen($d) == 1) ? '0'.$d : $d;
                            $char = '\x'.$c;
                        } else if (rand(0,100) < 20) {
                            $char = '"+/*'.substr(md5(uniqid()) , 0 ,rand(1,32) ).'*/"'.$char;
                        } else if (((rand(0,100) < 20) && ($level < 3)) && (true)) {

                            $val = substr('enc'.md5(uniqid()) , 0 ,rand(10,32) );
                            $functionName = substr('enc'.md5(uniqid()) , 0 ,rand(10,32) );
                            if ((is_array($this->_ENC_AllReadyFunc)) && (count($this->_ENC_AllReadyFunc) > 0)) {
                                while (in_array($functionName, $this->_ENC_AllReadyFunc)) {
                                    $functionName = substr('bf'.md5(uniqid()) , 0 ,rand(10,32) );
                                };
                            };
                            $this->_ENC_AllReadyFunc[] = $functionName;
                            $c = rand(0, strlen($string) - 2 - $i);
                            if ($c > 0) {
                                $char = substr($string, $i, $c);
                                $i += $c-1;
                                $functiontext =  'function '.$functionName.'(){return "'.$this->EncodeJsString($char, $level+1).'";};';
                                $char = '"+'.$functionName.'()+"';
                                $this->AddToShuffle($functiontext);
                            };

                        };
                    };
                    $i ++;
                    $result .= $char;
                }
            }
            return $result;
        }
        /**********************/
        function AddGoogleRecaptcha($key, $secret) {
            $this->_ENC_GoogleRecaptcha_key = $key;
            $this->_ENC_GoogleRecaptcha_SecretKey = $secret;
        }
        /**********************/
        function AddCryptWord($str){
            $val = $str;

            $val = 't'.substr(md5(uniqid()) , 0 ,rand(10,32) );
            if ((is_array($this->_ENC_string)) && (count($this->_ENC_string) > 0)) {
                while (in_array($val, $this->_ENC_string)) {
                    $val = substr(md5(uniqid()) , 0 ,rand(10,32));
                };
            };

            $this->_ENC_string[$str] = $val;
        }
        /**********************/
        function str_replace_once($search, $replace, $text)
        {
        $pos = strpos($text, $search);
        return $pos!==false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
        }
        /**********************/
        function SetEasyNoCaptcha($_protect = 30, $_form = 'form') {
            $HASHCODE = substr(md5(uniqid()) , 0 ,rand(10,32) );
            $HASH = substr(md5(uniqid()) , 0 ,rand(10,32) );
            $_SESSION['MYHASH'][$HASHCODE] =
                array(
                    'VALUE' => $HASH,
                    'DATE' => date('YmdHis')
                );
            $_InitedForm =  substr( base64_encode( md5( uniqid() ) ),0, rand(10,32) );
            $_CheckedForm =  substr( base64_encode( md5( uniqid() ) ),0, rand(10,32) );
            $GoogleRecaptcha_Code = '';
            $GoogleRecaptcha_Init = '';

            $this->AddCryptWord('HASHCODE');
            $this->AddCryptWord('HASH');
            $this->AddCryptWord('GR');
            $this->AddCryptWord('GR_action');
            $this->AddCryptWord('chechsum');

            $this->AddCryptWord('ENC_initGR');
            $this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_initGR'];
            $this->AddCryptWord('ENC_GR_Submit');
            $this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_GR_Submit'];
            $this->AddCryptWord('ENC_check');
            $this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_check'];
            $this->AddCryptWord('ENC_InitENC');
            $this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_InitENC'];

            $this->AddCryptWord('form1');
            $this->AddCryptWord('form2');
            $this->AddCryptWord('form3');
            $this->AddCryptWord('form4');
            $this->AddCryptWord('script1');
            $this->AddCryptWord('forms1');
            $this->AddCryptWord('forms2');
            $this->AddCryptWord('forms3');
            $this->AddCryptWord('grecaptcha');
            $this->AddCryptWord('document1');
            $this->AddCryptWord('document2');
            $T = $this->_ENC_string;

            if (($this->_ENC_GoogleRecaptcha_key != '') && ($this->_ENC_GoogleRecaptcha_SecretKey != '')) {
                $GoogleRecaptcha_Action =  substr( base64_encode( md5( uniqid() ) ),0, rand(10,32) );
                $GoogleRecaptcha_Code = '
                    function '.$T['ENC_initGR'].'() {
                        let '.$T['document1'].' = document;
                        let '.$T['script1'].' = '.$T['document1'].'["createElement"]("script");
                        '.$T['script1'].'["type"] = \'text/javascript\';
                        '.$T['script1'].'["src"] = \'https://www.google.com/recaptcha/api.js?render='.$this->_ENC_GoogleRecaptcha_key.'\';
                        '.$T['document1'].'["getElementsByTagName"]("head")[0].appendChild('.$T['script1'].');
                        const '.$T['forms1'].' = '.$T['document1'].'["querySelectorAll"]( "'.$_form.'" );
                        setTimeout(function() {
                            '.$T['forms1'].'["forEach"](function('.$T['form2'].') {
                                '.$T['ENC_GR_Submit'].'('.$T['form2'].')
                            });
                        }, 1000);
                    };
                    function '.$T['ENC_GR_Submit'].'('.$T['form1'].') {
                        let '.$T['grecaptcha'].' = grecaptcha;
                        '.$T['grecaptcha'].'["ready"](function() {
                            '.$T['grecaptcha'].'["execute"]("'.$this->_ENC_GoogleRecaptcha_key.'", {action: "'.$GoogleRecaptcha_Action.'"})
                            .then(function(token) {
                                let d = document;
                                let '.$T['GR'].' = d["createElement"]("input");
                                let '.$T['GR_action'].' = d["createElement"]("input");
                                '.$T['GR'].'["type"] = "hidden";
                                '.$T['GR'].'["name"] = "gresponse";
                                '.$T['GR'].'["value"] = token;
                                '.$T['GR_action'].'["type"] = "hidden";
                                '.$T['GR_action'].'["name"] = "gaction";
                                '.$T['GR_action'].'["value"] = "'.$GoogleRecaptcha_Action.'";
                                '.$T['form1'].'["appendChild"]('.$T['GR'].');
                                '.$T['form1'].'["appendChild"]('.$T['GR_action'].');
                            });;
                        });
                    };
                ';
                $GoogleRecaptcha_Init = $T['ENC_initGR'].'();';
            };


            $result = '
                document["addEventListener"]("DOMContentLoaded", function(event) {
                    let '.$T['document2'].' = document;
                    let '.$T['chechsum'].' = 0;
                    function '.$T['ENC_check'].'() {
                        '.$T['chechsum'].' ++;
                        if ('.$T['chechsum'].' > '.$_protect.') {
                            const '.$T['forms2'].' = '.$T['document2'].'["querySelectorAll"]( ".'.$_InitedForm.':not(.'.$_CheckedForm.')" );
                            '.$T['forms2'].'["forEach"](function('.$T['form3'].') {
                                let '.$T['HASHCODE'].' = '.$T['document2'].'["createElement"]("input");
                                let '.$T['HASH'].' = '.$T['document2'].'["createElement"]("input");
                                '.$T['HASHCODE'].'["type"] = "hidden";
                                '.$T['HASHCODE'].'["name"] = "HASHCODE";
                                '.$T['HASHCODE'].'["value"] = "'.$HASHCODE.'";
                                '.$T['HASH'].'["type"] = "hidden";
                                '.$T['HASH'].'["name"] = "HASH";
                                '.$T['HASH'].'["value"] = "'.$HASH.'";
                                '.$T['form3'].'["appendChild"]('.$T['HASHCODE'].');
                                '.$T['form3'].'["appendChild"]('.$T['HASH'].');
                                '.$T['form3'].'["classList"]["add"]("'.$_CheckedForm.'");
                            });
                        };
                    };
                    function '.$T['ENC_InitENC'].'() {
                        const '.$T['forms3'].' =  '.$T['document2'].'["querySelectorAll"]("'.$_form.'");
                        '.$T['forms3'].'["forEach"](function('.$T['form4'].') {
                            if (!'.$T['form4'].'["classList"]["contains"]("'.$_InitedForm.'")) {
                                '.$T['form4'].'["classList"]["add"]("'.$_InitedForm.'");
                                '.$T['form4'].'["addEventListener"]("click", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("keydown", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("keyup", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("touchstart", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("touchmove", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("touchend", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("mouseenter", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("keyup", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("mouseleave", '.$T['ENC_check'].', false);
                                '.$T['form4'].'["addEventListener"]("mousemove", '.$T['ENC_check'].', false);
                            };
                        });
                        '.
                        $GoogleRecaptcha_Init.
                        '
                    };
                    '.
                    $GoogleRecaptcha_Code.
                    '
                    setTimeout(function() {
                        '.$T['ENC_InitENC'].'();
                    }, 1000);
                });
            ';

            preg_match_all ('/\"[^\"]*\"/' , $result , $matches);
            if (is_array($matches)) {
                if (is_array($matches[0])) {
                    foreach ($matches[0] as $string) {

                        $result = $this->str_replace_once( $string, $this->EncodeJsString($string), $result, 1);
                    }
                }
            }

            $this->AddToShuffle($result);
            $result = $this->ShuffleText();
            $arResult = explode("\n", $result);
            if (is_array($arResult)) {
                $result = '';
                foreach ($arResult as $r) {
                    $result .= trim($r);
                };
            };
            $result = '<script type="text/javascript">'.$result.'</script>';



            return $result;

        }
        /**********************/
        function CheckEasyNoCaptha () {
            $result = false;
            if ($_REQUEST['HASHCODE'] != '') {
                if (($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]['VALUE'] == $_REQUEST['HASH']) && ($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]['VALUE'] != '')){
                    if ($this->_ENC_GoogleRecaptcha_key == '') {
                        $result = true;
                    } else {
                        $gresponse  = $_REQUEST['gresponse'];
                        $gaction  = $_REQUEST['gaction'];

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $this->_ENC_GoogleRecaptcha_SecretKey, 'response' => $gresponse)));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $arrResponse = json_decode($response, true);

                        $result = false;
                        if (($arrResponse['success'] == 1) && ($arrResponse['action'] == $gaction) && ($arrResponse['score'] > 0.5)) {
                            $result = true;
                        };
                    };
                };
                unset($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]);
            };
            return $result;
        }
    }
};