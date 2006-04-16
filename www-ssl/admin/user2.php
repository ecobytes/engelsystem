<?PHP

$title = "User-Liste";
$header = "Index";
include ("./inc/header.php");
include ("./inc/funktion_db_list.php");
include ("./inc/crypt.php");

if (IsSet($_GET["action"])) 
{
	
	SetHeaderGo2Back();
	echo "Gesendeter Befehl: ". $_GET["action"]. "<br>";

	switch ($_GET["action"]) 
	{
	case "change":
		if (IsSet($_POST["enterUID"]))
		{
			if ($_POST["Type"] == "Normal")
			{
				$SQL = "UPDATE `User` SET ";
				$SQL.= " `Nick` = '". $_POST["eNick"]. "', `Name` = '". $_POST["eName"]. "', ".
					"`Vorname` = '". $_POST["eVorname"]. "', ".
					"`Telefon` = '". $_POST["eTelefon"]. "', ".
					"`Handy` = '". $_POST["eHandy"]. "', ".
					"`DECT` = '". $_POST["eDECT"]. "', ".
					"`email` = '". $_POST["eemail"]. "', ".
					"`Size` = '". $_POST["eSize"]. "', ".
					"`Gekommen`= '". $_POST["eGekommen"]. "', ".
					"`Aktiv`= '". $_POST["eAktiv"]. "', ".
					"`Tshirt` = '". $_POST["eTshirt"]. "', ".
					"`Hometown` = '". $_POST["Hometown"]. "', ".
					"`Menu` = '". $_POST["eMenu"]. "' ".
					"WHERE `UID` = '". $_POST["enterUID"]. 
					"' LIMIT 1;";
				echo "User-";
				$Erg = db_query($SQL, "change user details");
				if ($Erg == 1) {
					echo "&Auml;nderung wurde gesichert...\n";
				} else {
					echo "Fehler beim speichern...\n(". mysql_error($con). ")";
				}
			}
			if ($_POST["Type"] == "Secure")
			{
				$SQL2 = "UPDATE `UserCVS` SET ";
			  	$SQL_CVS = "SELECT * FROM `UserCVS` WHERE UID=". $_POST["enterUID"];
				$Erg_CVS =  mysql_query($SQL_CVS, $con);
				$CVS_Data = mysql_fetch_array($Erg_CVS);
				$CVS_Data_i = 1;
				foreach ($CVS_Data as $CVS_Data_Name => $CVS_Data_Value) 
				{
					if( ($CVS_Data_i+1)%2 && $CVS_Data_Name!="UID")
						$SQL2.= "`$CVS_Data_Name` = '". $_POST[$CVS_Data_i]."', ";
			    		$CVS_Data_i++;
					}
				$SQL2 = substr( $SQL2, 0, strlen($SQL2)-2 );
				$SQL2.= "  WHERE `UID` = '". $_POST["enterUID"]. "' LIMIT 1;";
				echo "<br>Secure-";
				$Erg = db_query($SQL2, "change user CVS");
				if ($Erg == 1) {
					echo "&Auml;nderung wurde gesichert...\n";
				} else {
					echo "Fehler beim speichern...\n(". mysql_error($con). ")";
				}
			}
		}
		break;

	case "delete":
		if (IsSet($_POST["enterUID"]))
		{
			echo "delate User...";
			$SQL="DELETE FROM `User` WHERE `UID` = '". $_POST["enterUID"]. "' LIMIT 1;";
			$Erg = db_query($SQL, "User delete");
			if ($Erg == 1) {
				echo "&Auml;nderung wurde gesichert...\n";
			} else {
				echo "Fehler beim speichern...\n(". mysql_error($con). ")";
			}
			
			echo "<br>\ndelate UserCVS...";
			$SQL2="DELETE FROM `UserCVS` WHERE `UID` = '". $_POST["enterUID"]. "' LIMIT 1;";
			$Erg = db_query($SQL2, "User CVS delete");
			if ($Erg == 1) {
				echo "&Auml;nderung wurde gesichert...\n";
			} else {
				echo "Fehler beim speichern...\n(". mysql_error($con). ")";
			}
			
			echo "<br>\ndelate UserEntry...";
			$SQL3="UPDATE `ShiftEntry` SET `UID` = '0', `Comment` = NULL ".
				"WHERE `UID` = '". $_POST["enterUID"]. "' LIMIT 1;";
			$Erg = db_query($SQL3, "delate UserEntry");
			if ($Erg == 1) {
				echo "&Auml;nderung wurde gesichert...\n";
			} else {
				echo "Fehler beim speichern...\n(". mysql_error($con). ")";
			}
		}
		break;


	case "newpw":
		echo "Bitte neues Kennwort f&uuml;r <b>";
		// Get Nick
		$USQL = "SELECT * FROM User where UID=". $_GET["eUID"];
		$Erg = mysql_query($USQL, $con);
		echo mysql_result($Erg, 0, "Nick");
		echo "</b> eingeben:<br>";
		echo "<form action=\"./user2.php?action=newpwsave\" method=\"POST\">\n";	
		echo "<input type=\"Password\" name=\"ePasswort\">";
		echo "<input type=\"Password\" name=\"ePasswort2\">";
		echo "<input type=\"hidden\" name=\"eUID\" value=\"". $_GET["eUID"]. "\">";
	        echo "<input type=\"submit\" value=\"sichern...\">\n";
	        echo "</form>";
		break;

	case "newpwsave":
		if ($_POST["ePasswort"] == $_POST["ePasswort2"]) 
		{	// beide Passwoerter passen... 
			$_POST["ePasswort"] = PassCrypt($_POST["ePasswort"]);
			$SQL =	"UPDATE `User` SET `Passwort`='". $_POST["ePasswort"]. "' ".
				"where `UID` = '". $_POST["eUID"]. "'";
			$Erg = db_query($SQL, "User new passwort");
			if ($Erg == 1) {
				echo "&Auml;nderung wurde gesichert...\n";
			} else {
				echo "Fehler beim speichern...\n(". mysql_error($con). ")";
			}
		} 
		else 
			echo "Das Passwort wurde nicht &uuml;bereinstimmend eingegeben!";
		break;
	} // end switch

// ende - Action ist gesetzt 
} 
else 
{
	// kein Action gesetzt -> abbruch
	echo "Unzul&auml;ssiger Aufruf.<br>Bitte neu editieren...";
}

include ("./inc/footer.php");
?>

