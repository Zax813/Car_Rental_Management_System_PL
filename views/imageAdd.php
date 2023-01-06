<h2 class='form-outline mx-5 my-2'>Dodaj zdjęcie</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=carAdd' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz wpisywania nowego serwisu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form action="index.php?action=imageAdd" method="post" enctype="multipart/form-data">
        <div>
        Wybierz zdjęcie do załadowania:
        <input type="file" name="file">
        <input type="submit" name="submit" value="Upload">
        </div>
        <?php if (array_key_exists('zdjecie', $errors)) : ?><span><?php echo $errors['zdjecie'] ?></span><?php endif; ?>

    </form>
</div>