<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
  print_r($_SESSION);
  if (!isset($_SESSION['adloggedin']))
  {
      header("Location:index.php");
  }
  header("Location:admin.php");
print_r($_POST);

include_once("connection.php");
$stmt = $conn->prepare("INSERT INTO TblDivision(DivisionID,Name,LeagueID)
    VALUES (NULL,:name,:LID)");
    $stmt->bindParam(':name', $_POST["Divisionname"]);
    $stmt->bindParam(':LID', $_POST["typeofleague"]);
    $stmt->execute();
?>