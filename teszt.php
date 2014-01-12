<?php
include_once 'kepkezelo.class.php';

//a kép
$tesztkep = "tesztkep/ubuntu.gif";

//kép megnyitása
$kep = new kepkezelo($tesztkep);
//kép átméretezése (a képarány megmarad)
$kep->atmeretezes(400,400);
//kép mentése minőség romlás nélkül
$kep->mentes("tesztkep/ubuntu_w400.gif",100);

$kep2 = new kepkezelo($tesztkep);
$kep2->atmeretezes(100,100);
$kep2->tukrozes("#ffffff");
$kep2->mentes("tesztkep/ubuntu_turkozes.gif",100);


$kep3 = new kepkezelo($tesztkep);
$kep3->atmeretezes(800,600);
$kep3->szoveg("UBUNTU",64);
$kep3->mentes("tesztkep/ubuntu_szoveg_w800.gif",100);


$kep4 = new kepkezelo($tesztkep);
$kep4->atmeretezes(300,300);
$kep4->keret(5,"#1E90FF");
$kep4->mentes("tesztkep/ubuntu_keret_w300.gif",100);


$kep5 = new kepkezelo($tesztkep);
$kep5->keret(5,"#000000");
$kep5->effekt(IMG_FILTER_SMOOTH,5);
$kep5->mentes("tesztkep/ubuntu_keret_blur.gif",100);

?>
