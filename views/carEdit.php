<h2 class='form-outline mx-5 my-2'>Edytowanie samochodu</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=carEdit&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<?php if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") { ?>
<!-- Formularz wpisywania nowego serwisu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form class='form-horizontal' action="index.php?action=carEdit" id="carEditForm" method="post">

        <div class="row mb-2">
            <!-- Pole wyboru zdjęcia samochodu -->
            <div class='col-md-8 mb-2'>
                <label class='control-label' for="zdjecie">Zdjęcie:</label>
                <div class='controls'>
                    <select class="form-select col-md-12" id="zdjecie" name="zdjecie">
                        <option value="">--Wybierz--</option>
                        <?php
                        foreach ($zdjecia as $row) {
                            echo "<option value={$row['idzdjecie']}";
                            if ($fields['zdjecie'] == $row['idzdjecie']) {
                                echo " selected";
                            }
                            echo ">{$row['tytul']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class='col-md-4 mt-4'>
                <div class='controls'>
                    <input type='submit' class='btn btn-info btn-md col-md-12' id='addPhoto' value='Dodaj Zdjęcie' name='addPhoto' />
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <!-- Pole wpisywania marki -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="marka" >Marka:</label>
                <div class='controls'>
                    <input type="text" class='col-md-12' id="marka" name="marka" value="<?php echo $fields['marka']; ?>" disabled>
                </div>
            </div>

            <!-- Pole wpisywania modelu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="model">Model:</label>
                <div class='controls'>
                    <input type="text" class='col-md-12' id="model" name="model" value="<?php echo $fields['model']; ?>" disabled>
                </div>
            </div>
            <?php if (array_key_exists('marka', $errors)) : ?><span><?php echo $errors['marka'] ?></span><?php endif; ?>
            <?php if (array_key_exists('model', $errors)) : ?><span><?php echo $errors['model'] ?></span><?php endif; ?>
        </div>

        <div class="row mb-2">
            <!-- Pole wpisywania numeru vin -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="vin" disabled>VIN:</label>
                <div class='controls'>
                    <input type="text" class="col-md-12" id="vin" name="vin" value="<?php echo $fields['vin'] ?>" disabled>
                </div>
            </div>

            <!-- Pole wpisywania numeru rejestracyjnego -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="numer" disabled>Numer rejestracyjny:</label>
                <div class='controls'>
                    <input type="text" class="col-md-12" id="numer" name="numer" value="<?php echo $fields['numer'] ?>">
                </div>
            </div>
            <?php if (array_key_exists('vin', $errors)) : ?><span><?php echo $errors['vin'] ?></span><?php endif; ?>
            <?php if (array_key_exists('numer', $errors)) : ?><span><?php echo $errors['numer'] ?></span><?php endif; ?>
        </div>

        <div class="row mb-2">
            <!-- Pole wpisywania rocznika -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="rok">Rok produkcji:</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="rok" name="rok" min='1900' value="<?php echo $fields['rok'] ?>" disabled>
                </div>
            </div>

            <!-- Pole wpisywania przebiegu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="przebieg">Przebieg:</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="przebieg" name="przebieg" min='0' value="<?php echo $fields['przebieg'] ?>">
                </div>
            </div>

            <?php if (array_key_exists('przebieg', $errors)) : ?><span><?php echo $errors['przebieg'] ?></span><?php endif; ?>
            <?php if (array_key_exists('rok', $errors)) : ?><span><?php echo $errors['rok'] ?></span><?php endif; ?>
        </div>

        <div class="row mb-2">
            <!-- Pole wpisywania ceny doba -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="cenadoba">Cena za dobę (zł):</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="cenadoba" name="cenadoba" step="0.01" min="0" value="<?php echo $fields['cenadoba'] ?>">
                </div>
            </div>

            <!-- Pole wpisywania ceny km -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="cenakm">Cena za km (zł):</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="cenakm" name="cenakm" step="0.01" min="0" value="<?php echo $fields['cenakm'] ?>">
                </div>
            </div>
            <?php if (array_key_exists('cenadoba', $errors)) : ?><span><?php echo $errors['cenadoba'] ?></span><?php endif; ?>
            <?php if (array_key_exists('cenakm', $errors)) : ?><span><?php echo $errors['cenakm'] ?></span><?php endif; ?>
        </div>


        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dostepny" id="dostepny" value='true' <?php if($fields['dostepny']=='true'){ echo " checked";} ?>/>
            <label class="form-check-label" for="flexCheckDefault">
                Dostępny
            </label>
        </div>

        <div class="form-check">
        <input class="form-check-input" type="checkbox" name="sprawny" id="sprawny" value='true' <?php if($fields['sprawny']=='true'){ echo " checked";} ?>/>
            <label class="form-check-label" for="flexCheckChecked">
                Sprawny
            </label>
        </div>

        <div class="form-check">
        <input class="form-check-input" type="checkbox" name="aktywny" id="aktywny" value='true' <?php if($fields['aktywny']=='true'){ echo " checked";} ?>/>
            <label class="form-check-label" for="flexCheckChecked">
                Aktywny
            </label>
        </div>


        <!-- Pole wpisywania uwag -->
        <div class='form-group mb-2'>
            <label class='control-label' for="uwagi">Uwagi do samochodu:</label>
            <div class='controls'>
                <textarea class="col-md-12" id="uwagi" name="uwagi" rows="5" cols="23"></textarea>
            </div>
        </div>

        <!-- Przycisk zatwierdzający -->
        <div class='form-group my-4'>
            <label class='control-label'></label>
            <input type='submit' class='btn btn-info col-md-12' value='Zatwierdź' id='acceptAdd' name='acceptAdd' />
        </div>

        <!-- Komunikaty o błędach -->
        <div class='form-group mb-2'>
            <?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
            <span class='ok'><?php echo $info; ?></span>
        </div>

    </form>
</div>

<?php 
} 
?>