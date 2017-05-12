# EasyNoCaptcha
Uses

1) Set Captcha

- add Jquery on your page (see https://jquery.com/ )
- add to start file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php include_once('EasyNoCaptcha.php');?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
- add before close tag body
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php echo SetEasyNoCaptcha(); ?>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


2) Check Captcha

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if (CheckEasyNoCaptha ()) {
   echo '<p>You are human</p>';
} else {
   echo '<p>You are robot</p>';
} 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~        
