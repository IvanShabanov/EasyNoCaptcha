<?php include_once('EasyNoCaptcha.php');?>
<!DOCTYPE html>
<html>
	<head>
		<title>EasyNoCaptcha test</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
    <?php 
      if (isset($_REQUEST['text'])) { 
        if (CheckEasyNoCaptha ()) {
            echo '<p>You are human</p>';
        } else {
            echo '<p>You are robot</p>';
        } 
        echo '<p>POSTED DATA</p>';
        foreach ($_REQUEST as $key=>$val) {
          echo '<p>'.$key.' => '.$val.'</p>';
        }
      }
    ?>
    <form action="" method="post">
      <input type="text" name="text">
      <input type="text" name="text2">
      <input type="submit" value="send">
    </form>
    <?php echo SetEasyNoCaptcha(); ?>
	</body>
</html>