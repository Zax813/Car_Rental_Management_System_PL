<meta charset="utf-8" />

<head>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
</head>

<h2 class='form-outline mx-5 my-3'>Przeglądy Samochodów</h2>
<div class='form-outline mx-5 my-4'>
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
                <th scope="col">Marka</th>
                <th scope="col">Model</th>
                <th scope="col">Rejestracja</th>
                <th scope="col">Data Przeglądu</th>
                <th scope="col">Data Końca</th>
                <th scope="col">Uwagi</th>
                <th scope="col">Ważny</th>
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
                        <td>{$row['idprzegladu']}
                        <td>{$row['marka']}
                        <td>{$row['model']}
                        <td>{$row['rejestracja']}
                        <td>{$row['dataprzegladu']}
                        <td>{$row['datawaznosci']}
                        <td>{$row['uwagi']}";
                        if($row['datawaznosci'] < date('Y-m-d'))
                        {
                            echo "<td style='color: red'>Nie<td>";
                        }
                        else
                        {
                            $roznica = (strtotime($row['datawaznosci']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24);

                            if ($roznica >= 0 && $roznica <= 14) {
                                echo "<td style='color: Yellow'>Skończy się za $roznica dni<td>";
                            }else{
                                echo "<td style='color: green'>Tak<td>";
                            }
                        }
                        
                        if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                        {
                            echo "<a class='btn btn-info btn-sm' href='index.php?action=clientList&value={$row['idprzegladu']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                            echo "<a class='btn btn-warning btn-sm' href='index.php?action=clientList&value={$row['idprzegladu']}&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a></td>";
                            //echo "</td>";
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