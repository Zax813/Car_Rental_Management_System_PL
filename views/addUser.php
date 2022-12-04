<meta charset="utf-8"/>
<link rel="stylesheet" href="StyleHtml.css">

<?php
if($_SESSION['perm']=='admin' || $_SESSION['perm']=='kierownik')
{
?>
<h2>Dodaj pracownika</h2>

<form method="post" action="index.php?action=addUser">

	<fieldset>
		<ul>
        <li>
			<label>Imię</label>
			<input type="text" name="imie" value="<?php echo $fields['imie'] ?>" />
            <?php if (array_key_exists('imie', $errors)): ?><span><?php echo $errors['imie'] ?></span><?php endif; ?>
		</li>
        <li>
			<label>Nazwisko</label>
			<input type="text" name="nazwisko" value="<?php echo $fields['nazwisko'] ?>" />
            <?php if (array_key_exists('nazwisko', $errors)): ?><span><?php echo $errors['nazwisko'] ?></span><?php endif; ?>
		</li>
		<li>
			<label>Login</label>
			<input type="text" name="login" value="<?php echo $fields['login'] ?>" />
            <?php if (array_key_exists('login', $errors)): ?><span><?php echo $errors['login'] ?></span><?php endif; ?>
		</li>
		<li>
			<label>Hasło</label>
			<input type="password" name="haslo" value="" />
            <?php if (array_key_exists('haslo', $errors)): ?><span><?php echo $errors['haslo'] ?></span><?php endif; ?>
		</li>
        <li>
			<label>Stanowisko</label>
            <select name="stanowisko">
                <option>admin</option>
				<option>kierownik</option>
				<option selected>pracownik</option>
            </select>
			<?php if (array_key_exists('stanowisko', $errors)): ?><span><?php echo $errors['stanowisko'] ?></span><?php endif; ?>
		</li>
		<li>
			<label>Telefon</label>
			<input type="number" name="telefon" value="<?php echo $fields['telefon'] ?>"/>
			<?php if (array_key_exists('telefon', $errors)): ?><span><?php echo $errors['telefon'] ?></span><?php endif; ?>
		</li>
		<li>
			<label>E-mail</label>
			<input type="email" name="email" value="<?php echo $fields['email'] ?>"/>
			<?php if (array_key_exists('email', $errors)): ?><span><?php echo $errors['email'] ?></span><?php endif; ?>
		</li>
		<li><label></label></li>
		<li>
			<label></label>
			<input type="submit" value="Zatwierdź" name="acceptAdd"/>
			<?php if (array_key_exists('all', $errors)): ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
			<span class="ok"><?php echo $info;?></span>
		</li>
		</ul>
	</fieldset>

</form>
<?php
}
?>