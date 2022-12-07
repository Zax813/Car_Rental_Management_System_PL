<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Pracownicy</h2>
<div class='form-outline mx-5 my-3'>
<?php
if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") 
{
    echo "<a href='index.php?action=userAdd' class='btn btn-info' name='userAdd'>Dodaj Pracownika</a>";
?>
<form class="horizontal d-flex justify-content-end" action="index.php?action=userList" method="POST">
    <input type="search" name="search" value="<?php echo $fields['search'] ?>" maxlength="25">
    <button type="submit" class="btn btn-warning"  name="find" id="find"><i class="bi bi-search"></i></button>
</form>
</div>
<div class='form-outline mx-5 my-2'>
    <table class="table table-dark table-striped">
        <tr>
            <th>ID
            <th>Imię
            <th>Nazwisko
            <th>Login
            <th>Uprawnienia
            <th>Zatrudniony
            <th>Akcje
        <tr>
            <?php
            if (isset($_SESSION['user'])) 
            {
                foreach ($stmt as $row) 
                {
                    echo "<tr>
                    <td>{$row['idpracownika']}<td>{$row['imie']}<td>{$row['nazwisko']}<td>{$row['login']}<td>{$row['uprawnienia']}<td>";
                    if($row['zatrudniony']==true)
                        {echo "tak<td>";}
                    else
                        {echo "nie<td>";}

                    if($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik")
                    {
                        echo "<a class='btn btn-info btn-sm' href='index.php?action=userList&value={$row['idpracownika']}&event=details' title='Szczegóły' name='details'><i class='bi bi-person-vcard'></i></a>";
                        echo "<a class='btn btn-warning btn-sm' href='index.php?action=userList&value={$row['idpracownika']}&event=add' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i></a></td>";
                        //echo "</td>";
                    } 
                    echo "</tr>";
                }
            }

            ?>
    </table>
</div>

<?php } ?>