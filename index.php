<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Rach - Mailer</title>
  <link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" rel="stylesheet">
  </head>

</body>
<div class="container">
<h1>Rach - Mailer</h1>
<form method="post" action=".">
<?php
	error_reporting(~E_NOTICE);
	session_start();
	$ini['mail'] = "slim";
	$ini['url'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?otp=";
	$ini['maillist'] = "/etc/rach.mails";

	$mails = fopen($ini['maillist'], 'r');
	if ($_GET['otp'] && $_SESSION['otp'] == $_GET['otp']) {
		$_SESSION['verified'] = TRUE;
		unset($_SESSION['otp']);
	}
	if ($_SESSION['verified']) {
		if ($_POST['message']) {
			while ($m = fgets($mails)) {
				list($name) = explode('@', $m);
				$from = $_POST['from'];
				$replyto = $_SESSION['from'];
				mail($m, $_POST['subject'], $_POST['message'], "From: $from\r\nReply-to: $replyto");
				print " $name ";
			}
			print "<div class='alert alert-success'>Votre message a été envoyé</div>";
			unset($_SESSION['verified']);
		} else {
			$from = $_SESSION['from'];
			print "

<label>From</label> 
<input type='text' name='from' value='$from' />
<label>Subject</label> 
<input type='text' name='subject' class='input-block-level' />
<label>Message</label>
<textarea name='message' class='input-block-level' rows='13'></textarea>
<button type='submit' class='btn'>Envoyer</button>

";
		}
	} else {
		if ($_POST['from']) {
			$_SESSION['from'] = $_POST['from'];
			$_SESSION['otp']= bin2hex(openssl_random_pseudo_bytes(24));
			mail($_POST['from'], "Rach - Autorisation", "Accedez à ce lien pour être autorisé à envoyer un mailing : ".$ini['url'].$_SESSION['otp'], "From: ". $ini['mail']);
			print "<div class='alert alert-success'><h4>Veuillez vérifier votre boite mail</h4> Vous allez recevoir un email d'autorisation</div>";
		} else {
			print "
<p>Saisissez votre email pour vous autoriser a envoyer un mailing</p>
<div class='input-append'>
<input type='text' name='from' placeholder='Votre email' />
<button type='submit' class='btn'>Autoriser</button>
</div>
";
		}
	}
?>
</form>
</div>
</body>
</html>
