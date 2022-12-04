<meta charset="utf-8" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="./views/functions.js"></script>

<h2>Samochody</h2>

<div class='form-outline mt-3 mx-2'>
    <?php
    if ($_SESSION['perm'] == "admin") {
        echo "<a href='index.php?action=addEquip'' class='btn btn-info' name='addEquip'>Dodaj samochód</a>";
    }
    ?>
</div>
<!--
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