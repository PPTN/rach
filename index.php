<form method="post" action=".">
<?php
	session_start();
	$ini['mail'] = "slim";
	$ini['url'] = "http://localhost/slim/rach/?otp=";
	$mails = array('slim');
	if ($_GET['otp'] && $_SESSION['otp'] == $_GET['otp']) {
		$_SESSION['verified'] = TRUE;
		unset($_SESSION['otp']);
	}
	if ($_SESSION['verified']) {
		if ($_POST['message']) {
			foreach ($mails as $m) {
				mail($m, $_POST['subject'], $_POST['message'], "From: ". $_POST['from']);
			}
			unset($_SESSION['verified']);
		} else {
			$from = $_SESSION['from'];
			print "

From <input type='text' name='from' value='$from' />
Subject <input type='text' name='subject' />
<textarea name='message'></textarea>
<button type='submit'>Envoyer</button>

";
		}
	} else {
		if ($_POST['from']) {
			$_SESSION['from'] = $_POST['from'];
			$_SESSION['otp']= bin2hex(openssl_random_pseudo_bytes(24));
			mail($_POST['from'], "Rach - Vérification de votre Email", "Accedez à ce lien pour vérifier votre emai : ".$ini['url'].$_SESSION['otp'], "From: ". $ini['mail']);
		} else {
			print "Email <input type='text' name='from' /><button type='submit'>Verifier</button>";
		}
	}
?>
</form>
