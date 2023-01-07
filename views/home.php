<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Samochody</h2>

<div class='form-outline mx-5 my-3'>
    <?php
    if ($_SESSION['perm'] == "admin") {
        echo "<a href='index.php?action=home&event=carAdd' class='btn btn-info' name='carAdd'>Dodaj samochód</a>";
    }
    ?>
</div>
<form action="index.php?action=home" id="rentAddForm" method="post">
<div class="row mx-5 mb-2">

    <div class="col-md-2 mb-2">
        <select class="form-select col-md-12" id="sortuj" name="sortuj">
            <option value="" <?php if ($fields['sortuj'] == '') {
                                    echo " selected";
                                } ?>>--Sortuj--</option>
            <option value="MARKAUP" <?php if ($fields['sortuj'] == 'MARKAUP') {
                                            echo " selected";
                                        } ?>>Marka rosnąco</option>
            <option value="MARKADOWN" <?php if ($fields['sortuj'] == 'MARKADOWN') {
                                            echo " selected";
                                        } ?>>Marka malejąco</option>
            <option value="MOCUP" <?php if ($fields['sortuj'] == 'MOCUP') {
                                        echo " selected";
                                    } ?>>Moc rosnąco</option>
            <option value="MOCDOWN" <?php if ($fields['sortuj'] == 'MOCDOWN') {
                                            echo " selected";
                                        } ?>>Moc malejąco</option>
            <option value="CENADOBAUP" <?php if ($fields['sortuj'] == 'CENADOBAUP') {
                                            echo " selected";
                                        } ?>>Cena za dobę rosnąco</option>
            <option value="CENADOBADOWN" <?php if ($fields['sortuj'] == 'CENADOBADOWN') {
                                                echo " selected";
                                        } ?>>Cena za dobę malejąco</option>
            <option value="CENAKMUP" <?php if ($fields['sortuj'] == 'CENAKMUP') {
                                            echo " selected";
                                        } ?>>Cena za km rosnąco</option>
            <option value="CENAKMDOWN" <?php if ($fields['sortuj'] == 'CENAKMDOWN') {
                                            echo " selected";
                                        } ?>>Cena za km malejąco</option>
        </select>
    </div>

    <div class="col-md-2 mb-2">
        <select class="form-select col-md-12" id="segment" name="segment">
            <option value="">--Segment--</option>
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

    <div class="col-md-2 mb-2">
        <select class="form-select col-md-12" id="skrzynia" name="skrzynia">
            <option value="">--Skrzynia--</option>
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

                                    
    <div class='col-md-2 mb-2'>
        <select class="form-select col-md-12" id="paliwo" name="paliwo">
            <option value="">--Paliwo--</option>
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

    <!-- Pole wpisywania liczby miejsc -->
    <div class='col-md-3 mb-2'>
        <div class='row'>
            <div class='col-md-6 mb-2'>
                <input type="number" class="col-md-12" id="miejscaMin" name="miejscaMin" placeholder="Min Miejsca" min='1' max='9' value="<?php echo $fields['miejscaMin'] ?>">
            </div>
            <div class='col-md-6 mb-2'>
                <input type="number" class="col-md-12" id="miejscaMax" name="miejscaMax" placeholder="Max Miejsca" min='1' max='9' value="<?php echo $fields['miejscaMax'] ?>" >
            </div>
        </div>
    </div>

    
    <div class='col-md-1 mb-2 ms-auto'>
        <input type='submit' class='btn btn-info col-md-12' value='Szukaj' name='find' />
    </div>

</div>
</form>

<!-- Wyswietlenie samochodów -->
<?php foreach ($result as $row) { ?>
    <div class="card mb-3 mx-5">
        <div class="row no-gutters">
            <div class="col-md-4">
                <?php
                if ($row['zdjecie'] != null)
                    echo "<img src='{$row['zdjecie']}' class='h-100 card-img' alt='{$row['tytul']}'>";
                else
                    echo "<img src='images/sedan-car-front.png' class='h-100 card-img mx-1' alt='default_car'>";
                ?>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo "{$row['marka']} {$row['model']} ({$row['rejestracja']})" ?></h5>
                    </div>
                    <div class="col-md-6">
                        <?php
                        echo "<ul class='list-group list-group-flush'>
                        <li class='mb-1'>{$row['segment']} ({$row['rok']})</li>
                        <li class='mb-1'>Skrzynia {$row['skrzynia']}</li>
                        <li class='mb-1'>Liczba miejsc: {$row['liczbamiejsc']}</li>
                        <li class='mb-1'>Moc: {$row['mockw']} kW / ";
                        echo (round($row['mockw'] * 1.36));
                        echo " KM</li>
                        </ul>";

                        ?>
                    </div>

                    <div class="col-md-6">
                        <?php
                        echo "<ul class='list-group list-group-flush'> 
                            <li class='mb-1'>{$row['paliwo']}</li>
                            <li class='mb-1'> </li>
                            <li class='mb-1' style='color: white; background-color: crimson;'>&nbsp Cena 24h: {$row['cenadoba']} zł</li>
                            <li class='mb-1' style='color: white; background-color: crimson;'>&nbsp Cena KM:  {$row['cenakm']} zł</li>
                            </ul>";

                        if ($row['dostepny'] == true)
                            echo "<small class='ok'>Dostępny</small>
                            <a class='btn btn-success btn-sm mx-1 my-1' href='index.php?action=home&value={$row['idauto']}&event=add' title='Wypożycz' name='rentAdd'><i class='bi bi-calendar-plus'></i></a>";
                        else
                            echo "<p class='card-text mt-1'><small style='color: red;'>Nie dostępny</small></p>";

                        if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                            echo "<a class='btn btn-info btn-sm mx-1 my-1' href='index.php?action=home&value={$row['idauto']}&event=details' title='Szczegóły' name='details'><i class='bi bi-info-circle'></i></a>
                                    <a class='btn btn-warning btn-sm mx-1 my-1' href='index.php?action=home&value={$row['idauto']}&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a>";
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
}
?>

<!--
---------------------------------------------------------------
<p></p>

<form method="post" action="index.php?action=home" id="filter">
    <select name="limit" id="limit" onchange="onChangeList()">
        <option value="10" <?php if ($fields['limit'] == '10') {
                                echo " selected";
                            } ?>>Limit: 10</option>
        <option value="30" <?php if ($fields['limit'] == '30') {
                                echo " selected";
                            } ?>>Limit: 30</option>
        <option value="50" <?php if ($fields['limit'] == '50') {
                                echo " selected";
                            } ?>>Limit: 50</option>
        <option value="100" <?php if ($fields['limit'] == '100') {
                                echo " selected";
                            } ?>>Limit: 100</option>
    </select>

    <label>Filtr:</label>
    <select name="typ" id="typ" onchange="onChangeList()">
        <option value="">--Typ--</option>
        <?php
        foreach ($dbtyp as $row) {
            echo "<option value={$row['idtyp']}";
            if ($fields['typ'] == $row['idtyp']) {
                echo " selected";
            }
            echo ">{$row['nazwatyp']}</option>";
        }
        ?>
    </select>

    <select name='rodzaj' id='rodzaj'>
        <option value="" <?php if ($fields['rodzaj'] == "") {
                                echo " selected";
                            } ?>>--Rodzaj-- </option>
        <?php
        if (isset($dbrodzaj)) {
            foreach ($dbrodzaj as $row) {
                echo "<option value={$row['idrodzaj']}";
                if ($fields['rodzaj'] == $row['idrodzaj']) {
                    echo " selected";
                }
                echo ">{$row['nazwarodzaj']}</option>";
            }
        }
        ?>
    </select>

    <select name="fmarka" id="fmarka">
        <option value="">--Marka--</option>
        <?php
        foreach ($dbmarka as $row) {
            echo "<option value={$row['idmarka']}";
            if ($fields['fmarka'] == $row['idmarka']) {
                echo " selected";
            }
            echo ">{$row['nazwa']}</option>";
        }
        ?>
    </select>

    <label>  Sortuj po: </label>
    <select name="sortuj" id="sortuj">
        <option value="KOD" <?php if ($fields['sortuj'] == 'KOD') {
                                echo " selected";
                            } ?>>Kod produktu</option>
        <option value="MODEL" <?php if ($fields['sortuj'] == 'MODEL') {
                                    echo " selected";
                                } ?>>Model</option>
        <option value="CENA1" <?php if ($fields['sortuj'] == 'CENA1') {
                                    echo " selected";
                                } ?>>Cena Rosnąco</option>
        <option value="CENA2" <?php if ($fields['sortuj'] == 'CENA2') {
                                    echo " selected";
                                } ?>>Cena Malejąco</option>
    </select>

    <input type=submit name='filtruj' value='Filtruj'/>

</form>
<p></p>
<?php
if (array_key_exists('read', $errors)) {
    echo "<span>";
    echo $errors['read'];
    echo "</span>";
} else {
?>
<table>
    <tr>
        <th>Kod
        <th>Typ
        <th>Rodzaj
        <th>Marka
        <th>Model
        <th>Opis
        <th>Dostępne
        <th>Cena
        <th>Produkowany
        <th>Akcje
    <tr>
        <?php
        foreach ($stmt as $row) {
            echo "<form method='post' action='index.php?action=home&kod={$row['kod']}' id='item'>";
            echo "<tr>
            <td>{$row['kod']}
            <td>{$row['typ']}
            <td>{$row['rodzaj']}
            <td>{$row['marka']}
            <td>{$row['model']}
            <td>{$row['opis']}
            <td>{$row['ilosc']}
            <td>{$row['cena']}
            <td>";
            if ($row['produkowany'] == true) {
                echo "tak";
            } else {
                echo "nie";
            }

            echo "<td><dl>";
            if ($row['ilosc'] > 0) {
                echo "<input type='hidden' name='marka' value={$row['marka']} />";
                echo "<input type='hidden' name='model' value={$row['model']} />";
                echo "<input type='hidden' name='cena' value={$row['cena']} />";
                echo "<dt><input type='number' name='liczba' value='1' min='1' max={$row['ilosc']} /></dt>";
                echo "<input type=submit name='add' value='add'/>";
            }
            if ($_SESSION['perm'] == "admin") {
                echo "<input type=submit name='edit' value='edit'/>";
            }
            echo "</dl></tr></form>";
        }

        ?>
</table>

<?php } ?>
    -->