
<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
      }
    print_r($_POST);
include_once ("connection.php");
// Check if the form is submitted to update the item
//deal with where comes from
    if (isset($_SESSION["clubid"])){
        $id =$_SESSION["clubid"];
        $Clubname = $_SESSION['clubname'];
        $redirect="clubadmin.php";
    }else{
        $id = $_POST['id'];
        $Clubname = $_POST['clubname'];
        $redirect="Leagueadmin.php";#from league admin page need to check when admin login set
        echo("SFD");
    }
    
    $Location = $_POST['location'];
    $Website = $_POST['website'];
    $Contactname = $_POST['contactname'];
    $Contactemail = $_POST['contactemail'];
    $Clubnight = $_POST['clubnight'];
    $Contactnumber = $_POST['contactnumber'];
    #print_R($_POST);
    if (isset($_POST['junior'])) {
        $checkboxes = $_POST['junior'];
        if ((in_array("1", $checkboxes)) && (in_array("2", $checkboxes))) {
            $Junior = 2;#both
        } elseif (in_array("1", $checkboxes)) {
            $Junior = 1;#Junior only
        }else{
            $Junior=0;#Senior only
        }
 
    }else{
        $Junior=0;#default to senior f none selected
    }
    
    $sql = "UPDATE tblclub SET clubname = :name, location = :Location, Website = :Website, Contactname =:Contactname, 
    Contactemail = :Contactemail, Clubnight = :Clubnight,
    Contactnumber = :Contactnumber, Junior = :Junior WHERE clubID = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':name' => $Clubname, ':Location' => $Location, ':id' => $id,
     ':Website' => $Website, ':Contactname' => $Contactname, ':Contactemail' => $Contactemail, ':Clubnight' => $Clubnight,
      ':Contactnumber' => $Contactnumber, ':Junior' => $Junior]);
    echo("<script>
        alert('Details Updated');
        
    </script>");#alert followed by redirect
      
      ?>
