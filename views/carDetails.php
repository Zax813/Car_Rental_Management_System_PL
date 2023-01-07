<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły Samochodu</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
        echo "<tr>
                <td>ID
                <td>{$row['idauto']}
            <tr>
                <td>Marka i Model
                <td>{$row['marka']} {$row['model']}
            <tr>
                <td>Vin
                <td>{$row['vin']}
            <tr>
                <td>Rok Produkcji
                <td>{$row['rok']}
            <tr>
                <td>Rejestracja
                <td>{$row['rejestracja']}
            <tr>
                <td>Przebieg
                <td>{$row['przebieg']} km
            <tr>
                <td>Segment
                <td>{$row['segment']}
            <tr>
                <td>Paliwo
                <td>{$row['paliwo']}
            <tr>
                <td>Moc
                <td>{$row['mockw']} kW / {$row['mockm']} KM
            <tr>
                <td>Skrzynia biegów
                <td>{$row['skrzynia']}
            <tr>
                <td>Liczba miejsc
                <td>{$row['liczbamiejsc']}
            <tr>
                <td>Cena za dzień
                <td>{$row['cenadoba']} zł
            <tr>
                <td>Cena za km
                <td>{$row['cenakm']} zł
            <tr>
                <td>Aktywny";

                if($row['aktywny'] == false)
                {
                    echo "<td style='color: red'>Nie<td>";
                }
                else
                {
                    echo "<td style='color: green'>Tak<td>";
                }

            echo "<tr>
                <td>Sprawny";
            
                if($row['sprawny'] == false)
                {
                    echo "<td style='color: red'>Nie<td>";
                }
                else
                {
                    echo "<td style='color: green'>Tak<td>";
                }
            echo "<tr>
                <td>Dostępny";
                
                if($row['dostepny'] == false)
                {
                    echo "<td style='color: red'>Nie<td>";
                }
                else
                {
                    echo "<td style='color: green'>Tak<td>";
                }

            echo "<tr>";

        ?>
    </table>


    <!-- Pole  uwag -->
    <div class='form-group mb-2'>
        <label class='control-label' for="uwagi">Uwagi do wypożyczenia:</label>
        <div class='controls'>
            <textarea class="col-md-12" id="uwagi" name="uwagi" rows="5" cols="40" disabled><?php echo $row['uwagi']; ?></textarea>
        </div>
    </div>
</div>

<div class='form-outline mx-5 my-2'>
    <?php
    if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=carDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
        echo "<a class='btn btn-warning btn-sm mx-2' href='index.php?action=carDetails&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i> Edytuj</a>";
    }
    ?>
</div>