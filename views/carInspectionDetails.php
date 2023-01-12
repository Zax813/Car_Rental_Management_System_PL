<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły Przeglądu</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
        foreach ($result as $row)
            echo "<tr>
                <td>ID
                <td>{$row['idprzegladu']}
            <tr>
                <td>Samochód
                <td>{$row['marka']} {$row['model']}  ({$row['rejestracja']})
            <tr>
                <td>Data Przeglądu
                <td>{$row['dataprzegladu']}
            <tr>
                <td>Data Ważności
                <td>{$row['datawaznosci']}
            <tr>
            <td>Ważny";
        if ($row['datawaznosci'] < date('Y-m-d')) 
        {
            echo "<td style='color: red'>Nie";
        } 
        else 
        {
            $roznica = (strtotime($row['datawaznosci']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);

            if ($roznica >= 0 && $roznica <= 14) 
            {
                echo "<td style='color: Yellow'>Skończy się za $roznica dni";
            }
            else 
            {
                echo "<td style='color: green'>Tak";
            }
        }

        echo "</tr>
            <tr>
                <td>Uwagi
                <td><textarea class='col-md-12' id='uwagi' name='uwagi' rows='5' cols='40' disabled>{$row['uwagi']}</textarea>
        ";
        ?>
    </table>
</div>
<div class='form-outline mx-5 my-2'>
    <?php
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=carInspectionDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
    ?>
</div>