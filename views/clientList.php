<meta charset="utf-8" />

<head>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
</head>

<h2 class='form-outline mx-5 my-2'>Klienci</h2>
<div class='form-outline mx-5 my-3'>
<?php
if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    //echo "<a href='index.php?action=userAdd' class='btn btn-info' name='userAdd'>Dodaj Klienta</a>";
}
?>

</div>
<div class='form-outline mx-5 my-2'>
    <table id="userTable" class="table table-striped">
        <thead>    
            <tr>
                <th scope="col">ID</th>
                <?php if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik"){ ?>
                <th scope="col">Dokument</th>
                <th scope="col">Nr Dokumentu</th>
                <th scope="col">Pesel</th>
                <?php } ?>
                <th scope="col">Imię</th>
                <th scope="col">Nazwisko</th>
                <th scope="col">Telefon</th>
                <th scope="col">E-mail</th>
                <th scope="col">Kraj</th>
                <th scope="col">Miasto</th>
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
                        <td>{$row['idklienta']}";
                        if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                        {
                            echo "
                            <td>{$row['rodzajdokumentu']}
                            <td>{$row['nrdokumentu']}
                            <td>{$row['pesel']}";
                        }
                        echo "
                        <td>{$row['imie']}
                        <td>{$row['nazwisko']}
                        <td>{$row['telefon']}
                        <td>{$row['email']}
                        <td>{$row['kraj']}
                        <td>{$row['miasto']}
                        <td>";
                        
                        if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                        {
                            echo "<a class='btn btn-info btn-sm me-1' href='index.php?action=clientList&value={$row['idklienta']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                            echo "<a class='btn btn-secondary btn-sm me-1' href='index.php?action=clientList&value={$row['idklienta']}&event=history' title='Historia' name='history'><i class='bi bi-journal-text'></i></i></a>";
                            echo "<a class='btn btn-warning btn-sm me-1' href='index.php?action=clientList&value={$row['idklienta']}&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a></td>";
                            
                            //echo "</td>";
                        } 
                        else
                        {
                            echo "<a class='btn btn-secondary btn-sm me-1' href='index.php?action=clientList&value={$row['idklienta']}&event=history' title='Historia' name='history'><i class='bi bi-journal-text'></i></i></a></td>";
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