<!DOCTYPE HTML>
<html>

<head>
    <title>Lista uzytkownikow</title>
    <link href="/images/dot_ico/users.ico" rel="icon" type="image/x-icon" />
    <style>
        body {
            background-color: rgba(0, 0, 0, 0.90) !important;
            color: white !important;
        }

        table tr td {
            color: white !important;
        }

        .center {
            color: white !important;
            text-align: center;
        }

        .center_form {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .absolute {
            width: 80%;
            max-width: 800px;
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .tableUnderline {
            border-bottom: solid 4px !important;
        }

        .table>tbody>tr>td {
            vertical-align: middle;
        }
    </style>

    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-xxl navbar-dark">
        <div class="adminNav">
            <a class="navbar-brand" href="users_list.php"><img id="logoImg" src="images/logo.jpg" alt="Logo"></a>
            <a class="navbar-brand logomen" href="users_list.php">Restau<span class="fast-flicker">racja</span> u <span class="flicker">Mentzena</span></a>
        </div>
    </nav>
    <div class='absolute'>
        <?php
        session_start();
        if (isset($_SESSION['general_message'])) {
            echo $_SESSION['general_message'];
            unset($_SESSION['general_message']);
        }
        ?>
    </div>
</body>

</html>

<?php

require_once("paths.php");
require_once($pSharedFunctions);
$dbConnected = true;
if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
    try {
        require_once "$pDbConnection";
    } catch (Exception $e) {
        $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
        $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        $dbConnected = false;
    }


    if ($dbConnected) {

        if (isset($_POST['orders'])) {
            $id = $_POST['orders'];
            $_SESSION['ueo_id'] = $id;
        } else {
            $id = $_SESSION['ueo_id'];
        }

        require_once "print_table_functions.php";

        // Zamówienia w trakcie realizacji 
        $queryProducts = generateProductsQuery(" WHERE o.idUser = $id AND od.Status = 'W trakcie realizacji' ");
        try {
            $sth = $dbh->query($queryProducts);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        $queryDetails = generateDetailsQuery(" WHERE o.idUser = $id AND od.Status = 'W trakcie realizacji' ");
        try {
            $sthDetails = $dbh->query($queryDetails);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania trzeciego do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        // Używane do rowspana w tabeli
        $queryCount = "SELECT idOrders, count(*) AS count FROM orders GROUP BY idOrders";
        try {
            $sthCount = $dbh->query($queryCount);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania drugiego do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }



        echo "<h1 style='text-align: center; margin-top: 20vh;'>Zamówienia w trakcie realizacji</h1>";

        // Count jest robiony tylko raz ale używany we wszystkich tabelach do rowspana
        $arrCount = $sthCount->fetchAll();

        $arrData = $sth->fetchAll();

        printTable($arrData, $arrCount, $sthDetails, "users_orders.php", true, "Zrealizowano", true, "Anulowano");
        // Zamówienia w trakcie realizacji koniec

        echo "<br> <br>";

        // Zamówienia zrealizowane
        $queryProducts = generateProductsQuery(" WHERE o.idUser = $id AND od.Status = 'Zrealizowano' ");
        try {
            $sth = $dbh->query($queryProducts);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        $queryDetails = generateDetailsQuery(" WHERE o.idUser = $id AND od.Status = 'Zrealizowano' ");
        try {
            $sthDetails = $dbh->query($queryDetails);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania drugiego do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }


        echo "<h1 style='text-align: center;'>Zamówienia zrealizowane</h1>";

        $arrData = $sth->fetchAll();

        printTable($arrData, $arrCount, $sthDetails);
        // Zamówienia zrealizowane koniec

        echo "<br> <br>";

        // Zamówienia anulowane
        $queryProducts = generateProductsQuery(" WHERE o.idUser = $id AND od.Status = 'Anulowano' ");
        try {
            $sth = $dbh->query($queryProducts);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }

        $queryDetails = generateDetailsQuery(" WHERE o.idUser = $id AND od.Status = 'Anulowano' ");
        try {
            $sthDetails = $dbh->query($queryDetails);
        } catch (Exception $e) {
            $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania drugiego do bazy danych");
            $_SESSION['general_message'] .= ErrorMessageGenerator($e);
        }


        echo "<h1 style='text-align: center;'>Zamówienia anulowane</h1>";

        $arrData = $sth->fetchAll();

        printTable($arrData, $arrCount, $sthDetails);
        // Zamówienia anulowane koniec

        echo "<br> <br>";
    } else {
        header("Location: $pHome");
    }
} else {
    header("Location: $pHome");
}
?>