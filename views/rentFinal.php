<h2 class='form-outline mx-5 my-2'>Finalizacja wypożyczenia</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=rentFinal&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz finalizowania wypożyczenia -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form class='form-horizontal' action="index.php?action=rentFinal" id="rentFinalForm" method="post">

        <div class="row mb-2">
            <!-- Pole daty rozpoczęcia wypożyczenia -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="datapoczatek">Data rozpoczęcia:</label>
                <div class='controls'>
                    <input type="date" class='col-md-12' id="datapoczatek" name="datapoczatek" value="<?php echo $fields['datapoczatek']; ?>" disabled>
                </div>
            </div>

            <!-- Pole wpisywania daty końca wypożyczenia -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="datakoniec">Data końca:</label>
                <div class='controls'>
                    <input type="date" class='col-md-12' id="datakoniec" name="datakoniec" <?php echo "min={$fields['datapoczatek']} max={$today} value={$fields['datakoniec']}"?>>
                </div>
            </div>
            <?php if (array_key_exists('datakoniec', $errors)) : ?><span><?php echo $errors['datakoniec'] ?></span><?php endif; ?>
        </div>

        <!-- Pole wpisywania numeru rejestracyjnego -->
        <div class='form-group mb-2'>
            <label class='control-label' for="samochod">Samochód:</label>
            <div class='controls'>
                <input type="text" class="col-md-12" id="samochod" name="samochod" value="<?php echo $fields['samochod'] ?>" disabled>
            </div>
        </div>

        <!-- Pole wpisywania numeru rejestracyjnego -->
        <div class='form-group mb-2'>
            <label class='control-label' for="numer">Numer rejestracyjny:</label>
            <div class='controls'>
                <input type="text" class="col-md-12" id="numer" name="numer" value="<?php echo $fields['numer'] ?>" disabled>
            </div>
        </div>

        <!-- Pole wpisywania numeru rejestracyjnego -->
        <div class="row mb-2">
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="przebiegstart">Przebieg startowy:</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="przebiegstart" name="przebiegstart" value="<?php echo (int) $fields['przebiegstart'] ?>" disabled>
                </div>
            </div>

            <div class='col-md-6 mb-2'>
                <label class='control-label' for="przebiegkoniec">Przebieg końcowy:</label>
                <div class='controls'>
                    <input type="number" class="col-md-12" id="przebiegkoniec" name="przebiegkoniec" min="<?php echo (int) $fields['przebiegstart'] ?>" value="<?php echo (int) $fields['przebiegkoniec'] ?>">
                </div>
            </div>
            <?php if (array_key_exists('przebiegkoniec', $errors)) : ?><span><?php echo $errors['przebiegkoniec'] ?></span><?php endif; ?>
        </div>

        <!-- Pole wpisywania telefonu i wyszukiwania klienta po nim-->
        <div class='row mb-2'>
            <div class='col-md-6 my-2'>
                <label class='control-label' for='telefon'>Telefon</label>
                <div class='controls'>
                    <input type='number' class="col-md-12" id='telefon' name='telefon' value='<?php echo $fields['telefon'] ?>' disabled/>
                </div>
            </div>

            <div class='col-md-6 my-2'>
                <label class='control-label' for='email'>E-mail</label>
                <div class='controls'>
                    <input id='email' type='email' class="col-md-12" name='email' value='<?php echo $fields['email'] ?>' disabled />
                </div>
            </div>
            <?php if (array_key_exists('email', $errors)) : ?><span><?php echo $errors['email'] ?></span><?php endif; ?>
        </div>

        <!-- Pole wpisywania nazwy serwisu -->
        <div class='row mb-2'>
            <div class='col-md-6 mb-2'>
                <label class='control-label' for='imie'>Imię</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='imie' name='imie' value='<?php echo $fields['imie'] ?>' <?php echo $fields['disabled'] ?> disabled/>
                    <?php if (array_key_exists('imie', $errors)) : ?><span><?php echo $errors['imie'] ?></span><?php endif; ?>
                </div>
            </div>

            <div class='col-md-6 mb-2'>
                <label class='control-label' for='nazwisko'>Nazwisko</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='nazwisko' name='nazwisko' value='<?php echo $fields['nazwisko'] ?>' <?php echo $fields['disabled'] ?> disabled/>
                    <?php if (array_key_exists('nazwisko', $errors)) : ?><span><?php echo $errors['nazwisko'] ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pole wybrania i wpisania numeru dokumentu -->
        <div class='row mb-2'>
            <label class='control-label' for='rodzajdokumentu'>Dokument</label>
            <div class='col-md-4 mb-2'>

                <select class="col-md-12" name="rodzajdokumentu" disabled >
                    <option value="dowód osobisty" <?php if ($fields['rodzajdokumentu'] == 'dowód osobisty') {
                                                        echo " selected";
                                                    } ?>>dowód osobisty</option>
                    <option value="paszport" <?php if ($fields['rodzajdokumentu'] == 'paszport') {
                                                    echo " selected";
                                                } ?>>paszport</option>
                    <option value="prawo jazdy" <?php if ($fields['rodzajdokumentu'] == 'prawo jazdy') {
                                                    echo " selected";
                                                } ?>>prawo jazdy</option>
                    <option value="inny" <?php if ($fields['rodzajdokumentu'] == 'inny') {
                                                echo " selected";
                                            } ?>>inny</option>
                </select>
                <?php if (array_key_exists('rodzajdokumentu', $errors)) : ?><span><?php echo $errors['rodzajdokumentu'] ?></span><?php endif; ?>
            </div>

            <div class='col-md-8 mb-2'>
                <div class='controls'>
                    <input class="col-md-12" id='nrdokumentu' type='text' name='nrdokumentu' placeholder='Numer dokumentu' value='<?php echo $fields['nrdokumentu'] ?>' disabled />
                    <?php if (array_key_exists('nrdokumentu', $errors)) : ?><span><?php echo $errors['nrdokumentu'] ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <div class='row mb-2'>
            <div class='col-md-8 my-2'>
                <label class='control-label' for='suma'>Suma</label>
                <div class='controls'>
                    <input type='number' class="col-md-12" id='suma' name='suma' step='0.01' value='<?php echo $fields['suma'] ?>' />
                </div>
            </div>
            <div class='col-md-4 mt-4'>
                <input type='submit' class='btn btn-info btn-sm col-md-12' value='Oblicz' name='calcPrice' />
            </div>
            <?php if (array_key_exists('suma', $errors)) : ?><span><?php echo $errors['suma'] ?></span><?php endif; ?>
        </div>

        <div class='form-group mb-2'>
            <label class='control-label' for="obliczenia">Obliczenia:</label>
            <div class='controls'>
                <textarea class="col-md-12" id="obliczenia" name="obliczenia" rows="3" cols="23" disabled><?php echo $fields['obliczenia'] ?></textarea>
            </div>
        </div>

        <!-- Pole wpisywania uwag -->
        <div class='form-group mb-2'>
            <label class='control-label' for="uwagi">Uwagi do wypożyczenia:</label>
            <div class='controls'>
                <textarea class="col-md-12" id="uwagi" name="uwagi" rows="5" cols="23"><?php echo $fields['uwagi'] ?></textarea>
            </div>
        </div>

        <div class='form-group my-4'>
            <label class='control-label'></label>
            <input type='submit' class='btn btn-info col-md-12' value='Zatwierdź' name='acceptFinal' />
        </div>

        <div class='form-group mb-2'>
            <?php if (array_key_exists('all', $errors)) : ?><span><?php echo $errors['all'] ?></span><?php endif; ?>
            <span class='ok'><?php echo $info; ?></span>
        </div>

    </form>
</div>