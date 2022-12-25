<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły Serwisu</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
        foreach ($result as $row)
        {
            echo "<tr>
                <td>ID
                <td>{$row['idserwis']}
            <tr>
                <td>Pracownik
                <td>{$row['imie']} {$row['nazwisko']}
            <tr>
                <td>Samochód
                <td>{$row['marka']} {$row['model']} ({$row['rejestracja']})
            <tr>
                <td>Serwis
                <td>{$row['nazwaserwisu']}
            <tr>
                <td>Data Rozpoczęcia
                <td>{$row['datapoczatek']}
            <tr>
                <td>Data Zakończenia
                <td>{$row['datakoniec']}
           
            <tr>
                <td>Opis
                <td>{$row['opis']}
            <tr>
                <td>Uwagi
                <td>{$row['uwagi']}
            <tr>
                <td>Koszt
                <td>{$row['koszt']}
        ";
        ?>
    </table>
</div>
<div class='form-outline mx-5 my-2'>
    <?php
    if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=carServiceDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
        echo "<a class='btn btn-warning btn-sm mx-2' href='index.php?action=carServiceDetails&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i> Edytuj</a>";
    }
}
    ?>
</div>