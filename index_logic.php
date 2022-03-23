<?php

// Dane wymagane 
// v_zmienna - is-valid lub is-invalid - używane do sylizacji inputów
if (isset($_SESSION['v_email'])) {
  $vEmail = $_SESSION['v_email'];
  unset($_SESSION['v_email']);
} else $vEmail = "";

if (isset($_SESSION['v_password'])) {
  $vPassword = $_SESSION['v_password'];
  unset($_SESSION['v_password']);
} else $vPassword = "";

if (isset($_SESSION['v_password2'])) {
  $vPassword2 = $_SESSION['v_password2'];
  unset($_SESSION['v_password2']);
} else $vPassword2 = "";

// Dane opcjonalne
if (isset($_SESSION['v_name'])) {
  $vName = $_SESSION['v_name'];
  unset($_SESSION['v_name']);
} else $vName = "";

if (isset($_SESSION['v_address'])) {
  $vAddress = $_SESSION['v_address'];
  unset($_SESSION['v_address']);
} else $vAddress = "";

if (isset($_SESSION['v_phone'])) {
  $vPhone = $_SESSION['v_phone'];
  unset($_SESSION['v_phone']);
} else $vPhone = "";

// Dane do formularza rejestracji jeśli nie wszystkie były poprawne

$uDefault = "selected";
$uGliwice = "";
$uKatowice = "";
$uZabrze = "";

// Dane wymagane
// u_zmienna - dane wprowadzone od użytkownika. Pojawiają się w formularzu rejestracji jesli któraś z nich była niepoprawna
if (isset($_SESSION['u_email'])) {
  $uEmail = $_SESSION['u_email'];
  unset($_SESSION['u_email']);
} else $uEmail = "";

// Dane opcjonalne
if (isset($_SESSION['u_name'])) {
  $uName = $_SESSION['u_name'];
  unset($_SESSION['u_name']);
} else $uName = "";

if (isset($_SESSION['u_address'])) {
  $uAddress = $_SESSION['u_address'];
  unset($_SESSION['u_address']);
} else $uAddress = "";

if (isset($_SESSION['u_phone'])) {
  $uPhone = $_SESSION['u_phone'];
  unset($_SESSION['u_phone']);
} else $uPhone = "";

if (isset($_SESSION['u_city'])) {

  if ($_SESSION['u_city'] == "Gliwice") {
    $uGliwice = "selected";
    $uDefault = "";
  } else if ($_SESSION['u_city'] == "Katowice") {
    $uKatowice = "selected";
    $uDefault = "";
  } else if ($_SESSION['u_city'] == "Zabrze") {
    $uZabrze = "selected";
    $uDefault = "";
  }
  unset($_SESSION['u_city']);
}





// Po zalogowaniu
$displayBasket = "display: none";
$displayPromocja = "display: none"; // Domyślnie promocja nie jest pokazywana (dla niezalogowanych)

$displayOrders = "display: none";

$displayAdminPanel = "display: none";
$displayEmployeePanel = "display: none";

if (isset($_SESSION['user_email'])) {
  $displaySignIn = 'display: none';
  $displaySignUp = 'display: none';
  $displayLogOut = '';
  $displayBasket = "display: block";
  $displayMain = "display: none";
  $displayPromocja = "display: none";
  $displayOrders = '';
  if (isset($_SESSION['user_permission'])) {
    if ($_SESSION['user_permission'] == "admin") {
      $displayMenu = "display: none";
      $displayKontakt = "display: none";
      $displayPromocja = "display: none";
      $displayMain = "display: none";
      $displayOrders = "display: none";
      $displayAdminPanel = "";
      $displayEmployeePanel = "";
    }
    if ($_SESSION['user_permission'] == "employee") {
      $displayMenu = "display: none";
      $displayKontakt = "display: none";
      $displayPromocja = "display: none";
      $displayOrders = "display: none";
      $displayMain = "display: none";
      $displayAdminPanel = "display: none";
      $displayEmployeePanel = "";
    }
  }
} else {
  $displaySignIn = "";
  $displaySignUp = "";
  $displayLogout = 'display: none';
}
