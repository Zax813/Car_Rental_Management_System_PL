<meta charset="utf-8" />
<link rel="stylesheet" href="StyleHtml.css">

<?php
if ($_SESSION['perm'] == 'admin' || $_SESSION['perm'] == 'kierownik') {
?>
	<h2 class='form-outline mx-5 my-2'>Dodaj pracownika</h2>

	<div class='form-outline mx-5 d-flex justify-content-center'>
		<form class='form-horizontal' method='post' action='index.php?action=userAdd'>

			<div class='form-group mb-2'>
				<label class='control-label' for='imie'>Imię</label>
				<div class='controls'>
					<input id='imie' type='text' name='imie' value='<?php echo $fields['imie'] ?>' />
					<?php if (array_key_exists('imie', $errors)) : ?><span><?php echo $errors['imie'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='nazwisko'>Nazwisko</label>
				<div class='controls'>
					<input id='naziwsko' type='text' name='nazwisko' value='<?php echo $fields['nazwisko'] ?>' />
					<?php if (array_key_exists('nazwisko', $errors)) : ?><span><?php echo $errors['nazwisko'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='login'>Login</label>
				<div class='controls'>
					<input id='login' type='text' name='login' value='<?php echo $fields['login'] ?>' />
					<?php if (array_key_exists('login', $errors)) : ?><span><?php echo $errors['login'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='haslo'>Hasło</label>
				<div class='controls'>
					<input id='haslo' type='password' name='haslo' value='' />
					<?php if (array_key_exists('haslo', $errors)) : ?><span><?php echo $errors['haslo'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='powtorzhaslo'> Powtórz hasło</label>
				<div class='controls'>
					<input id='powtorzhaslo' type='password' name='powtorzhaslo' value='' />
					<?php if (array_key_exists('powtorzhaslo', $errors)) : ?><span><?php echo $errors['powtorzhaslo'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='uprawnienia'>Uprawnienia</label>
				<div class='controls'>
				<select id='uprawnienia' name='uprawnienia'>
					<option>admin</option>
					<option>kierownik</option>
					<option selected>pracownik</option>
				</select>
				<?php if (array_key_exists('uprawnienia', $errors)) : ?><span><?php echo $errors['uprawnienia'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='telefon'>Telefon</label>
				<div class='controls'>
				<input id='telefon' type='number' name='telefon' value='<?php echo $fields['telefon'] ?>' />
				<?php if (array_key_exists('telefon', $errors)) : ?><span><?php echo $errors['telefon'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='email'>E-mail</label>
				<div class='controls'>
				<input id='email' type='email' name='email' value='<?php echo $fields['email'] ?>' />
				<?php if (array_key_exists('email', $errors)) : ?><span><?php echo $errors['email'] ?></span><?php endif; ?>
			</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label'></label>
				<input type='submit' class='btn btn-info' value='Zatwierdź' name='acceptAdd' />
			</div>

			<div class='form-group mb-2'>
				<?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
				<span class='ok'><?php echo $info; ?></span>
			</div>

		</form>
	</div>
<?php
}
?>