<h2 class='form-outline mx-5 my-2'>Dodaj przegląd</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=carInspectionAdd&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz wpisywania nowego przeglądu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form class='form-horizontal' action="index.php?action=carInspectionAdd" method="post">

    <div class="row mb-2">
        <!-- Pole wpisywania daty przeglądu -->
        <div class='col-md-6 mb-2'>
            <label class='control-label' for="dataprzeglad">Data przeglądu:</label>
            <div class='controls'>
                <input type="date" class='col-md-12'  id="dataprzeglad" name="dataprzeglad" value="<?php echo $today; ?>">
            </div>
        </div>

        <!-- Pole wpisywania daty ważności przeglądu -->
        <div class='col-md-6 mb-2'>
            <label class='control-label' for="waznosc">Data ważności:</label>
            <div class='controls'>
                <input type="date" class='col-md-12' id="waznosc" name="waznosc" value="<?php echo $nextYear; ?>">
            </div>
        </div>
    </div>

        <!-- Pole wpisywania numeru rejestracyjnego -->
        <div class='form-group mb-2'>
            <label class='control-label' for="numer">Numer rejestracyjny:</label>
            <div class='controls'>
                <input type="text" class="col-md-12" id="numer" name="numer">
                <?php if (array_key_exists('numer', $errors)) : ?><span><?php echo $errors['numer'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wpisywania uwag -->
        <div class='form-group mb-2'>
            <label class='control-label' for="uwagi">Uwagi:</label>
            <div class='controls'>
                <textarea class="col-md-12" id="uwagi" name="uwagi" rows="5" cols="23"></textarea>
            </div>
        </div>

        <div class='form-group my-4'>
            <label class='control-label'></label>
            <input type='submit' class='btn btn-info col-md-12' value='Zatwierdź' name='acceptAdd' />
        </div>

        <div class='form-group mb-2'>
            <?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
            <span class='ok'><?php echo $info; ?></span>
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