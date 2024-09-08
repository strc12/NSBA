<!DOCTYPE html>
<html lang="en">
<head>
  <title>NSCBA</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link href="styles.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="images/favicon.png">
  <style>
    #result {
            z-index: 1;
            position: relative; /* Ensure it's positioned relative to its parent */
        }

        /* Ensure container has a higher z-index */
        .container-fluid {
            z-index: 0; /* Higher than navbar */
            position: relative; /* Establish stacking context */
        }
        td, th {
            text-align: center;
        }
    </style>
</head>
<body>
<!--Navigation bar-->
<div id="result">
    <?php
    include_once("navbar.php");
    ?>

</div>
    <br>
<div class="container-fluid">
<h2 class="text-center">View Results</h2>

<label>Fixture: </label>
<select id="matches" >
    <option>Select match</option>
   <?php

   include_once ("connection.php");
   if (isset($_SESSION["Clubid"])){
    $stmt = $conn->prepare("SELECT MatchID,HomeID, AwayID, Season, Fixturedate, TblMatches.DivisionID as DID, 
    leag.name as LN, awt.Clubname as AWC, ht.Clubname as HC, home.DivisionID as hd, away.Name as AWN, home.Name as HN, 
    away.DivisionID as ad , DIVIS.Name as DIVN FROM TblMatches 
    INNER JOIN TblClubhasteam as home ON (TblMatches.HomeID = home.ClubhasteamID) 
    INNER JOIN TblClubhasteam as away ON (TblMatches.AwayID=away.ClubhasteamID) 
    INNER JOIN TblDivision as DIVIS ON (TblMatches.DivisionID = DIVIS.DivisionID) 
    INNER JOIN TblLeague as leag ON (DIVIS.LeagueID = leag.LeagueID) 
    INNER JOIN TblClub as awt ON away.ClubID=awt.ClubID 
    INNER JOIN TblClub as ht ON home.ClubID=ht.ClubID 
    WHERE Season=:SEAS and resultsentered=1 AND awt.ClubID=:club OR ht.ClubID=:club ORDER BY ad ASC,Fixturedate ASC " );

    $stmt->bindParam(':club', $_SESSION["clubid"]);
    $stmt->bindParam(':SEAS', $_SESSION["Season"]);
    }else{
        $stmt = $conn->prepare("SELECT MatchID,HomeID, AwayID, Season, Fixturedate, TblMatches.DivisionID as DID, 
        leag.name as LN, awt.Clubname as AWC, ht.Clubname as HC, home.DivisionID as hd, away.Name as AWN, home.Name as HN, 
        away.DivisionID as ad , DIVIS.Name as DIVN FROM TblMatches 
        INNER JOIN TblClubhasteam as home ON (TblMatches.HomeID = home.ClubhasteamID) 
        INNER JOIN TblClubhasteam as away ON (TblMatches.AwayID=away.ClubhasteamID) 
        INNER JOIN TblDivision as DIVIS ON (TblMatches.DivisionID = DIVIS.DivisionID) 
        INNER JOIN TblLeague as leag ON (DIVIS.LeagueID = leag.LeagueID) 
        INNER JOIN TblClub as awt ON away.ClubID=awt.ClubID 
        INNER JOIN TblClub as ht ON home.ClubID=ht.ClubID 
        WHERE Season=:SEAS and resultsentered=1 ORDER BY ad ASC,Fixturedate ASC " );

    $stmt->bindParam(':SEAS', $_SESSION["Season"]);
    }
   
   $stmt->execute();
   
   while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
   {
       echo("<option value=".$row["MatchID"].'>'.$row["HC"]." ".$row["HN"]." v ".$row["AWC"]." ".$row["AWN"]." ".$row["ad"]." - ".date("d M y",(strtotime($row["Fixturedate"])))." ~ ".$row["LN"]." ".$row["DIVN"]."</option><br>");
   }
   $conn=null;
   ?>
    
</select>
</form>
</div>

<div id="results"></div>
<script>
function showresult(str) {
    if (str == "") {
        document.getElementById("results").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("results").innerHTML = this.responseText;
                calculate(); // Trigger calculation after results are loaded
            }
        };
        xmlhttp.open("GET","Getresultsnew.php?q="+str,true);
        xmlhttp.send();
    }
}

function calculate() {
    var homePointsTotal = 0;
    var homeRubbersTotal = 0;
    var awayPointsTotal = 0;
    var awayRubbersTotal = 0;
    var homeGamesTotal = 0;
    var awayGamesTotal = 0;
    const element1 = document.querySelector('#m19h');
        
    if (element1) {
        count=9;
    }else{
        count=6;

    }
    // Iterate through each match
    for (var i = 1; i <= count; i++) {
        var homeRubbers = 0;
        var awayRubbers = 0;
        var homeGames = 0;
        var awayGames = 0;
        
            // Calculate rubbers and games for each match
            for (var j = 0; j <= 2; j++) {
                var v = 3 * i - 2 + j;
                
            
                
                var homeValue = parseFloat(document.getElementById('m' + v + 'hr').innerText);
                var awayValue = parseFloat(document.getElementById('m' + v + 'ar').innerText);
                //console.log(homeValue,awayValue);
                if (!isNaN(homeValue) && !isNaN(awayValue)) {
                    homeGames += homeValue;
                    awayGames += awayValue;
                }

                if (homeValue > awayValue) {
                    homeRubbers++;
                } else if (homeValue < awayValue) {
                    awayRubbers++;
                }
                
                homePointsTotal += parseFloat(document.getElementById('m' + v + 'h').innerText);
                awayPointsTotal += parseFloat(document.getElementById('m' + v + 'a').innerText);
                
            }

         
            homeRubbersTotal += homeRubbers;
            awayRubbersTotal += awayRubbers;
            

            // Update match result display
            if (homeRubbers > awayRubbers) {
                document.getElementById('m' + i + 'hg').innerText = "1";
                document.getElementById('m' + i + 'ag').innerText = "0";
                homeGamesTotal += 1;
            } else {
                document.getElementById('m' + i + 'hg').innerText = "0";
                document.getElementById('m' + i + 'ag').innerText = "1";
                awayGamesTotal += 1;
            }
    }
    // Update total displays
    document.getElementById('homePointsTotal').innerText = homePointsTotal;
    document.getElementById('homeRubbersTotal').innerText = homeRubbersTotal;
    document.getElementById('awayPointsTotal').innerText = awayPointsTotal;
    document.getElementById('awayRubbersTotal').innerText = awayRubbersTotal;
    document.getElementById('homeGamesTotal').innerText = homeGamesTotal;
    document.getElementById('awayGamesTotal').innerText = awayGamesTotal;
}

$(document).ready(function() {
    // Use jQuery to trigger the calculation when page loads
    //calculate();

    // Trigger calculation on change of match selection
    $("#matches").on("change", function(){
        var selected = $(this).val();
        $("#results").html("You selected: " + selected);
        showresult(selected); // Load results and calculate
    });
});
</script>
</div>
</body>
</html>