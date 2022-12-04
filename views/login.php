<meta charset="utf-8" />


<h2>Logowanie</h2>

<form method="post" action="index.php?action=login">
	<div class="text-center container mt-5">

		<div class="form-outline mb-3">
			<label>Login</label>
			<input type="text" name="username" value="<?php echo $fields['username'] ?>" />
			<?php if (array_key_exists('username', $errors)) : ?><span><?php echo $errors['username'] ?></span><?php endif; ?>
		</div>

		<div class="form-outline mb-3">
			<label>Hasło </label>
			<input type="password" name="password" value="" />
			<?php if (array_key_exists('password', $errors)) : ?><span><?php echo $errors['password'] ?></span><?php endif; ?>
		</div>

		<div class="form-outline mb-1">

			<input type="submit" class="btn btn-primary btn-block mb-1" value="Zaloguj" name=" Zaloguj " />
		</div>
		<div class="form-outline mb-0">
			<?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
		</div>

	</div>
</form>