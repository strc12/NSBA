<html>
<head>
  <title>NSCBA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="styles.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
<!--Navigation bar-->
<div id="result">
    <?php
    include_once("navbar.php");
    ?>

</div>
<div class="container mt-5">
<?php

#print_r($_POST);
echo("<br>");

$down=$_POST["relegate_team"];
$up=$_POST["promote_team"];
include_once "connection.php";
 foreach ($up as $team) {
        $stmt = $conn->prepare("SELECT 
    DV.LeagueID AS DLID,
    DV.Name AS LN,
    DV.Divisionrank AS DR,
    MinDiv.MinDR,  -- Get the maximum Divisionrank from the subquery
    TblClubhasteam.DivisionID,
    CL.Clubname AS CN,
    TblClubhasteam.Name AS FN,
    TblClubhasteam.ClubhasteamID AS CID
FROM TblClubhasteam 
INNER JOIN TblClub AS CL ON CL.ClubID = TblClubhasteam.ClubID
INNER JOIN TblDivision AS DV ON DV.DivisionID = TblClubhasteam.DivisionID
INNER JOIN (
    SELECT 
        LeagueID, 
        MIN(Divisionrank) AS MinDR  -- Calculate the maximum Divisionrank for each LeagueID
    FROM TblDivision
    GROUP BY LeagueID
) AS MinDiv ON DV.LeagueID = MinDiv.LeagueID
WHERE TblClubhasteam.ClubhasteamID = :team;");

        $stmt->bindParam(':team', $team);
    
   
   $stmt->execute();
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
   {
    #echo("MinDR for " . htmlspecialchars($row["CN"]) . " " . htmlspecialchars($row["FN"]) . " is " . $row["MinDR"] . "current div is ".$row["DR"]."<br>");
        if ($row["DR"]-1>=$row["MinDR"]){
            echo("Promoting ".$row["CN"].$row["FN"]." to Division ".($row["DR"]-1)."<br>");
        } else{
            echo($row["CN"].$row["FN"]." is already in the top Division <br>");
        }
       
       echo("<br>");
   }
} 
#relegation
foreach ($down as $team) {
    $stmt = $conn->prepare("SELECT 
    DV.LeagueID AS DLID,
    DV.Name AS LN,
    DV.Divisionrank AS DR,
    MaxDiv.MaxDR,  -- Get the maximum Divisionrank from the subquery
    TblClubhasteam.DivisionID,
    CL.Clubname AS CN,
    TblClubhasteam.Name AS FN,
    TblClubhasteam.ClubhasteamID AS CID
FROM TblClubhasteam 
INNER JOIN TblClub AS CL ON CL.ClubID = TblClubhasteam.ClubID
INNER JOIN TblDivision AS DV ON DV.DivisionID = TblClubhasteam.DivisionID
INNER JOIN (
    SELECT 
        LeagueID, 
        MAX(Divisionrank) AS MaxDR  -- Calculate the maximum Divisionrank for each LeagueID
    FROM TblDivision
    GROUP BY LeagueID
) AS MaxDiv ON DV.LeagueID = MaxDiv.LeagueID
WHERE TblClubhasteam.ClubhasteamID = :team;");

    $stmt->bindParam(':team', $team);


$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
    #echo("MaxDR for " . htmlspecialchars($row["CN"]) . " " . htmlspecialchars($row["FN"]) . " is " . $row["MaxDR"] . "current div is ".$row["DR"]."<br>");
        
    if ($row["DR"]+1>=$row["MaxDR"]){
        echo("Relegating ".$row["CN"].$row["FN"]." to Division ".($row["DR"]+1)."<br>");
    }else{
        echo($row["CN"].$row["FN"]. " is already in the bottom division no relegation<br>");
    }
  
   echo("<br>");
}
}
   $conn=null;
?>
</div>
</body>
</html>