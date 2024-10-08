<h2 class='form-outline mx-5 my-2'>Dodaj zdjęcie</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=imageAdd&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz wpisywania nowego serwisu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form action="index.php?action=imageAdd" method="post" enctype="multipart/form-data">
        <div class='form-group mb-2'>
        <label class='control-label my-2' for='file'>Wybierz zdjęcie do załadowania:</label>
            <div class='controls'>
                <input type="file" name="file" id="file">
            </div>
        </div>
        <div class='d-flex justify-content-center'>
            <input class="my-2" type="submit" name="submit" value="Prześlij">
        </div>
        <?php if (array_key_exists('zdjecie', $errors)) : ?><span><?php echo $errors['zdjecie'] ?></span><?php endif; ?>
        <span class='ok'><?php echo $info; ?></span>
    </form>
</div>