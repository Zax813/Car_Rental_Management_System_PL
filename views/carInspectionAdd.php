<h2 class='form-outline mx-5 my-3'>Dodaj przegląd</h2>

<div class='form-outline mx-5 my-4'>

    <!-- Formularz z polem do wpisywania numeru rejestracyjnego -->
    <form action="carInspectionAdd.php" method="post">
        <label for="numer">Numer rejestracyjny:</label>
        <input type="text" id="numer" name="numer">

        <div class='form-outline mx-5 d-flex justify-content-center'>
            <input type="submit" value="Dodaj samochód">
        </div>
    </form>
</div>

<script>
    $(function() {
        $("#numer").autocomplete({
            source: <?php echo getData($db)?> 
        });
    });
</script>