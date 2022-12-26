<h2 class='form-outline mx-5 my-3'>Serwisy Samochodów</h2>
<div class='form-outline mx-5 my-4'>
    
<?php
if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    echo "<a href='index.php?action=carServiceAdd' class='btn btn-info' name='carServicenAdd'>Dodaj Serwis</a>";
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
                <th scope="col">Serwis</th>
                <th scope="col">Data Rozpoczęcia</th>
                <th scope="col">Data Końca</th>
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
                        <td>{$row['idserwis']}
                        <td>{$row['marka']}
                        <td>{$row['model']}
                        <td>{$row['rejestracja']}
                        <td>{$row['nazwaserwisu']}
                        <td>{$row['datapoczatek']}
                        <td>{$row['datakoniec']}";

                        if($row['datakoniec'] > date('Y-m-d') && $row['datapoczatek'] > date('Y-m-d'))
                        {
                            echo "<td style='color: orange'>Zaplanowany<td>";
                        }
                        else if($row['datakoniec'] > date('Y-m-d'))
                        {
                            echo "<td style='color: red'>W trakcie<td>";
                        }
                        else
                        {
                            echo "<td style='color: green'>Zakończony<td>";
                        }
                        
                        if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                        {
                            echo "<a class='btn btn-info btn-sm' href='index.php?action=carServiceList&value={$row['idserwis']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                            echo "<a class='btn btn-warning btn-sm' href='index.php?action=carServiceList&value={$row['idserwis']}&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a></td>";
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
        "order": [[ 0, "desc" ]],

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