<meta charset="utf-8" />

<head>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
</head>

<h2 class='form-outline mx-5 my-2'>Historia Klienta - <?php echo $klient['imie'].' '.$klient['nazwisko']?></h2>

<div class='form-outline mx-5 my-3'>
<?php
if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    //echo "<a href='index.php?action=userAdd' class='btn btn-info' name='userAdd'>Dodaj Klienta</a>";
}
?>

</div>
<div class='form-outline mx-5 my-2'>
    <table id="userTable" class="table table-dark table-striped">
        <thead>    
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Data Rozpoczęcia</th>
                <th scope="col">Data Zakończenia</th>
                <th scope="col">Marka</th>
                <th scope="col">Model</th>
                <th scope="col">Rejestracja</th>
                <th scope="col">Przebieg Start</th>
                <th scope="col">Przebieg Koniec</th>
                <th scope="col">Suma</th>
                <th scope="col">Status</th>
                <th scope="col">Akcje</th>
            </tr>
        </thead>
        <tbody>
                <?php
                if (isset($_SESSION['user'])) 
                {
                    foreach ($result as $row) 
                    {
                        echo "<tr>
                        <td>{$row['idwypozyczenia']}
                        <td>{$row['datapoczatek']}
                        <td>{$row['datakoniec']}
                        <td>{$row['marka']}
                        <td>{$row['model']}
                        <td>{$row['rejestracja']}
                        <td>{$row['przebiegstart']}
                        <td>{$row['przebiegkoniec']}
                        <td>{$row['suma']}";
                        if($row['realizacja'] == TRUE)
                        {
                            if($row['datapoczatek'] > date('Y-m-d'))
                            {
                                echo "<td style='color: orange'>Zaplanowany<td>";
                            }
                            else if($row['datakoniec'] == null || !$row['datakoniec'] || $row['datakoniec'] > date('Y-m-d'))
                            {
                                echo "<td style='color: red'>Trwa<td>";
                            }
                            else
                            {
                                echo "<td style='color: green'>Zakończony<td>";
                            }
                        }else
                        {
                            echo "<td style='color: blue'>Anulowano<td>";
                        }
                        
                        if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                        {
                            echo "<a class='btn btn-info btn-sm me-1' href='index.php?action=rentList&value={$row['idwypozyczenia']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                            //echo "</td>";
                        } 

                        if($row['zaplacono']==false)
                        {
                            if($row['datapoczatek'] >= date('Y-m-d'))
                            {
                                echo "<a class='btn btn-danger btn-sm me-1' href='index.php?action=rentList&value={$row['idwypozyczenia']}&event=cancel' title='Anuluj' name='cancel'><i class='bi bi-calendar-x'></i></a>"; 
                            }
                            echo "<a class='btn btn-success btn-sm me-1' href='index.php?action=rentList&value={$row['idwypozyczenia']}&event=final' title='Zakończ' name='final'><i class='bi bi-calendar-check'></i></a></td>";
                        }
                        echo "</tr>";
                    }
                }

                ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function () {
    $('#userTable').DataTable({
        //processing: true,
        //serverSide: true,
        //ajax: '../server_side/scripts/server_processing.php',
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10,25,50,100],
            [10,25,50,100]
        ],
        responsive: true,
        language: {
            "infoEmpty": "Brak danych",
            "info": "Strona _PAGE_ z _PAGES_",
            "infoFiltered":   "(filtrowanie z _MAX_ wpisów)",
            "lengthMenu": "Wyświetl _MENU_ wierszy na stronę",
            "loadingRecords": "Ładowanie...",
            "search": "_INPUT_",
            "searchPlaceholder": "Szukaj",      
            "zeroRecords": "Nic nie znaleziono",
            "paginate": {
                "first":      "Pierwsza",
                "last":       "Ostatnia",
                "next":       "Następna",
                "previous":   "Ostatnia"
            },

            "aria": {
                "sortAscending":  ": Sortuj kolumnę rosnąco",
                "sortDescending": ": Sortuj kolumnę malejąco"
            }
        }
    });
});
</script>