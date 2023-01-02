<h2 class='form-outline mx-5 my-2'>Błąd Anulowania</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=rentFinal&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<div class='form-outline mx-5 d-flex justify-content-center'>

    <!-- Pole wpisywania uwag -->
    <div class='form-group mb-2'>
        <label class='control-label' for="uwagi">Uwagi do wypożyczenia:</label>
        <div class='controls'>
            <textarea class="col-md-12" id="uwagi" name="uwagi" rows="20" cols="23" disabled><?php echo $errors['cancel'];?></textarea>
        </div>
    </div>

</div>