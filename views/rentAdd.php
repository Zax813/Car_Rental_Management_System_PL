<h2 class='form-outline mx-5 my-2'>Dodawanie wypożyczenia</h2>

<div class='form-outline mx-5 my-3'>
    <a class='btn btn-info btn-sm' href='index.php?action=rentAdd&event=list' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>
</div>

<!-- Formularz wpisywania nowego serwisu -->
<div class='form-outline mx-5 d-flex justify-content-center'>
    <form class='form-horizontal' action="index.php?action=rentAdd" id="rentAddForm" method="post">

        <div class="row mb-2">
            <!-- Pole wpisywania daty serwisu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="datapoczatek">Data rozpoczęcia:</label>
                <div class='controls'>
                    <input type="date" class='col-md-12' id="datapoczatek" name="datapoczatek" <?php echo "min='{$preweek}' max='{$maxdate}' value='{$fields['datapoczatek']}'"; ?>>
                </div>
            </div>

            <!-- Pole wpisywania daty ważności serwisu -->
            <div class='col-md-6 mb-2'>
                <label class='control-label' for="datakoniec">Data końca:</label>
                <div class='controls'>
                    <input type="date" class='col-md-12' id="datakoniec" name="datakoniec" disabled>
                </div>
            </div>
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
        <div class='form-group mb-3'>
            <label class='control-label' for="przebiegstart">Obecny przebieg:</label>
            <div class='controls'>
                <input type="number" class="col-md-12" id="przebiegstart" name="przebiegstart" min="<?php echo (int) $fields['przebiegstart'] ?>" value="<?php echo (int) $fields['przebiegstart'] ?>">
            </div>
            <?php if (array_key_exists('przebiegstart', $errors)) : ?><span><?php echo $errors['przebiegstart'] ?></span><?php endif; ?>
        </div>

        <!-- Pole wpisywania telefonu i wyszukiwania klienta po nim-->
        <div class='row mb-2'>
            <div class='col-md-8 my-2'>
                <label class='control-label' for='telefon'>Telefon</label>
                <div class='controls'>
                    <input type='number' class="col-md-12" id='telefon' name='telefon' value='<?php echo $fields['telefon'] ?>' />
                </div>
            </div>
            <div class='col-md-4 mt-4'>
                <input type='submit' class='btn btn-info btn-sm col-md-12' value='Znajdź' name='findClient' />
            </div>
            <?php if (array_key_exists('telefon', $errors)) : ?><span><?php echo $errors['telefon'] ?></span><?php endif; ?>
        </div>

        <div class='form-group mb-2'>
            <label class='control-label' for='email'>E-mail</label>
            <div class='controls'>
                <input id='email' type='email' class="col-md-12" name='email' value='<?php echo $fields['email'] ?>' <?php echo $fields['disabled']?> />
                <?php if (array_key_exists('email', $errors)) : ?><span><?php echo $errors['email'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wpisywania nazwy serwisu -->
        <div class='row mb-2'>
            <div class='col-md-6 mb-2'>
                <label class='control-label' for='imie'>Imię</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='imie' name='imie' value='<?php echo $fields['imie'] ?>' <?php echo $fields['disabled']?> />
                    <?php if (array_key_exists('imie', $errors)) : ?><span><?php echo $errors['imie'] ?></span><?php endif; ?>
                </div>
            </div>

            <div class='col-md-6 mb-2'>
                <label class='control-label' for='nazwisko'>Nazwisko</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='nazwisko' name='nazwisko' value='<?php echo $fields['nazwisko'] ?>' <?php echo $fields['disabled']?> />
                    <?php if (array_key_exists('nazwisko', $errors)) : ?><span><?php echo $errors['nazwisko'] ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pole wpisywania numeru pesel -->
        <div class='form-group mb-2'>
            <label class='control-label' for='pesel'>Pesel</label>
            <div class='controls'>
                <input type='number' class="col-md-12" id='pesel' name='pesel' value='<?php echo $fields['pesel'] ?>' <?php echo $fields['disabled']?> />
            </div>
        </div>

        <!-- Pole wybrania i wpisania numeru dokumentu -->
        <div class='row mb-2'>
            <label class='control-label' for='rodzajdokumentu'>Dokument</label>
            <div class='col-md-4 mb-2'>

                <select class="col-md-12" name="rodzajdokumentu" <?php echo $fields['disabled']?> >
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
                    <input class="col-md-12" id='nrdokumentu' type='text' name='nrdokumentu' placeholder='Numer dokumentu' value='<?php echo $fields['nrdokumentu'] ?>' <?php echo $fields['disabled']?> />
                    <?php if (array_key_exists('nrdokumentu', $errors)) : ?><span><?php echo $errors['nrdokumentu'] ?></span><?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pole wybrania i wpisania numeru dokumentu -->
        <div class='row mb-2'>
            <div class='col-md-6 mb-2'>
                <label class='control-label' for='kraj'>Kraj</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='kraj' name='kraj' onchange="onChangeCity()" value='<?php echo $fields['kraj'] ?>' <?php echo $fields['disabled']?> />
                    
                </div>
            </div>

            <div class='col-md-6 mb-2'>
                <label class='control-label' for='miasto'>Miasto</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='miasto' name='miasto' value='<?php echo $fields['miasto'] ?>' <?php echo $fields['disabled']?> />
                    
                </div>
            </div>
            <?php if (array_key_exists('kraj', $errors)) : ?><span><?php echo $errors['kraj'] ?></span><?php endif; ?>
            <?php if (array_key_exists('miasto', $errors)) : ?><span><?php echo $errors['miasto'] ?></span><?php endif; ?>
        </div>

        <div class='form-group mb-2'>
            <label class='control-label' for='ulica'>Ulica</label>
            <div class='controls'>
                <input id='ulica' type='text' class="col-md-12" name='ulica' value='<?php echo $fields['ulica'] ?>' <?php echo $fields['disabled']?> />
                <?php if (array_key_exists('ulica', $errors)) : ?><span><?php echo $errors['ulica'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wybrania i wpisania numeru dokumentu -->
        <div class='row mb-2'>
            <div class='col-md-6 mb-2'>
                <label class='control-label' for='nrdomu'>Nr domu</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='nrdomu' name='nrdomu' value='<?php echo $fields['nrdomu'] ?>' <?php echo $fields['disabled']?> />
                </div>
            </div>

            <div class='col-md-6 mb-2'>
                <label class='control-label' for='nrmieszkania'>Nr mieszkania</label>
                <div class='controls'>
                    <input type='text' class="col-md-12" id='nrmieszkania' name='nrmieszkania' value='<?php echo $fields['nrmieszkania'] ?>' <?php echo $fields['disabled']?> />
                    
                </div>
            </div>
            <?php if (array_key_exists('nrdomu', $errors)) : ?><span><?php echo $errors['nrdomu'] ?></span><?php endif; ?>
            <?php if (array_key_exists('nrmieszkania', $errors)) : ?><span><?php echo $errors['nrmieszkania'] ?></span><?php endif; ?>
        </div>

        <div class='form-group mb-2'>
            <label class='control-label' for='kodpocztowy'>kodpocztowy</label>
            <div class='controls'>
                <input id='kodpocztowy' type='text' class="col-md-12" name='kodpocztowy' value='<?php echo $fields['kodpocztowy'] ?>' <?php echo $fields['disabled']?> />
                <?php if (array_key_exists('kodpocztowy', $errors)) : ?><span><?php echo $errors['kodpocztowy'] ?></span><?php endif; ?>
            </div>
        </div>

        <!-- Pole wpisywania uwag -->
        <div class='form-group mb-2'>
            <label class='control-label' for="uwagi">Uwagi do wypożyczenia:</label>
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

<script id="autocomplete-script">
    $(function() {
        $("#kraj").autocomplete({
            source: <?php echo getCountryData($db) ?>
        });
    });

    const krajeIMiasta = <?php echo getCityData($db) ?>;

    $(function() {
        $("#miasto").autocomplete({
            source: krajeIMiasta.map(row => row.miasto)
        });
    });
</script>
