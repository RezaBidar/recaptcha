<?php 

$site_key = '6b1dc33a8fd845100d17e80890f73a15' ;
$secret_key = '3a4751c094ff5f51c9b598f047e85fa8' ;
$lang = 'fa' ;
$word_method = 'random' ;
$js_url = 'http://localhost/fanavard/captcha/public_html/api/captcha/js' ;
$validation_url = 'http://localhost/fanavard/captcha/public_html/api/captcha/siteverify/' ;

if(isset($_POST['submit'])){
	$answer = file_get_contents( $validation_url . '?secret='.$secret_key.'&response=' . $_POST['cp-response'] . '&id=' . $_POST['cp-id'] );
	if($answer == 'yes')
		var_dump('your answer was correct') ;
	else
		var_dump('you say WRONG !!!');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Captcha Tester</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}


	#body {
		margin: 0 15px 0 15px;
	}


	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Captcha test</h1>

	<div id="body">
		<form method="post" action="#" >
		<div data-sitekey="<?php echo $site_key ?>" data-lang="<?php echo $lang ?>" data-method="<?php echo $word_method ?>" class="cp-div"></div>
		<input type="submit" value="submit" name="submit" >
		</form>
	</div>

	<p class="footer"></p>
</div>

<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo $js_url ?>"></script>


</body>
</html>