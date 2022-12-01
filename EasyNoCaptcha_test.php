<?php include_once('EasyNoCaptcha.php');?>
<?

$ENC = new ENCv3();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>EasyNoCaptcha test</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
	</head>
	<body>
		<?php
			if (isset($_REQUEST['text'])) {
				if ($ENC->CheckEasyNoCaptha ()) {
						echo '<p>You are <b>human</b></p>';
				} else {
						echo '<p>Oooo NO!!! You are <b>robot</b></p>';
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
		<?php


		echo $ENC->SetEasyNoCaptcha();
		?>

	</body>
</html>
