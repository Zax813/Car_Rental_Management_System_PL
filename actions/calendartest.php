<?php

$idauto = 1;

$stmt = $db->prepare("SELECT 'wypozyczenie' AS TYPE, w.datapoczatek, w.datakoniec, k.nazwisko AS nazwisko, a.rejestracja AS rejestracja
    FROM wypozyczenia w
    INNER JOIN klienci k ON w.idklienta = k.idklienta
    INNER JOIN auta a ON w.idauta = a.idauto
    WHERE a.idauto = :idauto
    UNION ALL

    SELECT 'serwis' AS TYPE, s.datapoczatek, s.datakoniec, NULL AS nazwisko, a.rejestracja AS rejestracja
    FROM serwis s
    INNER JOIN auta a ON s.idauta = a.idauto
    WHERE a.idauto = :idauto
    UNION ALL

    SELECT 'przeglad' AS TYPE, p.dataprzegladu AS datapoczatek, p.dataprzegladu AS datakoniec, NULL AS nazwisko, a.rejestracja AS rejestracja
    FROM przeglad p
    INNER JOIN auta a ON p.idauta = a.idauto
    WHERE a.idauto = :idauto");

$stmt->bindValue(':idauto', $idauto, PDO::PARAM_INT);
$stmt->execute();

$events = $stmt->fetchAll();

var_dump($events);

$calendarEvents = [];
foreach ($events as $event) {
  $title = $event['rejestracja'];
  if ($event['type'] == 'wypozyczenie') {
    $title .= ' (' . $event['nazwisko'] . ')';
  }
  if ($event['type'] == 'serwis') {
    $title = ' ( Serwis )';
  }
  if ($event['type'] == 'przeglad') {
    $title = ' ( Przegląd )';
  }

  $calendarEvents[] = [
    'start' => $event['datapoczatek'],
    'end' => $event['datakoniec'],
    'title' => $title,
  ];
}

?>