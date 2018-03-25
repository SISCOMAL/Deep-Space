<?php

$solarSQL = "SELECT * FROM `".$planetSolar."`";
$solarResult = mysqli_query($db,$solarSQL);
$solarRows = mysqli_fetch_array($solarResult);
if (!$solarRows){
	echo mysqli_error();
	echo $solarSQL;
}
$solarLevelSQL = "SELECT `production`, `maxcap` FROM `SolarPlantStats` WHERE `level` = ".$solarRows['level']."";
$solarLevelResult = mysqli_query($db,$solarLevelSQL);
$solarLevelRows = mysqli_fetch_array($solarLevelResult);

if(!$_SESSION['login_user']){
  //SET SOLAR PLANT PRODUCTION TO 0
  $prodPlanetSolarSql = "UPDATE `".$planetSolar."` SET `production` = 0";
  $prodPlanetSolarResult = mysqli_query($db,$prodPlanetSolarSql);
  $solarSQL2 = "SELECT * FROM `".$planetSolar."`";
  $solarResult2 = mysqli_query($db,$solarSQL2);
  $solarRows2 = mysqli_fetch_array($solarResult2);
} else {
  $solarSQL2 = "SELECT * FROM `".$planetSolar."`";
  $solarResult2 = mysqli_query($db,$solarSQL2);
	
	class GetEnergy extends Thread {
    public function run() {
        /**Get Energy Value and Increment it.**/
		$getEnergy = number_format((float)$solarRows2['energy'], 3, '.', '');
		$maxEnergy = $solarRows2['maxcap'];
		$sumTime = intval('3600');
		$sumEnergy = $solarRows2['production'] / $sumTime;
		$sumEnergy2 = number_format((float)$sumEnergy, 3, '.', '') + $getEnergy;
		$solarEnergy = intval('');
		}
	}
    class ZeroProduction extends Thread {
    public function run() {
        /** SET SOLAR PLANT PRODUCTION TO 0 **/
		$prodPlanetSolarSql = "UPDATE `".$planetSolar."` SET `production` = 0";
		$prodPlanetSolarResult = mysqli_query($db,$prodPlanetSolarSql);
		$solarSQL2 = "SELECT * FROM `".$planetSolar."`";
		$solarResult2 = mysqli_query($db,$solarSQL2);
		$solarRows2 = mysqli_fetch_array($solarResult2);
		}
	}
	class SetEnergy extends Thread {
    public function run() {
        /** SET SOLAR PLANT PRODUCTION ACCORDING TO LEVEL **/
		$prodPlanetSolarSql = "UPDATE `".$planetSolar."` SET `production` = ".$solarLevelRows['production'].", `maxcap` = ".$solarLevelRows['maxcap']."";
		$prodPlanetSolarResult = mysqli_query($db,$prodPlanetSolarSql);
		$prodToEnergySql = "UPDATE `".$planetSolar."` SET `energy` = ".number_format((float)$sumEnergy2, 3, '.', '')."";
		$prodToEnergyResult = mysqli_query($db,$prodToEnergySql);

		$solarSQL3 = "SELECT `energy` FROM `".$planetSolar."`";
		$solarResult3 = mysqli_query($db,$solarSQL3);
		$solarRows3 = mysqli_fetch_array($solarResult3);
		$solarEnergy = $solarRows3['energy']; 
    }
}
    while ($solarRows2 = mysqli_fetch_array($solarResult2)) {
    if ($getEnergy >= $maxEnergy) {
		
		$ZeroProduction = new ZeroProduction();
		$ZeroProduction ->start();
    } else {
		$GetEnergy = new GetEnergy();
		$GetEnergy ->start();
		$SetEnergy = new SetEnergy();
		$SetEnergy ->start();
      }
}
    $solarSQL4 = "SELECT `production` FROM `".$planetSolar."`";
    $solarResult4 = mysqli_query($db,$solarSQL4);
    $solarRows4 = mysqli_fetch_array($solarResult4);
}
?>
