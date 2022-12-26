<h2 class='form-outline mx-5 my-2'>Edytuj Serwis</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=carServiceEdit&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz wpisywania nowego serwisu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form class='form-horizontal' action="index.php?action=carServiceEdit" method="post">

        <div class="row mb-2">
            <!-- Pole wpisywania daty serwisu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="datapoczatek">Data rozpoczęcia:</label>
                <div class='controls'>
                    <input type="date" class='col-md-12' id="datapoczatek" name="datapoczatek" value="<?php echo $fields['datapoczatek'] ?>">
                </div>
            </div>

            <!-- Pole wpisywania daty ważności serwisu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="datakoniec">Data końca:</label>
                <div class='controls'>
                    <input type="date" class='col-md-12' id="datakoniec" name="datakoniec" value="<?php echo $fields['datakoniec'] ?>">
                </div>
            </div>
        </div>

        <!-- Pole wpisywania numeru rejestracyjnego -->
        <div class='form-group mb-2'>
            <label class='control-label' for="numer">Numer rejestracyjny:</label>
            <div class='controls'>
                <input type="text" class="col-md-12" id="numer" name="numer" value="<?php echo $fields['numer'] ?>">
                <?php if (array_key_exists('numer', $errors)) : ?><span><?php echo $errors['numer'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wpisywania nazwy serwisu -->
        <div class='form-group mb-2'>
            <label class='control-label' for="nazwaserwis">Nazwa serwisu:</label>
            <div class='controls'>
                <input type="text" class="col-md-12" id="nazwaserwis" name="nazwaserwis" value="<?php echo $fields['nazwaserwis'] ?>">
                <?php if (array_key_exists('nazwaserwis', $errors)) : ?><span><?php echo $errors['nazwaserwis'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wpisywania opisu naprawy -->
        <div class='form-group mb-2'>
            <label class='control-label' for="opis">Opis naprawy:</label>
            <div class='controls'>
                <textarea class="col-md-12" id="opis" name="opis" rows="5" cols="23"><?php echo $fields['opis'] ?></textarea>
                <?php if (array_key_exists('opis', $errors)) : ?><span><?php echo $errors['opis'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wpisywania uwag -->
        <div class='form-group mb-2'>
            <label class='control-label' for="uwagi">Uwagi:</label>
            <div class='controls'>
                <textarea class="col-md-12" id="uwagi" name="uwagi" rows="5" cols="23"><?php echo $fields['uwagi'] ?></textarea>
            </div>
        </div>

        <!-- Pole wpisywania nazwy serwisu -->
        <div class='form-group mb-2'>
            <label class='control-label' for="koszt">Koszt:</label>
            <div class='controls'>
                <input type="number" class="col-md-12" id="koszt" name="koszt" step="0.01" min="0" placeholder="0.00" value="<?php echo $fields['koszt'] ?>">
                <?php if (array_key_exists('koszt', $errors)) : ?><span><?php echo $errors['koszt'] ?></span><?php endif; ?>
            </div>
        </div>

        <div class='form-group my-4'>
            <label class='control-label'></label>
            <input type='submit' class='btn btn-info col-md-12' value='Zatwierdź' name='acceptAdd' />
        </div>

        <div class='form-group mb-2'>
            <?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
        </div>

    </form>
</div>

<script>
    $(function() {
        $("#numer").autocomplete({
            source: <?php echo getData($db) ?>
        });
    });
</script>