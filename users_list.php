<!DOCTYPE HTML>
<html>

<head>
  <title>Lista uzytkownikow</title>
  <link rel="stylesheet" href="styles.css">
  <link href="/images/dot_ico/users.ico" rel="icon" type="image/x-icon" />
  <style>
    body {
      background-color: rgba(0, 0, 0, 0.95) !important;
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

    /* #tlo:hover {
      background-color: RGBA(243, 111, 39, 0.5);
    } */
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
  <nav class="navbar navbar-expand-xxl navbar-dark">
    <div class="adminNav">
      <a class="navbar-brand" href="index.php"><img id="logoImg" src="images/logo.jpg" alt="Logo"></a>
      <a class="navbar-brand logomen" href="index.php">Restau<span class="fast-flicker">racja</span> u <span class="flicker">Mentzena</span></a>
    </div>
  </nav>
</body>

</html>

<?php
@session_start();
require_once("paths.php");
$dbConnected = true;
if (isset($_SESSION['user_permission']) && $_SESSION['user_permission'] == "admin") {
  try {
    require_once "$pDbConnection";
  } catch (Exception $e) {
    $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas łączenia z bazą danych");
    $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    $dbConnected = false;
  }

  // Wyświetlanie alertów
  echo "<div class='absolute'>";
  if (isset($_SESSION['general_message'])) {
    echo $_SESSION['general_message'];
    unset($_SESSION['general_message']);
  }
  echo "</div>";
  // 

  if ($dbConnected) {

    // Użytkownicy aktywni 
    try {
      $sth = $dbh->query("SELECT id, name, permission, city, address, phone, email FROM users WHERE isActive");
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }
    echo "<h1 style='text-align: center; margin-top: 15vh;'>Użytkownicy aktywni</h1>";
    echo '<table class="center table " style="width: 80%; margin-left:auto; margin-right:auto">
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Uprawnienia</th>
                <th>Miasto</th>
                <th>Adres</th>
                <th>Numer telefonu</th>
                <th>Email</th>
                <th>Akcja</th>
            </tr>';
    while ($row = $sth->fetch()) {
      // jeśli w bazie jest null to wpisujemy do zmiennej napis NULL, inaczej jest puste pole w tabeli. Jeśli różne od NULL przypisujemy wartość z bazy
      if ($row['name'] == NULL) {
        $name = "NULL";
      } else {
        $name = $row['name'];
      }
      if ($row['city'] == NULL) {
        $city = "NULL";
      } else {
        $city = $row['city'];
      }
      if ($row['address'] == NULL) {
        $address = "NULL";
      } else {
        $address = $row['address'];
      }
      if ($row['phone'] == NULL) {
        $phone = "NULL";
      } else {
        $phone = $row['phone'];
      }

      echo "
            <tr id='tlo'>
                <td>{$row['id']}</td>
                <td>{$name}</td>
                <td>{$row['permission']}</td>
                <td>{$city}</td>
                <td>{$address}</td>
                <td>{$phone}</td>
                <td>{$row['email']}</td>
                <td class='center_form'>
                    <form method='post' action ='$pUsersEdit'> 
                        <button name='edit' class='btn btn-outline-primary' type='submit' value='{$row['id']}'>Edytuj</button>
                    </form>
    
                    <form method='post' action='$pUsersDelete'> 
                         <button name='delete' class='btn btn-outline-danger' type='submit' value='{$row['id']}'>Usuń</button>
                    </form> 
    
                    <form method='post' action ='$pUsersOrders'> 
                        <button name='orders' class='btn btn-outline-warning' type='submit' value='{$row['id']}'>Zamówienia</button>
                    </form>  
                </td>
            </tr>";
    }
    echo '</table>';
    // Użytkownicy aktywni koniec

    // Użytkownicy nieaktywni 
    try {
      $sth = $dbh->query("SELECT id, name, permission, city, address, phone, email FROM users WHERE NOT isActive");
    } catch (Exception $e) {
      $_SESSION['general_message'] = ErrorMessageGenerator("Błąd podczas wykonywania zapytania do bazy danych");
      $_SESSION['general_message'] .= ErrorMessageGenerator($e);
    }
    echo "<h1 style='text-align: center;'>Użytkownicy nieaktywni</h1>"; //<table class="center table table-striped table-hover"
    echo '<table class="center table" style="width: 80%; margin-left:auto; margin-right:auto"> 
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Uprawnienia</th>
                <th>Miasto</th>
                <th>Adres</th>
                <th>Numer telefonu</th>
                <th>Email</th>
                <th>Akcja</th>
            </tr>';
    while ($row = $sth->fetch()) {
      // jeśli w bazie jest null to wpisujemy do zmiennej napis NULL, inaczej jest puste pole w tabeli. Jeśli różne od NULL przypisujemy wartość z bazy
      if ($row['name'] == NULL) {
        $name = "NULL";
      } else {
        $name = $row['name'];
      }
      if ($row['city'] == NULL) {
        $city = "NULL";
      } else {
        $city = $row['city'];
      }
      if ($row['address'] == NULL) {
        $address = "NULL";
      } else {
        $address = $row['address'];
      }
      if ($row['phone'] == NULL) {
        $phone = "NULL";
      } else {
        $phone = $row['phone'];
      }

      echo "
            <tr id='tlo'>
                <td>{$row['id']}</td>
                <td>{$name}</td>
                <td>{$row['permission']}</td>
                <td>{$city}</td>
                <td>{$address}</td>
                <td>{$phone}</td>
                <td>{$row['email']}</td>
                <td class='center_form'>
                    <form method='post' action ='$pUsersActivate'> 
                        <button name='activate' class='btn btn-outline-primary' type='submit' value='{$row['id']}'>Aktywuj</button>
                    </form>
                </td>
            </tr>";
    }
    echo '</table>';
    // Użytkownicy nieaktywni koniec

  } else {
    header("Location: $pHome");
  }
} else {
  header("Location: $pHome");
}
?>