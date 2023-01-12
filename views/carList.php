<h2 class='form-outline mx-5 my-3'>Lista Wszystkich Samochodów</h2>
<div class='form-outline mx-5 my-4'>
    
<?php
if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    echo "<a href='index.php?action=carList' class='btn btn-info' name='carAdd'>Dodaj Samochód</a>";

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
                <th scope="col">Segment</th>
                <th scope="col">Cena Doba</th>
                <th scope="col">Cena KM</th>
                <th scope="col">Dostępny</th>
                <th scope="col">Sprawny</th>
                <th scope="col">Aktywny</th>
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
                        <td>{$row['idauto']}
                        <td>{$row['marka']}
                        <td>{$row['model']}
                        <td>{$row['rejestracja']}
                        <td>{$row['segment']}
                        <td>{$row['cenadoba']}
                        <td>{$row['cenakm']}";

                        if($row['dostepny'] == 0)
                        {
                            echo "<td style='color: red'>Nie</td>";
                        }
                        else
                        {
                            echo "<td style='color: green'>Tak</td>";
                        }

                        if($row['sprawny'] == 0)
                        {
                            echo "<td style='color: red'>Nie</td>";
                        }
                        else
                        {
                            echo "<td style='color: green'>Tak</td>";
                        }

                        if($row['aktywny'] == 0)
                        {
                            echo "<td style='color: red'>Nie<td>";
                        }
                        else
                        {
                            echo "<td style='color: green'>Tak<td>";
                        }
                        
                            echo "<a class='btn btn-info btn-sm' href='index.php?action=carList&value={$row['idauto']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                            echo "<a class='btn btn-secondary btn-sm mx-1 my-1' href='index.php?action=home&value={$row['idauto']}&event=history' title='Historia' name='history'><i class='bi bi-journal-text'></i></a>";
                            echo "<a class='btn btn-warning btn-sm' href='index.php?action=carList&value={$row['idauto']}&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a></td>";
                        echo "</tr>";
                    }
                }

                ?>
        </tbody>
    </table>
</div>

<?php
}
else
{
    echo "<h3 class='form-outline mx-5 my-3'>Brak dostępu do tej strony</h3>";
}
?>

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