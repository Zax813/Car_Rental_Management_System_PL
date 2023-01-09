<meta charset="utf-8" />

<?php
if ($_SESSION['perm'] == 'admin' || $_SESSION['perm'] == 'kierownik') {
?>
	<h2 class='form-outline mx-5 my-2'>Edytuj pracownika</h2>

	<div class='form-outline mx-5 my-2'>
		<a class='btn btn-info btn-sm' href='index.php?action=userEdit&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
	</div>

	<div class='form-outline mx-5 d-flex justify-content-center'>
		<form class='form-horizontal' method="post" action="index.php?action=userEdit">

			<div class='form-group mb-2'>
				<label class='control-label' for='imie'>Imię</label>
				<div class='controls'>
					<input id='imie' type="text" name="imie" value="<?php echo $fields['imie'] ?>" />
					<?php if (array_key_exists('imie', $errors)) : ?><span><?php echo $errors['imie'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='nazwisko'>Nazwisko</label>
				<div class='controls'>
					<input id='nazwisko' type="text" name="nazwisko" value="<?php echo $fields['nazwisko'] ?>" />
					<?php if (array_key_exists('nazwisko', $errors)) : ?><span><?php echo $errors['nazwisko'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='login'>Login</label>
				<div class='controls'>
					<input id='login' type="text" name="login" value="<?php echo $fields['login'] ?>" />
					<?php if (array_key_exists('login', $errors)) : ?><span><?php echo $errors['login'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='haslo'>Hasło</label>
				<div class='controls'>
					<input id='haslo' type="password" name="haslo" value='' <?php echo $fields['disabledSelect']; ?>/>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='uprawnienia'>Uprawnienia</label>
				<div class='controls'>
					<select id='uprawnienia' name="uprawnienia" <?php echo $fields['disabledSelect']; ?>>
					<?php if($fields['disabled'] == FALSE)
					{?>
						<option value="admin" <?php if ($fields['uprawnienia'] == 'admin') {
													echo " selected";
												} ?>>admin</option>
					<?php } ?>
						<option value="kierownik" <?php if ($fields['uprawnienia'] == 'kierownik') {
														echo " selected";
													} ?>>kierownik</option>
						<option value="pracownik" <?php if ($fields['uprawnienia'] == 'pracownik') {
														echo " selected";
													} ?>>pracownik</option>
					</select>
					<?php if (array_key_exists('uprawnienia', $errors)) : ?><span><?php echo $errors['uprawnienia'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='telefon'>Telefon</label>
				<div class='controls'>
					<input id='telefon' type="number" name="telefon" value="<?php echo $fields['telefon'] ?>" />
					<?php if (array_key_exists('telefon', $errors)) : ?><span><?php echo $errors['telefon'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label class='control-label' for='email'>E-mail</label>
				<div class='controls'>
					<input id='email' type="email" name="email" value="<?php echo $fields['email'] ?>" />
					<?php if (array_key_exists('email', $errors)) : ?><span><?php echo $errors['email'] ?></span><?php endif; ?>
				</div>
			</div>

			<div class='form-group mb-2'>
				<label>Zatrudniony</label>
				<input class='mx-2' type="checkbox" name="zatrudniony" value='true' <?php if ($fields['zatrudniony'] == 'true') {
																						echo " checked";
																					} ?> />
			</div>

			<div class='form-group my-2'>
				<input class='btn btn-primary' type="submit" value="Zatwierdź" name="accept" />
				<?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
			</div>
		</form>

	<?php
}
	?>