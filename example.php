<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Crazy-Captcha-PHP by Igor Escobar</title>
</head>
<body>
	<?php
	if ( ! $_POST ):
	?>
    <h1>Crazy-Captcha-PHP</h1>
	<form action="" method="post">
		<fieldset>
			<legend>Verificação</legend>
			<img src="captcha.php?<?php echo uniqid() ?>" /> <br />
			
			<label for="">O que você vê?</label>
			<input type="text" name="captcha-code" value=""> <input type="submit" name="bt" value="Validar" />
		</fieldset>
	</form>
	<?php
	else:
		
		/**
		 * Exemplo de validação
		 */
		session_start();
		
		if ( $_SESSION ['ahctpac'] == md5 ( strtoupper ( $_POST['captcha-code'] ) ) ):
			echo "That's right!"; 
		else:
			echo "Try again :(";
		endif;
		
	endif;
	?>
</body>
</html>