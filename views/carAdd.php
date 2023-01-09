<h2 class='form-outline mx-5 my-2'>Dodawanie samochodu</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=carAdd&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz wpisywania nowego serwisu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form class='form-horizontal' action="index.php?action=carAdd" id="carAddForm" method="post">

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
                <label class='control-label' for="marka">Marka:</label>
                <div class='controls'>
                    <input type="text" class='col-md-12' id="marka" name="marka" value="<?php echo $fields['marka']; ?>">
                </div>
            </div>

            <!-- Pole wpisywania modelu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="model">Model:</label>
                <div class='controls'>
                    <input type="text" class='col-md-12' id="model" name="model" value="<?php echo $fields['model']; ?>">
                </div>
            </div>
            <?php if (array_key_exists('marka', $errors)) : ?><span><?php echo $errors['marka'] ?></span><?php endif; ?>
            <?php if (array_key_exists('model', $errors)) : ?><span><?php echo $errors['model'] ?></span><?php endif; ?>
        </div>

        <div class="row mb-2">
            <!-- Pole wpisywania numeru vin -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="vin">VIN:</label>
                <div class='controls'>
                    <input type="text" class="col-md-12" id="vin" name="vin" maxlength="20" value="<?php echo $fields['vin'] ?>">
                </div>
            </div>

            <!-- Pole wpisywania numeru rejestracyjnego -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="numer">Numer rejestracyjny:</label>
                <div class='controls'>
                    <input type="text" class="col-md-12" id="numer" name="numer" maxlength="10" value="<?php echo $fields['numer'] ?>">
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
                    <input type="number" class="col-md-12" id="rok" name="rok" min="1900" max="3000" value="<?php echo $fields['rok'] ?>">
                </div>
            </div>

            <!-- Pole wpisywania przebiegu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="przebieg">Przebieg:</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="przebieg" name="przebieg" min="0" max="10000000" value="<?php echo $fields['przebieg'] ?>">
                </div>
            </div>

            <?php if (array_key_exists('przebieg', $errors)) : ?><span><?php echo $errors['przebieg'] ?></span><?php endif; ?>
            <?php if (array_key_exists('rok', $errors)) : ?><span><?php echo $errors['rok'] ?></span><?php endif; ?>
        </div>


        <div class="row mb-2">
            <!-- Pole wyboru segmentu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="segment">Segment:</label>
                <div class='controls'>
                    <select class="form-select col-md-12" id="segment" name="segment">
                        <option value="">--Wybierz--</option>
                        <?php
                        foreach ($segmenty as $row) {
                            echo "<option value={$row['idsegment']}";
                            if ($fields['segment'] == $row['idsegment']) {
                                echo " selected";
                            }
                            echo ">{$row['nazwasegment']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Pole wyboru paliwa -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="paliwo">Paliwo:</label>
                <div class='controls'>
                    <select class="form-select col-md-12" id="paliwo" name="paliwo">
                        <option value="">--Wybierz--</option>
                        <?php
                        foreach ($paliwa as $row) {
                            echo "<option value={$row['idpaliwo']}";
                            if ($fields['paliwo'] == $row['idpaliwo']) {
                                echo " selected";
                            }
                            echo ">{$row['nazwapaliwo']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <?php if (array_key_exists('segment', $errors)) : ?><span><?php echo $errors['segment'] ?></span><?php endif; ?>
            <?php if (array_key_exists('paliwo', $errors)) : ?><span><?php echo $errors['paliwo'] ?></span><?php endif; ?>
        </div>

        <div class="row mb-2">
            <!-- Pole wyboru skrzyni biegów -->
            <div class='col-md-4 mb-2'>
                <label class='control-label' for="skrzynia">Skrzynia biegów:</label>
                <div class='controls'>
                    <select class="form-select col-md-12" id="skrzynia" name="skrzynia">
                        <option value="">--Wybierz--</option>
                        <option value="automatyczna" <?php if ($fields['skrzynia'] == 'automatyczna') {
                                                            echo " selected";
                                                        } ?>>automatyczna</option>
                        <option value="półautomatyczna" <?php if ($fields['skrzynia'] == 'półautomatyczna') {
                                                            echo " selected";
                                                        } ?>>półautomatyczna</option>
                        <option value="manualna" <?php if ($fields['skrzynia'] == 'manualna') {
                                                        echo " selected";
                                                    } ?>>manualna</option>
                    </select>
                </div>
            </div>

            <!-- Pole wpisywania mocy silnika -->
            <div class='col-md-4 mb-2'>
                <label class='control-label' for="mockw">Moc silnika (kW):</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="mockw" name="mockw" min='0' max='10000' value="<?php echo $fields['mockw'] ?>">
                </div>
            </div>

            <!-- Pole wpisywania liczby miejsc -->
            <div class='col-md-4 mb-2'>
                <label class='control-label' for="liczbamiejsc">Liczba miejsc:</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="liczbamiejsc" name="liczbamiejsc" min="1" max="9" value="<?php echo $fields['liczbamiejsc'] ?>">
                </div>
            </div>

            <?php if (array_key_exists('skrzynia', $errors)) : ?><span><?php echo $errors['skrzynia'] ?></span><?php endif; ?>
            <?php if (array_key_exists('mockw', $errors)) : ?><span><?php echo $errors['mockw'] ?></span><?php endif; ?>
            <?php if (array_key_exists('liczbamiejsc', $errors)) : ?><span><?php echo $errors['liczbamiejsc'] ?></span><?php endif; ?>
        </div>

        <div class="row mb-2">
            <!-- Pole wpisywania ceny doba -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="cenadoba">Cena za dobę (zł):</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="cenadoba" name="cenadoba" step="0.01" min="0" max="10000000" value="<?php echo $fields['cenadoba'] ?>">
                </div>
            </div>

            <!-- Pole wpisywania ceny km -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="cenakm">Cena za km (zł):</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="cenakm" name="cenakm" step="0.01" min="0" max="10000000" value="<?php echo $fields['cenakm'] ?>">
                </div>
            </div>
            <?php if (array_key_exists('cenadoba', $errors)) : ?><span><?php echo $errors['cenadoba'] ?></span><?php endif; ?>
            <?php if (array_key_exists('cenakm', $errors)) : ?><span><?php echo $errors['cenakm'] ?></span><?php endif; ?>
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
            <input type='submit' class='btn btn-info col-md-12' value='Zatwierdź' name='acceptAdd' />
        </div>

        <!-- Komunikaty o błędach -->
        <div class='form-group mb-2'>
            <?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
            <span class='ok'><?php echo $info; ?></span>
        </div>

    </form>
</div>

<script id="autocomplete-script">
    $(function() {
        $("#marka").autocomplete({
            source: <?php echo getBrandData($db) ?>
        });
    });

    const markaIModele = <?php echo getModelData($db) ?>;

    $(function() {
        $("#model").autocomplete({
            source: markaIModele.map(row => row.model)
        });
    });
</script>