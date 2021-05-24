# EasyNoCaptcha
Uses

1) Set Captcha

- add to start file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php include_once('EasyNoCaptcha.php');?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- Constructor
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php $ENC = new ENCv2;;?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- If use Google reCaptcha v3 (only v3 !)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php $ENC->AddGoogleRecaptcha("key", "secret_key");?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- to set on all forms invisible captcha - add before close tag body
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php echo $ENC->SetEasyNoCaptcha(); ?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
or
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php echo $ENC->SetEasyNoCaptcha([protection_level], [form_selector]); ?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
protection_level - is number from 1 to infinity, the minimum amount of action to determine that the form is filled by human, not a robot. The default is 3.

form_selector -  is string to set captcha on only this form. The default is "form".

2) Check Captcha

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ($ENC->CheckEasyNoCaptha ()) {
   echo '<p>You are human</p>';
} else {
   echo '<p>You are robot</p>';
}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
