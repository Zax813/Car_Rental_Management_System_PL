<?php
session_start();

//--Wartości do połączenia z bazą--//
$host = "localhost";    //Host bazy danych (np. ip, domena lub jeśli baza jest tylko na komputerze 'localhost')
$dbName = "CarDB";      //Nazwa bazy danych (domyślnie 'CarDB')
$dbPort = "5432";       //Port bazy danych (domyślnie '5432')
$dbUser = "postgres";   //Użytkownik bazy danych (domyślnie 'postgres')
$dbPass = "admin";      //Hasło użytkownika bazy danych, zdefiniowane przy tworzeniu bazy danych lub dodawaniu nowego użytkownika



//Funckje pomocne przy przekierowywaniu
function url($link)
{
    return "index.php?action=$link";
}

function redirect($link)
{
    header("Location: $link");
}

//Dodanie ścieżki do pliku php z funkcjami
define('_ROOT_PATH', dirname(__FILE__));
require_once(_ROOT_PATH.DIRECTORY_SEPARATOR.'functions.php');

//Spis dostępnych podstron
$actions = array('home',
                'carAdd',
                'carDetails',
                'carEdit',
                'carInspectionAdd',
                'carInspectionList',
                'carServiceDetails',
                'carServiceEdit',
                'carServiceAdd',
                'carServiceList',
                'clientAdd',
                'clientDetails',
                'clientEdit',
                'clientList',
                'imageAdd',
                'login', 
                'logout',
                'pageNotFound',
                'rentAdd',
                'rentCancel',
                'rentDetails',
                'rentFinal',
                'rentList',
                'userAdd',
                'userDetails',
                'userEdit',
                'userList'
                );

//Załadowanie widoku górnego paska
include(_ROOT_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'top.php');

//Próba nawiązania połączenia z bazą za pomocą biblioteki PDO
try 
{
    $db = new PDO('pgsql:host='.$host.'; dbname='.$dbName.'; port='.$dbPort, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
}
catch (PDOException $e) 
{
    echo '<h2>Błąd połączenia z bazą danych: </h2> <h3>'.$e->getMessage()."</h3>";
}


//Sprawdzenie czy podstrona istnieje
if (array_key_exists('action', $_GET)) 
{
    if (in_array($_GET['action'] , $actions))
    {
        $action = $_GET['action'];
    } 
    else 
    {
        $action = 'pageNotFound';
    }
}
else
{
    $action = 'home';
}

//Sprawdzenie czy ustawiona jest zmienna user, jeśli nie to przekieruje na stronę logowania
if(isset($db))
{
    if(!isset($_SESSION['user']))
    {
        $action = 'login';
    }

}
else
{
    $action = 'pageNotFound';
}

//Kolejność wykonywania działań przy odświeżeniu strony
//Wykonanie logiki podstrony
include(_ROOT_PATH.DIRECTORY_SEPARATOR. 'actions'.DIRECTORY_SEPARATOR.$action.'.php');

//Załadowanie widoku górnego paska
//include(_ROOT_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'top.php');

//Załadowanie widoku podstrony
include(_ROOT_PATH.DIRECTORY_SEPARATOR. 'views'.DIRECTORY_SEPARATOR.$action.'.php');

//Załadowanie widoku stopki
include(_ROOT_PATH.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'bottom.php');

?>