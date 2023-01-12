<meta charset="utf-8" />

<head>
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
</head>

<h2 class='form-outline mx-5 my-2'>Historia Samochodu - <?php echo $auto['marka'].' '.$auto['model'].' ('.$auto['rejestracja'].')';?></h2>

<div class='form-outline mx-5 my-3'>
<?php
if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    //echo "<a href='index.php?action=userAdd' class='btn btn-info' name='userAdd'>Dodaj Klienta</a>";
}
?>
</div>

<div class='form-outline mx-5 my-2'>
    <div class="row mb-2">
        <fieldset>
            <div id="calendar" style="margin: 10px;"></div>
        </fieldset>
    </div>
</div>

<fieldset class='form-outline mx-5 my-2'></fieldset>

<h2 class='form-outline mx-5 my-3'>Wypożyczenia</h3>


<div class='form-outline mx-5 my-2'>
    <table id="carRentTable" class="table table-dark table-striped">
        <thead>    
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Data Rozpoczęcia</th>
                <th scope="col">Data Zakończenia</th>
                <th scope="col">Przebieg Start</th>
                <th scope="col">Przebieg Koniec</th>
                <th scope="col">Imię</th>
                <th scope="col">Nazwisko</th>
                <th scope="col">Telefon</th>
                <th scope="col">Suma</th>
                <th scope="col">Status</th>
                <th scope="col">Akcje</th>
            </tr>
        </thead>
        <tbody>
                <?php
                if (isset($_SESSION['user'])) 
                {
                    foreach ($rentResult as $row) 
                    {
                        echo "<tr>
                        <td>{$row['idwypozyczenia']}
                        <td>{$row['datapoczatek']}
                        <td>{$row['datakoniec']}
                        <td>{$row['przebiegstart']}
                        <td>{$row['przebiegkoniec']}
                        <td>{$row['imie']}
                        <td>{$row['nazwisko']}
                        <td>{$row['telefon']}
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

<fieldset class='form-outline mx-5 my-5'></fieldset>

<h2 class='form-outline mx-5 my-2'>Serwisy</h3>

<div class='form-outline mx-5 my-2'>
    <table id="carServiceTable" class="table table-dark table-striped">
        <thead>    
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Data Rozpoczęcia</th>
                <th scope="col">Data Zakończenia</th>
                <th scope="col">Pracownik</th>
                <th scope="col">Serwis</th>
                <th scope="col">Opis</th>
                <th scope="col">Koszt</th>
                <th scope="col">Akcje</th>
            </tr>
        </thead>
        <tbody>
                <?php
                if (isset($_SESSION['user'])) 
                {
                    foreach ($serviceResult as $row) 
                    {
                        echo "<tr>
                        <td>{$row['idserwis']}
                        <td>{$row['datapoczatek']}
                        <td>{$row['datakoniec']}
                        <td>{$row['nazwisko']} {$row['imie']}
                        <td>{$row['nazwaserwisu']}
                        <td>{$row['opis']}
                        <td>{$row['koszt']}
                        <td>";
                        
                        if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                        {
                            echo "<a class='btn btn-info btn-sm' href='index.php?action=carServiceList&value={$row['idserwis']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                            echo "<a class='btn btn-warning btn-sm' href='index.php?action=carServiceList&value={$row['idserwis']}&event=edit' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a></td>";
                            //echo "</td>";
                        } 
                        echo "</tr>";
                    }
                }

                ?>
        </tbody>
    </table>
</div>


<fieldset class='form-outline mx-5 my-5'></fieldset>

<h2 class='form-outline mx-5 my-2'>Przeglądy</h3>

<div class='form-outline mx-5 my-2'>
<table id="carInspectionTable" class="table table-dark table-striped">
        <thead>    
            <tr>
                <th scope="col">ID</th>
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
                    foreach ($inspectResult as $row) 
                    {
                        echo "<tr>
                        <td>{$row['idprzegladu']}
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
                            echo "<a class='btn btn-info btn-sm' href='index.php?action=carInspectionList&value={$row['idprzegladu']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                        }
                        echo "</tr>";
                    }
                }

                ?>
        </tbody>
    </table>
</div>

<script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pl',
            initialView: 'dayGridMonth',
            firstDay: 1,
            events: <?= json_encode($calendarEvents) ?>,
            height: 600,
        });
        calendar.render();
      });

</script>

<script>
$(document).ready(function () {
    $('#carRentTable').DataTable({
        //processing: true,
        //serverSide: true,
        //ajax: '../server_side/scripts/server_processing.php',
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10,25,50],
            [10,25,50]
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

    $('#carServiceTable').DataTable({
        //processing: true,
        //serverSide: true,
        //ajax: '../server_side/scripts/server_processing.php',
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10,25,50],
            [10,25,50]
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

    $('#carInspectionTable').DataTable({
        //processing: true,
        //serverSide: true,
        //ajax: '../server_side/scripts/server_processing.php',
        "pagingType": "full_numbers",
        "lengthMenu": [
            [10,25,50],
            [10,25,50]
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