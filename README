*PHP képkezelő osztály

	-Képek átméretezése /arány megtartással/
	-Képek levágásáa:   
		Előszőr a képet átméretezi a lehető legközelebbi méretre, amit a képarány megenged
		Majd ezekután a cél mérethez képest levágja  a felesleges képrészeket úgy, hogy középre helyezi a képet.
	   Ezzel a módszerrel egyenlő szélességü és magasságú bélyegképek készíthetőek, akár weboldalak számára is.

	-Keret a képre:
		Megadott vastagságú és színű keretet hoz létre a kép szélén
	-Szöveg a képre:
		Egyedi szöveg kiírása a képre, előre megadott pozíciókra, vagy egyedi pozíciókra.
			Előre megadott pozíciók: 
				Y: fent(TEXT_TOP), lent(TEXT_BOTTOM), középre(TEXT_MIDDLE)
				X: balra (TEXT_LEFT), jobbra(TEXT_RIGHT), középre(TEXT_CENTER)
	-Kép tükrözése:
		Egyedi vagy előre beállított méretű és színü tükrözés hozzáadása a képhez (csak kisebb méretü képekhez ajánlott)




Használati példa: 
	//egy nagyobb kép kisebbre méretezése és elmentése
	$kep = new kepkezelo($teljes_meretu_kep_utvonala);	
	$kep->atmeretezes(800,600);
	$kep->mentes($uj_fajlnev);


	//bélyegkép készítése
	$kep = new kepkezelo($teljes_meretu_kep_utvonala);
	$kep->atmeretezes(100,100);
	$kep->mentes($uj_fajlnev);

	//bélyegkép mutatása
	$kep = new kepkezelo($teljes_meretu_kep_utvonala);
	$kep->atmeretezes(100,100);
	$kep->mutat();


	//bélyegkép tükrözése
	$kep  = new kepkezelo($teljes_meretu_kep_utvonala);
	$kep->levagas(100,100);
	//a tükrözés háttérszíne, hogy beleolvadjon az oldalba
	$kep->tukrozes("#ffffff");
	//belyegkep megjelenites
	$kep->mutatas();

	//kép átméretezése 800x600 arányba, majd fekete szöveg ráhelyezése az átméretezett képre (jobb alsó sarokba)
	$kep = new kepkezelo($teljes_meretu_kep_utvonala);
	$kep->atmeretezes(800,600);			//x	//y	  //fekete betüszín
	$kep->szoveg("Created by: gH0StArthour",12,TEXT_RIGHT,TEXT_BOTTOM,"#00000");
	//kép mentése 100%-os minőségbe és png formátumban
	$kep->mentes("ujkepnev.jpg",100,"png");
