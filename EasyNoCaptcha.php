<?php
@session_start();
if (!class_exists('ENCv3')) {
	class ENCv3
	{
		private $_ENC_shuffle = [];
		private $_ENC_AllReadyFunc = [];
		private $_ENC_string = [];
		private $curArray = array();
		private $_ENC_setting = [];

		function __construct(array $setting = [])
		{
			$this->_ENC_setting = [
				'encode' => true,
				'checkDefault' => true,
				'checkIP' => true,
				'ReturnPureJS' => false,
				'Recaptcha_key' => '',
				'Recaptcha_SecretKey' => '',
				'hCaptcha_key' => '',
				'hCaptcha_SecretKey' => '',
				'script_attributes' => '',
				'debug' => false,
				'forms_selector' => 'form'
			];

			if (count($setting) > 0) {
				foreach ($setting as $key => $val) {
					if (isset($this->_ENC_setting[$key])) {
						$this->_ENC_setting[$key] = $val;
					}
				}
			}

			$this->curArray = [
				'DATE' => time(),
				'IP' => $_SERVER['REMOTE_ADDR'],
				'URL' => $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
				'REFERER' => $_SERVER['HTTP_REFERER']
			];

			if ($this->_ENC_setting['checkDefault']) {
				if (is_array($_SESSION['MYHASH'])) {
					foreach ($_SESSION['MYHASH'] as $key => $val) {
						if (isset($_SESSION['MYHASH'][$key]['DATE'])) {
							/* Delete hash livetime > 10 min */
							if ($this->curArray['DATE'] - $_SESSION['MYHASH'][$key]['DATE'] > 600) {
								unset($_SESSION['MYHASH'][$key]);
							}
						} else {
							/* Delete hash without DATE */
							unset($_SESSION['MYHASH'][$key]);
						}
					}
				}
			}
		}

		/**********************/
		/* ENC */
		/**********************/
		public function SetEasyNoCaptcha($_protect = 3, $_form = 'form')
		{
			$this->_ENC_setting['form_selector'] = $_form;
			$HASHCODE = substr(md5(uniqid()), 0, rand(10, 32));
			$HASH = substr(md5(uniqid()), 0, rand(10, 32));
			if (!$this->_ENC_setting['checkDefault']) {
				$HASHCODE = md5('HASHCODE');
				$HASH = md5('HASH');
			};
			$_SESSION['MYHASH'][$HASHCODE] = $this->curArray;
			$_SESSION['MYHASH'][$HASHCODE]['VALUE'] = $HASH;
			$_SESSION['MYHASH'][$HASHCODE]['DATE'] = date('YmdHis');

			$_InitedForm =  substr(base64_encode(md5(uniqid())), 0, rand(10, 32));
			$_CheckedForm =  substr(base64_encode(md5(uniqid())), 0, rand(10, 32));


			$this->addCryptWord('HASHCODE');
			$this->addCryptWord('HASH');

			$this->addCryptWord('chechsum');


			$this->addCryptWord('ENC_check');
			$this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_check'];
			$this->addCryptWord('ENC_InitENC');
			$this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_InitENC'];
			$this->addCryptWord('form1');
			$this->addCryptWord('form2');
			$this->addCryptWord('form3');
			$this->addCryptWord('form4');
			$this->addCryptWord('script1');
			$this->addCryptWord('forms1');
			$this->addCryptWord('forms2');
			$this->addCryptWord('forms3');

			$this->addCryptWord('document1');
			$this->addCryptWord('document2');

			$this->addCryptWord('ENCMutationObserver');
			$this->addCryptWord('event');

			$_ENC_script['code'] = '';
			$_ENC_script['init'] = '';
			if (($this->_ENC_setting['Recaptcha_key'] != '') && ($this->_ENC_setting['Recaptcha_SecretKey'] != '')) {
				$_ENC_script['code'] .= $this->SetGoogleReCaptcha();
				$_ENC_script['init'] .= $this->getCryptWord('ENC_initGR') . '();';
			};

			if (($this->_ENC_setting['hCaptcha_key'] != '') && ($this->_ENC_setting['hCaptcha_SecretKey'] != '')) {
				$_ENC_script['code'] .= $this->SetHCaptcha();
				$_ENC_script['init'] .= $this->getCryptWord('ENC_initHC') . '();';
			};

			$T = $this->_ENC_string;


			$result = '
				document["addEventListener"]("DOMContentLoaded", function(event) {
					const ' . $T['document2'] . ' = document;
					let ' . $T['chechsum'] . ' = 0;

					function ' . $T['ENC_check'] . '() {
						' . $T['chechsum'] . ' ++;
						if (' . $T['chechsum'] . ' > ' . $_protect . ') {
							const ' . $T['forms2'] . ' = ' . $T['document2'] . '["querySelectorAll"]( ".' . $_InitedForm . ':not(.' . $_CheckedForm . ')" );
							' . $T['forms2'] . '["forEach"](function(' . $T['form3'] . ') {
								let ' . $T['HASHCODE'] . ' = ' . $T['document2'] . '["createElement"]("input");
								let ' . $T['HASH'] . ' = ' . $T['document2'] . '["createElement"]("input");
								' . $T['HASHCODE'] . '["type"] = "hidden";
								' . $T['HASHCODE'] . '["name"] = "HASHCODE";
								' . $T['HASHCODE'] . '["value"] = "' . $HASHCODE . '";
								' . $T['HASH'] . '["type"] = "hidden";
								' . $T['HASH'] . '["name"] = "HASH";
								' . $T['HASH'] . '["value"] = "' . $HASH . '";
								' . $T['form3'] . '["appendChild"](' . $T['HASHCODE'] . ');
								' . $T['form3'] . '["appendChild"](' . $T['HASH'] . ');
								' . $T['form3'] . '["classList"]["add"]("' . $_CheckedForm . '");
							});
						};
					};
					function ' . $T['ENC_InitENC'] . '() {
						const ' . $T['forms3'] . ' =  ' . $T['document2'] . '["querySelectorAll"]("' . $_form . '");
						' . $T['forms3'] . '["forEach"](function(' . $T['form4'] . ') {
							if (!' . $T['form4'] . '["classList"]["contains"]("' . $_InitedForm . '")) {
								' . $T['form4'] . '["classList"]["add"]("' . $_InitedForm . '");
								' . $T['form4'] . '["addEventListener"]("click", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("keydown", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("keyup", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("touchstart", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("touchmove", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("touchend", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("mouseenter", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("keyup", ' . $T['ENC_check'] . ', false);
								' . $T['form4'] . '["addEventListener"]("mouseleave", ' . $T['ENC_check'] . ', false);
							};
						});
						' .
						$_ENC_script['init'] .
				'
					};
					' .
				$_ENC_script['code'] .
				'
					setTimeout(function() {
						' . $T['ENC_InitENC'] . '();
						const MutationObserver	= window.MutationObserver;
						const ' . $T['ENCMutationObserver'] . ' = new MutationObserver(' . $T['ENC_InitENC'] . ');
						' . $T['ENCMutationObserver'] . '["observe"](' . $T['document2'] . '["querySelectorAll"]("body")[0],{childList:true,subtree:true});

					}, 1000);

				});
			';

			preg_match_all('/\"[^\"]*\"/', $result, $matches);
			if (is_array($matches)) {
				if (is_array($matches[0])) {
					foreach ($matches[0] as $string) {

						$result = $this->str_replace_once($string, $this->EncodeJsString($string), $result, 1);
					}
				}
			}

			$this->AddToShuffle($result);
			$result = $this->ShuffleText();
			if ($this->_ENC_setting['encode']) {
				$arResult = explode("\n", $result);
				if (is_array($arResult)) {
					$result = '';
					foreach ($arResult as $r) {
						$result .= trim($r);
					};
				};
			}

			if (!$this->_ENC_setting['ReturnPureJS']) {
				$result = '<script type="text/javascript">' . $result . '</script>';
			}

			return $result;
		}
		/**********************/
		public function CheckEasyNoCaptha()
		{
			$result = false;
			if ((isset($_REQUEST['HASHCODE'])) && (isset($_REQUEST['HASH']))) {
				$result = 	($this->CheckHash() &&
					$this->CheckIP()
				) &&
					($this->_ENC_setting['onlyRecaptcha'] ||
						$this->CheckRecaptcha()
					) && ($this->CheckHCaptcha()
					);
				if (!$this->_ENC_setting['onlyRecaptcha']) {
					unset($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]);
				};
			};
			return $result;
		}
		/**********************/
		private function CheckHash()
		{
			$result = true;
			if ($this->_ENC_setting['checkDefault']) {
				if (
					($_REQUEST['HASHCODE'] == '') ||
					($_REQUEST['HASH'] == '') ||
					($_SESSION['MYHASH'][$_REQUEST['HASHCODE']]['VALUE'] != $_REQUEST['HASH'])
				) {
					$result = false;
				}
			}

			return $result;
		}

		/**********************/
		private function CheckIP()
		{
			$result = true;
			if ($this->_ENC_setting['checkIP']) {
				$session = $_SESSION['MYHASH'][$_REQUEST['HASHCODE']];
				if ($this->curArray['IP'] != $session['IP']) {
					/* IP не совпадают */
					$result = false;
				};
			};
			return $result;
		}

		/**********************/
		/* ReCaptcha */
		/**********************/

		public function AddGoogleRecaptcha($key, $secret)
		{
			$this->_ENC_setting['Recaptcha_key'] = $key;
			$this->_ENC_setting['Recaptcha_SecretKey'] = $secret;
		}
		/**********************/
		private function SetGoogleReCaptcha()
		{
			$_form = $this->_ENC_setting['form_selector'];
			$this->addCryptWord('ENC_initGR');
			$this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_initGR'];
			$this->addCryptWord('ENC_GR_Set');
			$this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_GR_Set'];

			$this->addCryptWord('GR');
			$this->addCryptWord('GR_action');
			$this->addCryptWord('GR_checked');
			$this->addCryptWord('GR_add_script');
			$this->addCryptWord('GR_need_add_script');
			$this->addCryptWord('grecaptcha');
			$this->addCryptWord('GoogleRecaptcha_Action');

			$T = $this->_ENC_string;
			$GoogleRecaptcha_Action =  $T['GoogleRecaptcha_Action'];
			$result = '
				let ' . $T['GR_need_add_script'] . '=1;
				function ' . $T['GR_add_script'] . '() {
					if (' . $T['GR_need_add_script'] . ') {
						let ' . $T['document1'] . ' = document;
						let ' . $T['script1'] . ' = ' . $T['document1'] . '["createElement"]("script");
						' . $T['script1'] . '["type"] = \'text/javascript\';
						' . $T['script1'] . '["src"] = \'https://www.google.com/recaptcha/api.js?render=' . $this->_ENC_setting['Recaptcha_key'] . '\';
						' . $T['document1'] . '["getElementsByTagName"]("head")[0].appendChild(' . $T['script1'] . ');
						' . $T['GR_need_add_script'] . '=0;
					};
				};
				function ' . $T['ENC_initGR'] . '() {
					let ' . $T['document1'] . ' = document;
					' . $T['GR_add_script'] . '();
					const ' . $T['forms1'] . ' = ' . $T['document1'] . '["querySelectorAll"]( "' . $_form . ':not(.' . $T['GR_checked'] . ')" );
					setTimeout(function() {
						' . $T['forms1'] . '["forEach"](function(' . $T['form2'] . ') {
							' . $T['ENC_GR_Set'] . '(' . $T['form2'] . ');
							' . $T['form2'] . '["classList"]["add"]("' . $T['GR_checked'] . '");
						});
					}, 1000);
				};
				function ' . $T['ENC_GR_Set'] . '(' . $T['form1'] . ') {
					if (!' . $T['form1'] . '["classList"]["contains"]("' . $T['GR_checked'] . '")) {
						' . $T['form1'] . '["classList"]["add"]("' . $T['GR_checked'] . '");
						let ' . $T['grecaptcha'] . ' = grecaptcha;
						' . $T['grecaptcha'] . '["ready"](function() {
							' . $T['grecaptcha'] . '["execute"]("' . $this->_ENC_setting['Recaptcha_key'] . '", {action: "' . $GoogleRecaptcha_Action . '"})
							.then(function(token) {
								let d = document;
								let ' . $T['GR'] . ' = d["createElement"]("input");
								let ' . $T['GR_action'] . ' = d["createElement"]("input");
								' . $T['GR'] . '["type"] = "hidden";
								' . $T['GR'] . '["name"] = "gresponse";
								' . $T['GR'] . '["value"] = token;
								' . $T['GR_action'] . '["type"] = "hidden";
								' . $T['GR_action'] . '["name"] = "gaction";
								' . $T['GR_action'] . '["value"] = "' . $GoogleRecaptcha_Action . '";
								' . $T['form1'] . '["appendChild"](' . $T['GR'] . ');
								' . $T['form1'] . '["appendChild"](' . $T['GR_action'] . ');
							});;
						});
					};
				};
			';
			return $result;

		}


		/**********************/
		private function CheckRecaptcha()
		{

			$result = false;
			if ($this->_ENC_setting['Recaptcha_key'] == '') {
				$result = true;
			} else if ((isset($_REQUEST['gresponse'])) && (isset($_REQUEST['gaction']))) {
				$gresponse  = $_REQUEST['gresponse'];
				$gaction  = $_REQUEST['gaction'];
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $this->_ENC_setting['Recaptcha_SecretKey'], 'response' => $gresponse)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);
				$arrResponse = json_decode($response, true);
				$result = false;
				if ($this->_ENC_setting['debug']) {
					echo '<pre>';
					print_r($arrResponse);
					echo  '</pre>';
				}
				if (($arrResponse['success'] == 1) && ($arrResponse['action'] == $gaction) && ($arrResponse['score'] > 0.5)) {
					$result = true;
				};


			};
			return $result;
		}


		/**********************/
		/* hCaptcha */
		/**********************/

		public function AddHCaptcha($key, $secret)
		{
			$this->_ENC_setting['hCaptcha_key'] = $key;
			$this->_ENC_setting['hCaptcha_SecretKey'] = $secret;
		}
		/**********************/
		private function SetHCaptcha()
		{
			$_form = $this->_ENC_setting['form_selector'];
			$this->addCryptWord('ENC_initHC');
			$this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_initHC'];
			$this->addCryptWord('ENC_HC_Set');
			$this->_ENC_AllReadyFunc[] = $this->_ENC_string['ENC_HC_Set'];

			$this->addCryptWord('HC');
			$this->addCryptWord('HC_action');
			$this->addCryptWord('HC_checked');
			$this->addCryptWord('HC_add_script');
			$this->addCryptWord('HC_need_add_script');
			$this->addCryptWord('HCaptcha');
			$this->addCryptWord('btn_submit');
			$T = $this->_ENC_string;
			$result = '
				let ' . $T['HC_need_add_script'] . '=1;
				function ' . $T['HC_add_script'] . '() {
					if (' . $T['HC_need_add_script'] . ') {
						let ' . $T['document1'] . ' = document;
						let ' . $T['script1'] . ' = ' . $T['document1'] . '["createElement"]("script");
						' . $T['script1'] . '["type"] = \'text/javascript\';
						' . $T['script1'] . '["src"] = \'https://www.hCaptcha.com/1/api.js' . $this->_ENC_setting['Recaptcha_key'] . '\';
						' . $T['document1'] . '["getElementsByTagName"]("head")[0].appendChild(' . $T['script1'] . ');
						' . $T['HC_need_add_script'] . '=0;
					};
				};
				function ' . $T['ENC_initHC'] . '() {
					const ' . $T['document1'] . ' = document;

					const ' . $T['forms1'] . ' = ' . $T['document1'] . '["querySelectorAll"]( "' . $_form . ':not(.' . $T['HC_checked'] . ')" );

					' . $T['forms1'] . '["forEach"](function(' . $T['form2'] . ') {
						' . $T['ENC_HC_Set'] . '(' . $T['form2'] . ');
						' . $T['form2'] . '["classList"]["add"]("' . $T['HC_checked'] . '");
					});
					' . $T['HC_add_script'] . '();
				};
				function ' . $T['ENC_HC_Set'] . '(' . $T['form1'] . ') {
					if (!' . $T['form1'] . '["classList"]["contains"]("' . $T['HC_checked'] . '")) {
						' . $T['form1'] . '["classList"]["add"]("' . $T['HC_checked'] . '");
						let d = document;
						let ' . $T['HC'] . ' = d["createElement"]("div");
						' . $T['HC'] . '["setAttribute"]("class", "h-captcha");
						' . $T['HC'] . '["setAttribute"]("data-sitekey", "' . $this->_ENC_setting['hCaptcha_key'] . '");
						' . $T['form1'] . '["appendChild"](' . $T['HC'] . ');
					};
				};
			';
			return $result;

		}
		/**********************/
		private function CheckHCaptcha()
		{

			$result = false;
			if ($this->_ENC_setting['hCaptcha_key'] == '') {
				$result = true;
			} else if (isset($_REQUEST['h-captcha-response'])) {
				$data = array(
					'secret' => $this->_ENC_setting['hCaptcha_SecretKey'],
					'response' => $_REQUEST['h-captcha-response']
				);
				$verify = curl_init();
				curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
				curl_setopt($verify, CURLOPT_POST, true);
				curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
				curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($verify);
				$responseData = json_decode($response);
				if ($responseData->success) {
					$result = true;
				};
			};
			return $result;
		}
		/**********************/
		/* functions */
		/**********************/
		private function AddToShuffle($text)
		{
			$this->_ENC_shuffle[] = $text;
		}

		/**********************/
		private function ShuffleText()
		{
			$result = '';
			foreach ($this->_ENC_shuffle as $t) {
				if (rand(0, 100) < 50) {
					$result .= $t;
				} else {
					$result = $t . $result;
				}
			}
			return $result;
		}
		/**********************/
		private function EncodeJsString($string, $level = 0)
		{

			$result = '';
			$needEncode =  $this->_ENC_setting['encode'];
			if ($level >= 3) {
				$needEncode = false;
			}
			if (!$needEncode) {
				$result = $string;
			}

			while ($needEncode) {
				$needEncode = false;
				$i = 0;
				while ($i <= strlen($string)) {
					$char = substr($string, $i, 1);
					if (($i > 0) and ($i < strlen($string) - 1)) {
						if ((rand(0, 100) < 20) || ($char == '<')) {
							$d = dechex(ord($char));
							$c = (strlen($d) == 1) ? '0' . $d : $d;
							$char = '\x' . $c;
						} else if (rand(0, 100) < 20) {
							$char = '"+/*' . substr(md5(uniqid()), 0, rand(1, 32)) . '*/"' . $char;
						} else if (((rand(0, 100) < 20) && ($level < 3)) && (true)) {

							$val = substr('enc' . md5(uniqid()), 0, rand(10, 32));
							$functionName = substr('enc' . md5(uniqid()), 0, rand(10, 32));
							if ((is_array($this->_ENC_AllReadyFunc)) && (count($this->_ENC_AllReadyFunc) > 0)) {
								while (in_array($functionName, $this->_ENC_AllReadyFunc)) {
									$functionName = substr('bf' . md5(uniqid()), 0, rand(10, 32));
								};
							};
							$this->_ENC_AllReadyFunc[] = $functionName;
							$c = rand(0, strlen($string) - 2 - $i);
							if ($c > 0) {
								$char = substr($string, $i, $c);
								$i += $c - 1;
								$functiontext =  'function ' . $functionName . '(){return "' . $this->EncodeJsString($char, $level + 1) . '";};';
								$char = '"+' . $functionName . '()+"';
								$this->AddToShuffle($functiontext);
							};
						};
					};
					$i++;
					$result .= $char;
				}
			}
			return $result;
		}


		/**********************/
		private function addCryptWord($str)
		{
			$val = $str;
			if ($this->_ENC_setting['encode']) {
				$val = 't' . substr(md5(uniqid()), 0, rand(10, 32));
				if ((is_array($this->_ENC_string)) && (count($this->_ENC_string) > 0)) {
					while (in_array($val, $this->_ENC_string)) {
						$val = substr(md5(uniqid()), 0, rand(10, 32));
					};
				};
			};
			$this->_ENC_string[$str] = $val;
		}
		/**********************/
		private function getCryptWord($str)
		{
			return $this->_ENC_string[$str];
		}
		/**********************/
		private function str_replace_once($search, $replace, $text)
		{
			$pos = strpos($text, $search);
			return $pos !== false ? substr_replace($text, $replace, $pos, strlen($search)) : $text;
		}


	}
};
