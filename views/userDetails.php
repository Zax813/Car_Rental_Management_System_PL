<meta charset="utf-8" />

<h2 class='form-outline mx-5 my-2'>Szczegóły pracownika</h2>

<div class='form-outline mx-5 my-2'>
    <table class="table">
        <?php
        foreach ($$result as $row)
            echo "<tr>
                <td>ID
                <td>{$row['idpracownika']}
            <tr>
                <td>Login
                <td>{$row['login']}
            <tr>
                <td>Imię i Nazwisko
                <td>{$row['imie']} {$row['nazwisko']}
            <tr>
                <td>Uprawnienia
                <td>{$row['uprawnienia']}
            <tr>
                <td>Zatrudniony
                <td>";
        if ($row['zatrudniony'] == true) {
            echo "tak";
        } else {
            echo "nie";
        }
        echo "
            <tr>
                <td>Telefon
                <td>{$row['telefon']}
            <tr>
                <td>E-mail
                <td>{$row['email']}
        ";
        ?>
    </table>
</div>
<div class='form-outline mx-5 my-2'>
    <?php
    if ($_SESSION['perm'] == "admin" || $_SESSION['perm'] == "kierownik") {
        echo "<a class='btn btn-info btn-sm mr-1' href='index.php?action=userDetails&event=back' title='Lista' name='list'><i class='bi bi-arrow-left-circle'></i> Powrót</a>";
        echo "<a class='btn btn-warning btn-sm mx-2' href='index.php?action=userDetails&event=add' title='Edytuj' name='edit'><i class='bi bi-pencil-square'></i> Edytuj</a>";
    }
    ?>
</div>