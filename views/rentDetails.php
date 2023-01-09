<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły Wypożyczenia</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
            echo "<tr>
                <td>ID
                <td>{$row['idwypozyczenia']}
            <tr>
                <td>Samochód
                <td><a href='index.php?action=rentDetails&event=car'>{$row['marka']} {$row['model']} ({$row['rejestracja']})</a>
            <tr>
                <td>Klient
                <td><a href='index.php?action=rentDetails&event=client'>{$row['imie']} {$row['nazwisko']}</a>
            <tr>
                <td>Kontakt
                <td><i class='bi bi-telephone'></i> {$row['telefon']} || <i class='bi bi-envelope-at'></i> {$row['email']}
            <tr>
                <td>Pracownik
                <td><a href='index.php?action=rentDetails&event=user'>{$row['pracimie']} {$row['pracnazwisko']} ({$row['login']})
            <tr>
                <td>Czas wypożyczenia
                <td>{$row['datapoczatek']}";
                if($row['datakoniec'] == null || !$row['datakoniec']){
                    echo " - trwa ($dni dni)";
                }else{
                    echo " - {$row['datakoniec']} ($dni dni)"; }

            echo "<tr>
                <td>Przebieg
                <td> {$row['przebiegstart']}";
                if($row['przebiegkoniec'] == null || !$row['przebiegkoniec']){
                    echo " - trwa";
                }else{
                    echo " - {$row['przebiegkoniec']} ($przejechano km)"; }
            
            echo "<tr>
                <td>Koszt
                <td>{$row['suma']}
            <tr>
                <td>Opłacone";
                if($row['zaplacono'] == false)
                {
                    echo "<td style='color: red'>Nie<td>";
                }
                else
                {
                    echo "<td style='color: green'>Tak<td>";
                }
            
            echo "<tr>
                <td>Uwagi
                <td><textarea class='col-md-12' id='uwagi' name='uwagi' rows='5' cols='40' disabled>{$row['uwagi']}</textarea>

        "
        ?>
    </table>
</div>
<div class='form-outline mx-5 my-2'>
    <?php
    if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=rentDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
    }
    ?>
</div>