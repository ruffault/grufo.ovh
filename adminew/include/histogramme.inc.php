<?php

function draw_histogramme($tab,$filename)
{
	$maxelement = 17;

	if (count($tab) > $maxelement)
	{
		for ($i = (count($tab) - $maxelement); $i < count($tab); $i++)
		{
			$visites[] = $tab[$i];
		}
	}
	else
	{
		foreach($tab as $element){
			$visites[]=$element;
		}
		//$visites = $tab;
	}
	$largeurImage = 35 * count($visites)-1;
	$hauteurImage = 150;

	$im = @ImageCreate ($largeurImage, $hauteurImage)
					or die ("Erreur lors de la cr�ation de l'image");
	$blanc = ImageColorAllocate ($im, 255, 255, 255);
	$noir = ImageColorAllocate ($im, 0, 0, 0);
	$bleu = ImageColorAllocate ($im, 120, 150, 150);

	// on dessine un trait horizontal pour representer l'axe du temps
	ImageLine ($im, 10, $hauteurImage-10, $largeurImage-10, $hauteurImage-10, $noir);

	for ($mois=1; $mois<=count($visites); $mois++)
	{
		$monthname[$mois] = date("M", mktime(0, 0, 0,
			(date('m') - $mois + 1), date('d'), date('Y')));
	}
	$monthname = array_reverse($monthname);

	// on affiche le numero des 12 mois
	for ($mois=1; $mois<=count($visites); $mois++)
	{
		ImageString ($im, 0, $mois*29, $hauteurImage-9, $monthname[$mois-1], $noir);
	}
	// on dessine un trait vertical pour représenter le nombre de visites
	ImageLine ($im, 10, 10, 10, $hauteurImage-10, $noir);

	// le nombre maximum de visites
	$visitesMax = max($visites) + (max($visites)*10/100);

	// tracé des batons
	for ($mois=1; $mois<= count($visites); $mois++)
	{
		$hauteurImageRectangle = round(($visites[$mois-1]*$hauteurImage)
			/$visitesMax);
		ImageFilledRectangle ($im, $mois*30-7, $hauteurImage-$hauteurImageRectangle,
			$mois*30+7, $hauteurImage-10, $bleu);
		ImageString ($im, 0, $mois*30-7, $hauteurImage-$hauteurImageRectangle-10,
			$visites[$mois-1], $noir);
	}

	// et c'est fini...
	imagepng($im, $filename);
}

?>