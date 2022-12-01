# EasyNoCaptcha
Uses

1) Set Captcha

- add Jquery on your page (see https://jquery.com/ )
- add to start file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php include_once('EasyNoCaptcha.php');?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- If use Google reCaptcha
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php AddGoogleRecaptcha("key", "secret_key");?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- to set on all forms invisible captcha - add before close tag body
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php echo SetEasyNoCaptcha(); ?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
or
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php echo SetEasyNoCaptcha([protection_level], [form_selector]); ?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
protection_level - is number from 1 to infinity, the minimum amount of action to determine that the form is filled by human, not a robot. The default is 3.

form_selector -  is string to set captcha on only this form. The default is "form".

2) Check Captcha

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if (CheckEasyNoCaptha ()) {
   echo '<p>You are human</p>';
} else {
   echo '<p>You are robot</p>';
} 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        
