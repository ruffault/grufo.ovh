<?php
//verification des champs du formulaire


//le prix ne doit pas avoir plus de 2 chiffres apres la virgule et plus de 7
//chiffres avant. Le separateur entre les centimes d'euros et les euros
//et une virgule

// Test du nom de l'ouvrage
function checkname($name)
{
	if (!preg_match("/^.{1,255}$/", $name))
		return true;
}

// Test du nom de l'auteur
function checkauteur($auteur)
{
	if (!preg_match("/^.{1,255}$/", $auteur))
		return true;
}

// Test de la categorie ou de la langue
function checkcategorie($categorie)
{
	if (!preg_match("/^([0-9]{1,8})$/", $categorie))
		return true;
}

//Test de la r�f�rence
function checkreference($reference)
{
	if (!preg_match("/^.{1,15}$/", $reference))
		return true;
}

function reference_exist($reference)
{
	global $link;
  $sql = "SELECT reference
          FROM produit
          WHERE reference = '" . $reference . "'
          ;";
  $res = mysqli_query($link,$sql);
  $count = mysqli_num_rows($res);
	
  mysqli_free_result($sql);
  
  if ($count > 0)
    return true;
  else
    return false;
}

function give_reference($id_product)
{
	global $link;
  $sql = "SELECT reference
          FROM produit
          WHERE id_produit = '" . $id_product . "'
          ;";
  $res = mysqli_query($link,$sql);
  $val = mysqli_fetch_assoc($res);
  mysqli_free_result($sql);
  
  if (isset($val['reference']))
    return $val['reference'];
}


function product_exist($id_product)
{
	global $link;
  $sql = "SELECT id_produit
          FROM produit
          WHERE id_produit = '" . $id_product . "'
          ;";
  $res = mysqli_query($link,$sql);
  $count = mysqli_num_rows($res);
  mysqli_free_result($sql);
  
  if ($count > 0)
    return true;
  else
    return false;
}


//Test du prix
function checkprix($prix)
{
	if (!preg_match("/^[0-9\.]{1,15}$/", $prix))
		return true;
}

//Test de l'url
function checkurl($url)
{
  if (!preg_match("/^((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+$/", $url))
  	return true;
}

//Test du poid
function checkpoid($poid)
{
	if (!preg_match("/^[0-9]{1,8}$/", $poid))
		return true;
}

//Test du d�lai de r�approvisionnement
function checkdelai($delai)
{
	if (!preg_match("/^[0-9]{1,5}$/", $delai))
		return true;
}

//Test le nombre d'ouvrage disponible
function checknbdispo($nbdispo)
{
	if (!preg_match("/^[0-9]{1,6}$/", $nbdispo))
		return true;
}

// Test du nombre de pages ou de termes
function checkpage($page)
{
	if (!preg_match("/^([0-9]{1,10})$/", $page))
		return true;
}

// Test le jour de parution
function checkjourparution($jour)
{
  //test s'il est numerique, s'il fait bien 2 chiffres et s'il est compris entre 0 et 13
	if (!preg_match("/^([0-9]{1,2})$/", $jour) or $jour > 31 or $jour < 1)
		return true;
}

// Test le mois de parution
function checkmoisparution($mois)
{
  //test s'il est numerique, s'il fait bien 2 chiffres et s'il est compris entre 0 et 32
	if (!preg_match("/^([0-9]{1,2})$/", $mois) or $mois > 12 or $mois < 1)
		return true;
}

// Test l'ann�e de parution
function checkanneeparution($annee)
{
  //test si elle est numerique, et si elle fait bien de 1 � 4 chiffres
	if (!preg_match("/^([0-9]{1,4})$/", $annee))
		return true;
}

function ht_livre($prixeuro,$tva)
{
  $prixeuro = $prixeuro / (1 + ($tva/1000));
  $prixeuro = round($prixeuro,2);
  return $prixeuro;
}

/*
function typeprice($societe,$intracommu){
  if ($societe == 1 && !$intracommu)
    $type = "ht";
  elseif (!$societe)
    $type = "ttc";
  else
    $type = "ht";

  return $type;
}
*/

function typeprice($societe,$intracommu,$europe){
  if ($societe == 1 && !$intracommu && !$europe)
    $type = "ht";
  elseif (!$societe)
    $type = "ttc";
  elseif ($societe && !$intracommu && $europe)
    $type = "ttc";
  else
    $type = "ht";
  return $type;
}


function rabais($prixeuro,$rabais)
{
  if ($rabais)
    $prixeuro = $prixeuro - ($prixeuro * $rabais / 100);

  return $prixeuro;
}


// poids d'une commande
function poid_commande($id_commande)
{
	global $link;
  $poid_moyen_article = "1000"; //Poid moyen d'un article en gramme
  $poid = 0;
  $sql_poid = "SELECT produit.id_produit, produit.poid
								FROM panier, commande, produit
								WHERE panier.id_commande = commande.id_commande
								AND commande.id_commande = '".$id_commande."'
								AND panier.id_produit = produit.id_produit
               ;";
  $sql_poid_result = mysqli_query($link,$sql_poid);
  while($val = mysqli_fetch_array($sql_poid_result))
  {
    if ($val['poid'] != 0)
      $poid += $val['poid'];
    else
      $poid += $poid_moyen_article;
  }
	mysqli_free_result($sql_poid_result);

  
  if ($poid > 1000)
    $poid += 400;
  else
    $poid += 300;

  return $poid;
}

//Prix des frais de port suivant le poid et le pays
function frais_port($poid, $id_livraison)
{
	global $link;
  $sql_pays = "SELECT frais_port.pays,
											frais_port.promo_begin+0 as begin,
	 										frais_port.promo_end+0 as end
               FROM frais_port, livraison
               WHERE livraison.id_livraison = '" . $id_livraison . "'
               AND frais_port.pays = livraison.pays
               ;";

  $res_pays = mysqli_query($link,$sql_pays);
  $val_pays = mysqli_fetch_array($res_pays);
  $pays = $val_pays['pays'];

  //On determine la tranche à appliquer suivant le poid
  if ($poid <= 250)
    $limite = 250;
  elseif ($poid <= 500)
    $limite = 500;
  elseif ($poid <= 750)
    $limite = 750;
  elseif ($poid <= 1000)
    $limite = 1000;
  elseif ($poid <= 1500)
    $limite = 1500;
  elseif ($poid <= 2000)
    $limite = 2000;
  elseif ($poid <= 3000)
    $limite = 3000;
  elseif ($poid <= 4000)
    $limite = 4000;
  elseif ($poid <= 5000)
    $limite = 5000;
  elseif ($poid <= 6000)
    $limite = 6000;
  elseif ($poid <= 7000)
    $limite = 7000;
  elseif ($poid <= 8000)
    $limite = 8000;
  elseif ($poid <= 9000)
    $limite = 9000;
  elseif ($poid <= 10000)
    $limite = 10000;
  elseif ($poid <= 11000)
    $limite = 11000;
  elseif ($poid <= 12000)
    $limite = 12000;
  elseif ($poid <= 13000)
    $limite = 13000;
  elseif ($poid <= 14000)
    $limite = 14000;
  elseif ($poid <= 15000)
    $limite = 15000;
  elseif ($poid <= 16000)
    $limite = 17000;
  elseif ($poid <= 18000)
    $limite = 18000;
  elseif ($poid <= 19000)
    $limite = 19000;
  elseif ($poid <= 20000)
    $limite = 20000;
  elseif ($poid <= 25000)
    $limite = 25000;
  elseif ($poid <= 30000)
    $limite = 30000;
  elseif ($poid > 30000)
    $limite = 30000;
  
  $sql = "SELECT mode, `" . $limite . "` FROM frais_port
          WHERE pays = '" . $pays . "'";

	// On evite le tarif "courrier ordinaire" si le poids depasse 3000 grammes pour la France et 2000 pour l'international pour les pays choisis
	$array_pays_internationaux_avec_courrier_ordinaire_autorise = array ("AD", "AN", "AR", "AT", "AU", "BE", "BG", "BO", "BR", "CA", "CH", "CL", "CO", "CY", "CZ", "DE", "DK", "ES", "FI", "GB", "GF", "GG", "GP", "GR", "GY", "HK", "HR", "HU", "IE", "IL", "IS", "IT", "JP", "LI", "LT", "LU", "MC", "MQ", "MX", "NC", "NL", "NO", "NZ", "PE", "PF", "PL", "PT", "RE", "RO", "RU", "SC", "SE", "SG", "SI", "SK", "TR", "TW", "US", "VA", "VE", "ZA");
	if ($poid > 3000 && $pays == "FR")
	{
		$sql.=" AND mode <> 'courrier_ordinaire';";
	}
	else if ($poid > 2000 && in_array($array_pays_internationaux_avec_courrier_ordinaire_autorise,$pays))
	{
		$sql.=" AND mode <> 'courrier_ordinaire';";
	}
	else
	{
		$sql.=";";
	}

  $res = mysqli_query($link,$sql);
  $i = 0;

	$today = date('YmdHis');

  while ($val = mysqli_fetch_array($res))
  {
    $frais_port[$i]['mode'] = $val['mode'];

		//Si une les frais de port sont gratuit en ce moment
		if ((isset($val_pays['begin']) && isset($val_pays['end']))
			&& ($val_pays['begin'] < $today && $today < $val_pays['end']))
		{
		    $frais_port[$i]['prix'] = '0.00';
		}
		else
	    $frais_port[$i]['prix'] = $val[$limite];

		$i++;
  }

  return $frais_port;
}
//3 fonctions pour aider admin � g�rer le param�trage des frais de port
//GR 10 novembre 2020
//
function maj_frais_port_new($pays='fr',$mode_calcul,$mode,$poids_tarif)
{

// il s'agit de mette � jour les donnn�es existantes de la table
	$sql = "update frais_port_new";
}


//Infos sur les frais de port par id_frais_port
function infos_frais_port_for_admin($id_frais_port,$id_commande)
{
	global $link;
  $sql_nom_frais_port = "SELECT frais_port.mode
               FROM frais_port 
               WHERE frais_port.id_frais_port = '" . $id_frais_port . "'
               LIMIT 1
               ;";

  $res_nom_frais_port = mysqli_query($link,$sql_nom_frais_port);
  $val_nom_frais_port = mysqli_fetch_array($res_nom_frais_port);
  $frais_port2['0']['mode'] = $val_nom_frais_port['mode'];

  $sql_prix_frais_port = "SELECT commande.prix_port
               FROM commande 
               WHERE commande.id_commande = '" . $id_commande . "'
               LIMIT 1
               ;";

  $res_prix_frais_port = mysqli_query($link,$sql_prix_frais_port);
  $val_prix_frais_port = mysqli_fetch_array($res_prix_frais_port);
  $frais_port2['0']['prix'] = $val_prix_frais_port['prix_port'];



  return $frais_port2;
}


function update_modif_date($id_product, $field)
{
	global $link;
	$id_product = addslashes($id_product);
	$field = addslashes($field);

	$today = date("Y-m-d");

	$sql = "UPDATE `produit`
					SET `$field` = '$today'
					WHERE id_produit = '$id_product'
				 ;";
					
	mysqli_query($link,$sql);

	return 0;
}

function is_modified($content, $fieldname, $id_product)
{
	global $link;

	$content = addslashes($content);
	$fieldname = addslashes($fieldname);
	$id_product = addslashes($id_product);

	$sql = "SELECT id_produit
					FROM produit
					WHERE $fieldname = '$content'
					AND id_produit = '$id_product'
					;";
	$res = mysqli_query($link,$sql);
	if (mysqli_num_rows($res) == 0)
		return true;
	mysqli_free_result($res);
	return false;
}

function date_fr($timestamp=0)
{
	$tabjour = array('Dimanche', 'Lundi', 'Mardi',
									 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
  
	$tabmois = array('0', 'janvier', 'f�vrier', 'mars', 'avril',
									 'mai', 'juin', 'juillet', 'aout', 'septembre',
									 'octobre', 'novembre', 'd�cembre');
	
	if ($timestamp == 0)
		$timestamp = time();
  
	$jsem = date('w', $timestamp);
  $jmois = date('j', $timestamp);
  $mois = date('n', $timestamp);
  $annee = date('Y', $timestamp);
 
  $today = $tabjour[$jsem]." $jmois ".$tabmois[$mois]." $annee";
 
  return $today;
}

function pageSeen($begining, $ending)
{
	global $link;
	$sql = "SELECT count(*) as total
					FROM log
					WHERE `date` BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59';";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	mysqli_free_result($res);
	return $val['total'];
}

// unique visitor
function visitor($begining, $ending)
{
	global $link;
	$sql = "SELECT count(distinct(log.ip)) as total
					FROM log
					WHERE log.date BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59';";

	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	mysqli_free_result($res);

	return $val['total'];
}

// most seen product
function mostSeen($quantity = 10)
{
	global $link;
	$sql = "SELECT nom_fr as nom, nb_hit
					FROM hit, produit
					WHERE hit.id_produit = produit.id_produit
					ORDER by nb_hit DESC
					LIMIT $quantity;";
	
	$res = mysqli_query($link,$sql);

	$i = 0;

	while($val = mysqli_fetch_array($res))
	{
		$product[$i]['nom'] = $val['nom'];
		$product[$i]['nb_hit'] = $val['nb_hit'];
		$i++;
	}
	mysqli_free_result($res);
	return $product;
}

function mostBuy($quantity = 10, $begining, $ending)
{
	global $link;
	$sql = "SELECT produit.nom_fr as nom, SUM(panier.quantite) as quantite
          FROM produit, panier, commande
          WHERE commande.statut = 6
					AND commande.date_validation BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					AND commande.id_commande = panier.id_commande
					AND produit.id_produit = panier.id_produit
          GROUP BY produit.id_produit
          ORDER by quantite DESC
          LIMIT $quantity;";
	$res = mysqli_query($link,$sql);

	$i = 0;
	while($val = mysqli_fetch_array($res))
	{
		$product[$i]['nom'] = $val['nom'];
		$product[$i]['quantite'] = $val['quantite'];
		$i++;
	}
	mysqli_free_result($res);
	return $product;
}

function bestBuyer($quantity = 10, $begining, $ending)
{
	global $link;
	$sql = "SELECT membre.id_membre, membre.login, membre.nom,
					membre.prenom, sum(quantite * prix_unitaire) as prix_calc
					FROM utilisateur, commande, membre, panier
					WHERE commande.id_utilisateur = utilisateur.id_utilisateur
					AND commande.date_validation BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					AND commande.statut = 6
					AND membre.id_membre = utilisateur.id_membre
					AND panier.id_commande = commande.id_commande
					GROUP by membre.id_membre
					ORDER by prix_calc DESC
					LIMIT $quantity;";
	$res = mysqli_query($link,$sql);

	$i = 0;
	while($val = mysqli_fetch_array($res))
	{
		$buyer[$i]['id'] = $val['id_membre'];
		$buyer[$i]['login'] = $val['login'];
		$buyer[$i]['prenom'] = $val['prenom'];
		$buyer[$i]['nom'] = $val['nom'];
		$buyer[$i]['prix'] = $val['prix_calc'];
		$i++;
	}
	mysqli_free_result($res);
	return $buyer;
}


function newClient($quantity = 10)
{
	global $link;
	$sql = "SELECT id_membre, login, email, nom, prenom
				 FROM membre
				 ORDER by id_membre DESC
				 LIMIT $quantity;";
	$res = mysqli_query($link,$sql);

	$i = 0;
	while($val = mysqli_fetch_array($res))
	{
		$client[$i]['id'] = $val['id_membre'];
		$client[$i]['login'] = $val['login'];
		$client[$i]['prenom'] = $val['prenom'];
		$client[$i]['nom'] = $val['nom'];
		$i++;
	}
	mysqli_free_result($res);

	return $client;
}

function affaireAmount($begining, $ending)
{
	global $link;
	$sql = "SELECT ROUND(SUM(commande.prix_total_ttc) + SUM(commande.prix_port)) as ca
					FROM commande
					WHERE commande.prix_total_ttc <> 0
					AND commande.date_validation BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					AND commande.statut = 6;";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	if (!$val['ca'])
		$val['ca'] = 0;
	mysqli_free_result($res);
	return $val['ca'];
}

function countCommandeSend($begining, $ending)
{
	global $link;
	$sql = "SELECT COUNT(id_commande) as total
					FROM commande
					WHERE commande.statut = 6
					AND commande.date_validation BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59';";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	mysqli_free_result($res);
	return $val['total'];
}

function countNewCommande($begining, $ending)
{
	global $link;
	$sql = "SELECT COUNT(*) as total
					FROM commande
					WHERE commande.date_validation BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59';";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	if (!$val['total'])
		$val['total'] = 0;
	mysqli_free_result($res);
	return $val['total'];
}


function evolution($start, $end)
{
	$evol=0;
	if ($start) $evol = $end / $start * 100 - 100;
	$evol = round($evol,1);
	if ($evol > 0)
		$evol = "+$evol";
	return $evol;
}

function pageSeenPerHour($begining = '', $ending = '')
{
	global $link;
	if ($begining == '' && $ending == '')
		$begining = $ending = date("Y-m-d");

	$sql = "SELECT count(date_format(date,'%H')) as visite,
						date_format(date,'%H') as hour
					FROM  `log` 
					WHERE date BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					GROUP  BY date_format(date,'%H');";
	$res = mysqli_query($link,$sql);

	while ($val = mysqli_fetch_array($res))
		$frequentation[$val['hour']] = $val['visite'];
	mysqli_free_result($res);
	for($i = 0; $i < 24; $i++)
	{
		$j = $i;
		
		if (strlen($i) == 1)
			$j = "0$j";
			
		if (!$frequentation[$j])
			$frequentation[$j] = '0';
	}

	return $frequentation;
}

function visitorPerHour($begining = '', $ending = '')
{
	global $link;
	if ($begining == '' && $ending == '')
		$begining = $ending = date("Y-m-d");
	
	$sql = "SELECT date_format(date, '%H') as hour
					FROM  `log`
					WHERE date BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					group by ip
					ORDER by hour;";
	$res = mysqli_query($link,$sql);

	for($i = 0; $i < 24; $i++)
		$frequentation[$i] = 0;

	while ($val = mysqli_fetch_array($res))
		$frequentation[round($val['hour'])] += 1;
	mysqli_free_result($res);
	return $frequentation;
}

function referant($begining = '', $ending = '')
{
	global $link;
	if ($begining == '' && $ending == '')
		$begining = $ending = date("Y-m-d");
	
	$sql = "SELECT count(referant) as nb, referant
					FROM  `log`
					WHERE referant <> ''
					AND referant not like 'http://www.dicoland.com%'
					AND referant not like 'http://dicoland.com%'
					AND date BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					group by referant
					ORDER by nb DESC;";
	$res = mysqli_query($link,$sql);

	while ($val = mysqli_fetch_array($res))
		$tab[extractDomain($val["referant"])] += $val["nb"];
	mysqli_free_result($res);	
	asort($tab);
	$tab = array_reverse($tab);

	return $tab;
}

function keyword($begining = '', $ending = '')
{
	global $link;
	if ($begining == '' && $ending == '')
		$begining = $ending = date("Y-m-d");
	
	$sql = "SELECT count(referant) as nb, referant
					FROM  `log`
					WHERE referant <> ''
					AND referant not like 'http://www.dicoland.com%'
					AND referant not like 'http://dicoland.com%'
					AND referant not like 'http://maisondudico.com%'
					AND referant not like 'http://www.maisondudico.com%'
					AND referant not like 'http://ns30538.ovh.net%'
					AND referant not like 'http://lmdd.com%'
					AND referant not like 'http://www.lmdd.com%'
					AND date BETWEEN '$begining 00:00:00'
					AND '$ending 23:59:59'
					group by referant
					ORDER by nb DESC;";
	$res = mysqli_query($link,$sql);

	while ($val = mysqli_fetch_array($res))
	{
		$key = extractKeyword($val["referant"]);
		if($key != '')
			$tab[$key] += $val["nb"];
	}
	if(isset($tab))
	{
		asort($tab);
		$tab = array_reverse($tab);
	}
	return $tab;
}

function extractDomain($url)
{
	$url = preg_replace('/^http://{1}/','',$url); //on enleve le http:// GR 16/10/20
	$url = preg_replace('/^www\.{1}/','',$url); //on enleve le www.
	$url = preg_replace('@/{1}.*@','',$url); // on enleve tout ce qui est pr�c�d� de /
	$url = preg_replace('/:{1}.*/','',$url); // on enleve tout ce qui est preced� de : ce qui donne par exemple yatoufourmi.com dans http://www.yatoufourmi.com/index.php:tot

	return $url;
}

function fixChr($string)
{
	$string = urldecode($string);
	$string = utf8_decode($string);
	$url = str_replace('+',' ',$url);

	return $string;
}

function google_word($url)
{
	
	$url = preg_replace('/^.*(\&|\?)q=/','',$url);
	$url = preg_replace('/^.*(\&|\?)as_q=/','',$url);
	$url = preg_replace('/&.*$/','',$url);
	$url = fixChr($url);
	return $url;
}

function yahoo_word($url)
{
	$url = preg_replace('/^.*(\?p=|&p=|\?va=|&va=)/','',$url);
	$url = preg_replace('/&.*$/','',$url);
	
	return $url;
}

function extractKeyword($url)
{
	$url = preg_replace('/^http://{1}/','',$url);
	$url = preg_replace('/^www\.{1}/','',$url);
	$url = preg_replace('/:{1}.*/','',$url);

	if(
		substr($url,0,6) == 'google'
		|| substr($url,0,6) == '.googl'
		|| substr($url,0,10) == 'www.google'
		|| substr($url,0,9) == 'ww.google'
		)
	{
		$url = google_word($url);
	}
	elseif(substr($url,0,19) == 'fr.search.yahoo.com')
		$url = yahoo_word($url);

	$url = preg_replace('@/{1}.*@','',$url);
	$url = preg_replace('/.*\..*/','',$url);
	$url = preg_replace('/XXXX/','',$url);

	return $url;
}

function monthLessMonth($diff)
{
	$tabmois = array(
									 '1' => 'janvier',
									 '2' => 'f�vrier',
									 '3' => 'mars',
									 '4' => 'avril',
									 '5' => 'mai',
									 '6' => 'juin',
									 '7' => 'juillet',
									 '8' => 'ao�t',
									 '9' => 'septembre',
									 '10' => 'octobre',
									 '11' => 'novembre',
									 '12' => 'd�cembre'
									);
	$month = (int) date("m",mktime(0, 0, 0, date("m") - $diff, 01, date("Y")));
	$month = ucfirst($tabmois[$month]);
	return $month;

}


function affaireOnMonth($date)
{
	global $link;

	$endday = lastMonthDay(substr($date,0,4), substr($date,5,2));
	
	for ($i = 1; $i < $endday + 1; $i++)
		$tab[$i] = 0;
	
	$date = substr($date ,0,7);
	$sql = "SELECT date_format(commande.date_validation, '%d') AS dateval,
						ROUND(SUM(prix_total_ttc) + SUM(prix_port)) AS ca
					FROM commande
					WHERE prix_total_ttc <>0 AND commande.date_validation
					BETWEEN '$date-01 00:00:00'
					AND '$date-31 23:59:59'
					AND statut =6
					GROUP BY dateval;";
	$res = mysqli_query($link,$sql);
	while($val = mysqli_fetch_array($res))
		$tab[(int) $val['dateval']] = $val['ca'];
	
	mysqli_free_result($res);
	return $tab;
}

function affaireOnYear($year)
{
	global $link;
	for ($i = 1; $i < 365 + 1; $i++)
		$tab[$i] = 0;
	
	$sql = "SELECT date_format(commande.date_validation, '%d') AS dateval,
						ROUND(SUM(prix_total_ttc) + SUM(prix_port)) AS ca
					FROM commande
					WHERE prix_total_ttc <>0 AND commande.date_validation
					BETWEEN '$year-01-01 00:00:00'
					AND '$year-12-31 23:59:59'
					AND statut =6
					GROUP BY dateval;";
	$res = mysqli_query($link,$sql);
	//echo $sql;
	while($val = mysqli_fetch_array($res))
	{
		$tab[(int) $val['dateval']] = $val['ca'];
	}
	mysqli_free_result($res);
	return $tab;
}


function lastMonthDay($year, $month)
{
	$lastday = 28;
	while (checkdate($month, $lastday, $year))
		$lastday++;

	return --$lastday;
}

function countMember()
{
	global $link;
	$sql = "SELECT COUNT(*) as total FROM membre;";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	mysqli_free_result($res);

	return $val['total'];
}


function clientMostCommande($begining = '', $ending = '', $number = 0)
{
	global $link;
	
	if ($begining == '' && $ending == '')
	{
		$begining = date("Y-m");
		$ending = date("Y-m");
	}

	$begining = substr($begining, 0, 7) . '-01';
	$ending = substr($ending, 0, 7) . '-31';
	
	$sql = "SELECT count(commande.id_commande) as total,
						membre.id_membre,
						membre.login,
						membre.prenom,
						membre.nom
	FROM membre, utilisateur, commande
	WHERE membre.id_membre = utilisateur.id_membre
	AND utilisateur.id_utilisateur = commande.id_utilisateur
	AND commande.statut >2
	AND commande.statut <7
	AND commande.date_validation
	BETWEEN '$begining 00:00:00' AND '$ending 23:59:59'		
	GROUP by membre.id_membre
	ORDER by total desc
	LIMIT $number;";

	$res = mysqli_query($link,$sql);

	$i = 0;
	while ($val = mysqli_fetch_array($res))
	{
		$tab[$i]['total'] = $val['total'];
		$tab[$i]['id_membre'] = $val['id_membre'];
		$tab[$i]['login'] = $val['login'];
		$tab[$i]['prenom'] = $val['prenom'];
		$tab[$i]['nom'] = $val['nom'];

		$i++;
	}
	mysqli_free_result($res);

	return (isset($tab) ? $tab : '') ;
}

function clientMostBuy($begining = '', $ending = '', $number = 0)
{
	global $link;
	
	if ($begining == '' && $ending == '')
	{
		$begining = date("Y-m");
		$ending = date("Y-m");
	}

	$begining = substr($begining, 0, 7) . '-01';
	$ending = substr($ending, 0, 7) . '-31';
	
	$sql = "SELECT round(sum(commande.prix_total_ttc)) as total,
						membre.id_membre,
						membre.login,
						membre.prenom,
						membre.nom
						FROM membre, utilisateur, commande
						WHERE membre.id_membre = utilisateur.id_membre
						AND utilisateur.id_utilisateur = commande.id_utilisateur
						AND commande.statut >2
						AND commande.statut <7
						AND commande.date_validation
						BETWEEN '$begining 00:00:00' AND '$ending 23:59:59'		
						GROUP by membre.id_membre
						ORDER by total desc
						LIMIT $number;";

	$res = mysqli_query($link,$sql);

	$i = 0;
	while ($val = mysqli_fetch_array($res))
	{
		$tab[$i]['total'] = $val['total'];
		$tab[$i]['id_membre'] = $val['id_membre'];
		$tab[$i]['login'] = $val['login'];
		$tab[$i]['prenom'] = $val['prenom'];
		$tab[$i]['nom'] = $val['nom'];

		$i++;
	}
	mysqli_free_result($res);

	return (isset($tab) ? $tab : '') ;
}


function olderLogDate()
{
	global $link;
	$sql = 'SELECT date as older
					FROM statday
					ORDER BY date
					LIMIT 1;';
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	mysqli_free_result($res);

	return $val['older'];
}

function youngerLogDate()
{
	global $link;
	$sql = 'SELECT date as younger
					FROM statday
					ORDER BY date DESC
					LIMIT 1;';
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	mysqli_free_result($res);

	return $val['younger'];
}


function articleMostCommande($begining = '', $ending = '', $number = 0)
{
	global $link;
	
	if ($begining == '' && $ending == '')
	{
		$begining = date("Y-m");
		$ending = date("Y-m");
	}

	$begining = substr($begining, 0, 7) . '-01';
	$ending = substr($ending, 0, 7) . '-31';
	
	$sql = "SELECT count(panier.id_produit) as total,";
	$sql .=" 					panier.id_produit as idprod, produit.nom_fr as nom";
	$sql .="				FROM commande, panier, produit";
	$sql .=" 				WHERE commande.id_commande = panier.id_commande";
	$sql .=" 				AND panier.id_produit = produit.id_produit";
	$sql .=" 				AND commande.statut > 2";
	$sql .=" 				AND commande.statut < 7";
	$sql .=" 				AND commande.date_validation";
	$sql .="				BETWEEN '".$begining." 00:00:00' AND '".$ending." 23:59:59'";		
	$sql .=" 				GROUP by idprod";
	$sql .=" 				ORDER by total desc";
	$sql .=" 				LIMIT ".$number;
	//echo $sql;
	
	$res = mysqli_query($link,$sql);
	
	$j = 0;
	while ($val = mysqli_fetch_array($res))
	{
		$tab[$j]['total'] = $val['total'];
		$tab[$j]['idprod'] = $val['idprod'];
		$tab[$j]['nom'] = $val['nom'];
		$j++;
		
	}
	
	mysqli_free_result($res);
	return (isset($tab) ? $tab : '') ;
}

function articleMostSeen($number = 0)
{
	global $link;
	
	$sql = "SELECT nb_hit as total,
						produit.id_produit,
						produit.nom_fr as nom
					FROM hit, produit
					WHERE produit.id_produit = hit.id_produit
					ORDER by total desc
					LIMIT $number;";
	$res = mysqli_query($link,$sql);
	$i = 0;
	while ($val = mysqli_fetch_array($res))
	{
		$tab[$i]['total'] = $val['total'];
		$tab[$i]['id_produit'] = $val['id_produit'];
		$tab[$i]['nom'] = $val['nom'];
		$i++;
	}
	mysqli_free_result($res);

	
	return $tab;
}

function articleMostAdd2Panier($begining = '', $ending = '', $number = 0)
{
	global $link;
	
	if ($begining == '' && $ending == '')
	{
		$begining = date("Y-m");
		$ending = date("Y-m");
	}

	$begining = substr($begining, 0, 7) . '-01';
	$ending = substr($ending, 0, 7) . '-31';
	
	$sql = "SELECT count(panier.id_produit) as total,
						panier.id_produit, produit.nom_fr as nom
					FROM commande, panier, produit
					WHERE commande.date_creation
					BETWEEN '$begining 00:00:00' AND '$ending 23:59:59'		
					AND commande.id_commande = panier.id_commande
					AND panier.id_produit = produit.id_produit
					GROUP by id_produit
					ORDER by total desc
					LIMIT $number;";
	
	$res = mysqli_query($link,$sql);

	$i = 0;
	while ($val = mysqli_fetch_array($res))
	{
		$tab[$i]['total'] = $val['total'];
		$tab[$i]['id_produit'] = $val['id_produit'];
		$tab[$i]['nom'] = $val['nom'];
		$i++;
	}

	mysqli_free_result($res);
	
	return $tab;
}


function convert_lang($code = 'fr')
{

	$tablang = array(
										'fr' => 'fran�ais',
										'en' => 'anglais',
										'es' => 'espagnol',
										'de' => 'allemand',
										'it' => 'italien'
									);

	return (isset($tablang[$code]) ? $tablang[$code] : '') ;
}

function abrev2lang($code)
{
	global $link;
	$sql = "SELECT nom_fr as nom
					FROM pays
					WHERE abrev = '$code';";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);
	mysqli_free_result($res);

	return $val['nom'];
}

function endate2fr($date)
{
	list($y, $m, $d) = explode('-', $date);
	$date = "$d/$m/$y";

	return $date;
}

require 'calendar.inc.php';

function move_date($date)
{
	list($y, $m, $d) = explode('-',$date);
	return "$d/$m/$y";
}

function delai_livraison($mode)
{
  $temp = 0;
  switch ($mode)
  {
    case "courrier_ordinaire":
      $temps = 0;
      break;
    case "colissimo_fr":
      $temps = 2;
      break;
    case "colispostal_prio_a":
      $temps = 8;
      break;
    case "colispostal_prio_b":
      $temps = 8;
      break;
    case "colispostal_prio_c":
      $temps = 10;
      break;
    case "colispostal_prio_d":
      $temps = 10;
      break;
    case "colispostal_eco_b":
      $temps = 10;
      break;
    case "colispostal_eco_c":
      $temps = 12;
      break;
    case "colispostal_eco_d":
      $temps = 15;
      break;
    case "colissimo_eur":
      $temps = 4;
      break;
    case "colissimo_dom":
      $temps = 4;
      break;
  }
  return $temps;
}

function temps_total_livraison($id_commande)
{
	global $link;
  $delai_produit = 0;
  $delai_mode = 0;

  $sql = "SELECT produit.delai_reapprovisionnement
          FROM commande, panier, produit
          WHERE commande.id_commande = '" . addslashes($id_commande) ."'
          AND commande.id_commande = panier.id_commande
          AND panier.id_produit = produit.id_produit
          AND disponible = 0
          ORDER BY delai_reapprovisionnement DESC
          LIMIT 1;";
  $res = mysqli_query($link,$sql);
  $val = mysqli_fetch_array($res);
  $delai_produit = $val['delai_reapprovisionnement'];

  $sql = "SELECT mode
          FROM commande, livraison, frais_port
          WHERE commande.id_commande = '" . addslashes($id_commande) ."'
          AND commande.id_livraison = livraison.id_livraison
          AND livraison.id_frais_port = frais_port.id_frais_port
          ;";
  $res = mysqli_query($link,$sql);
  $val = mysqli_fetch_array($res);
  $delai_mode = delai_livraison($val['mode']);

  $temps_total = $delai_produit + $delai_mode;
  
  return $temps_total;
}

//Return the reference code
function refcode($id)
{
	global $link;
	$id = addslashes($id);
	$sql = "SELECT code
					FROM commande
					WHERE id_commande = '$id'
					LIMIT 1;";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	$code = $val['code'];

	return $code;
}

function val_date($id)
{
	global $link;
	$id = addslashes($id);
	$sql = "SELECT date_validation
					FROM commande
					WHERE id_commande = '$id'
					LIMIT 1;";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	$date = $val['date_validation'];

	return $date;
}

function member_info($id_member)
{
	global $link;
	$sql = "SELECT *
					FROM membre
					WHERE id_membre = '$id_member'
					AND membre.pays = pays.abrev;";
	$res = mysqli_query($link,$sql);

	if (isset($res))
	{
		$member = mysqli_fetch_array($res);

		foreach ($member as $key => $value)
			$member[$key] = utf8_decode($value);

		$member['civility_txt'] = civility_txt($member['civilite']);
		print_r($member);		
		return $member;
 	}	
}

function delivery_info($id_delivery)
{
	global $link;
	$sql = "SELECT *
					FROM livraison, pays
					WHERE id_livraison = '$id_delivery'
					AND livraison.pays = pays.abrev;";
	$res = mysqli_query($link,$sql);
	if (isset($res))
	{
		$delivery = mysqli_fetch_array($res);

		foreach ($delivery as $key => $value)
			$delivery[$key] = utf8_decode($value);

		$delivery['civility_txt'] = civility_txt($delivery['civilite']);
		return $delivery;
 	}	
}

function billing_info($id_billing)
{
	global $link;
	$sql = "SELECT *
					FROM facturation, pays
					WHERE facturation.id_facturation = '$id_billing'
					AND facturation.pays = pays.abrev;";

	$res = mysqli_query($link,$sql);
	if (isset($res))
	{
		$billing = mysqli_fetch_array($res);

		foreach ($billing as $key => $value)
			$billing[$key] = utf8_decode($value);

		$billing['civility_txt'] = civility_txt($billing['civilite']);
		return $billing;
 	}
}

function civility_txt($id_civility)
{
	switch ($id_civility)
	{
		case 1:
			$txt = "M.";
			break;
		case 2:
			$txt = "Mme";
			break;
		case 3:
			$txt = "Mlle";
			break;
	}
	return $txt;
}

function country_txt($abrev, $lang)
{
	global $link;
	$sql = "SELECT nom_$lang
					FROM pays
					WHERE abrev = '$abrev'
					;";
	$res = mysqli_query($link,$sql) or die(mysqli_error($link));

	if (isset($res))
	{
		$country = mysqli_fetch_row($res);
		return $country[0];
 	}
}

function is_europeean($id_membre)
{
	global $link;
	$sql = "SELECT europe, pays
					FROM pays, membre
					WHERE membre.id_membre = '" . $id_membre . "'
					AND membre.pays = pays.abrev
					;";
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_array($res);
	return $val;
}

function command_info($command_id)
{
	global $link;
	$sql = 'SELECT *
					FROM commande
					WHERE id_commande = "' . $command_id . '"
					;';
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);

	if (isset($val))
	{
		return $val;
	}
}

function give_basket($commandid)
{
	global $link;
	global $applicationlang;

	$sql = 'SELECT panier.id_panier,
									panier.id_commande,
									panier.id_produit,
									panier.prix_unitaire as prix_unitaire_ht,
									panier.discount,
									SUM(panier.quantite) as quantite,
									panier.taxes,
									produit.reference,
									produit.nom_' . $applicationlang . ' as nom,
									produit.disponible,
									produit.delai_reapprovisionnement,
									auteur.nom as auteur
									FROM panier, produit, type, auteur
									WHERE panier.id_commande = "' . $commandid . '"
									AND panier.id_produit = produit.id_produit
									AND produit.id_auteur = auteur.id_auteur
									AND produit.id_type = type.id_type
									GROUP BY panier.id_produit
									;';
	$res = mysqli_query($link,$sql);
	$i = 0;

	while ($val = mysqli_fetch_assoc($res))
	{
		$basket[$i] = $val;

		$basket[$i]['prix_rabais'] = twodecimal(round_up(($val['prix_unitaire_ht'] * $val['discount'] / 100), 2));


		$basket[$i]['prix_unitaire_ht_rabais'] = twodecimal($val['prix_unitaire_ht'] - $basket[$i]['prix_rabais']);

		$basket[$i]['prix_taxes'] = twodecimal(round_up(($basket[$i]['prix_unitaire_ht_rabais'] * $val['taxes'] / 1000), 2));

		$basket[$i]['prix_unitaire_ttc'] = twodecimal($val['prix_unitaire_ht'] + $basket[$i]['prix_taxes']);

		$basket[$i]['prix_unitaire_ttc_rabais'] = twodecimal($basket[$i]['prix_unitaire_ht_rabais'] + $basket[$i]['prix_taxes']);

		$basket[$i]['prix_rabais_for_all'] = twodecimal($basket[$i]['prix_rabais'] * $basket[$i]['quantite']);

		$basket[$i]['prix_unitaire_rabais_ht_for_all'] = twodecimal($basket[$i]['prix_unitaire_ht_rabais'] * $basket[$i]['quantite']);

		$basket[$i]['prix_taxes_for_all'] = twodecimal($basket[$i]['prix_taxes'] * $basket[$i]['quantite']);

		$basket[$i]['prix_unitaire_ttc_rabais_for_all'] = twodecimal($basket[$i]['prix_unitaire_ttc_rabais'] * $basket[$i]['quantite']);

		$i++;
	}

	if (isset($basket))
		return $basket;
	else
		return false;
}


function give_cancel($commandid)
{
	global $applicationlang;
	global $link;

	$sql = 'SELECT cancel.id_cancel,
									cancel.id_commande,
									cancel.id_produit,
									cancel.prix_unitaire as prix_unitaire_ht,
									cancel.discount,
									SUM(cancel.quantite) as quantite,
									cancel.taxes,
									produit.reference,
									produit.nom_' . $applicationlang . ' as nom,
									produit.disponible,
									produit.delai_reapprovisionnement,
									auteur.nom as auteur
									FROM cancel, produit, type, auteur
									WHERE cancel.id_commande = "' . $commandid . '"
									AND cancel.id_produit = produit.id_produit
									AND produit.id_auteur = auteur.id_auteur
									AND produit.id_type = type.id_type
									GROUP BY cancel.id_produit
									;';
	$res = mysqli_query($link,$sql);
	$i = 0;

	while ($val = mysqli_fetch_assoc($res))
	{
		$cancel[$i] = $val;

		$cancel[$i]['prix_rabais'] = twodecimal(round_up(($val['prix_unitaire_ht'] * $val['discount'] / 100), 2));


		$cancel[$i]['prix_unitaire_ht_rabais'] = twodecimal($val['prix_unitaire_ht'] - $cancel[$i]['prix_rabais']);

		$cancel[$i]['prix_taxes'] = twodecimal(round_up(($cancel[$i]['prix_unitaire_ht_rabais'] * $val['taxes'] / 1000), 2));

		$cancel[$i]['prix_unitaire_ttc'] = twodecimal($val['prix_unitaire_ht'] + $cancel[$i]['prix_taxes']);

		$cancel[$i]['prix_unitaire_ttc_rabais'] = twodecimal($cancel[$i]['prix_unitaire_ht_rabais'] + $cancel[$i]['prix_taxes']);

		$cancel[$i]['prix_rabais_for_all'] = twodecimal($cancel[$i]['prix_rabais'] * $cancel[$i]['quantite']);

		$cancel[$i]['prix_unitaire_rabais_ht_for_all'] = twodecimal($cancel[$i]['prix_unitaire_ht_rabais'] * $cancel[$i]['quantite']);

		$cancel[$i]['prix_taxes_for_all'] = twodecimal($cancel[$i]['prix_taxes'] * $cancel[$i]['quantite']);

		$cancel[$i]['prix_unitaire_ttc_rabais_for_all'] = twodecimal($cancel[$i]['prix_unitaire_ttc_rabais'] * $cancel[$i]['quantite']);

		$i++;
	}

	if (isset($cancel))
		return $cancel;
	else
		return false;
}


function give_id_commande($commande_code)
{
	global $link;
	$sql = 'SELECT id_commande
					FROM commande
					WHERE commande.code = "' . $commande_code . '"
					;';
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);

	if (isset($val['id_commande']))
		return $val['id_commande'];
}

function basket_summary($basket)
{
	global $link;
	$summary['total_delai'] = 0;
	$summary['total_quantite'] = 0;
	$summary['total_ht'] = 0;
	$summary['total_taxe'] = 0;
	$summary['total_ttc'] = 0;


	foreach ($basket as $key => $product)
	{
		$summary['total_quantite'] += $product['quantite'];
		$summary['total_ht'] += $product['prix_unitaire_rabais_ht_for_all'];
		$summary['total_taxe'] += $product['prix_taxes_for_all'];
		$summary['total_ttc'] += $product['prix_unitaire_ttc_rabais_for_all'];
		if($product['disponible'] != 1)
		{
			//delai maximum pour avoir les produits de la commande
			if ($summary['total_delai'] < $product['delai_reapprovisionnement'])
			{
				$summary['total_delai'] = $product['delai_reapprovisionnement'];
			}
		}
	}
	$summary['total_ht'] = twodecimal($summary['total_ht']);
	$summary['total_taxe'] = twodecimal($summary['total_taxe']);
	$summary['total_ttc'] = twodecimal($summary['total_ttc']);


//	echo '<pre>' . print_r($basket, 1) . '</pre>';
//	echo '<pre>' . print_r($summary, 1) . '</pre>';
//	echo '<pre>' . print_r($product, 1) . '</pre>';


	return $summary;
}



function order_content($id_order, $group = '')
{
	global $link;
	$sql = "SELECT panier.id_panier as panier_id_panier,
									panier.id_commande as panier_id_commande,
									panier.id_produit as panier_id_produit,
									panier.prix_unitaire as panier_prix_unitaire,
									sum(panier.quantite) as panier_quantite,
									produit.id_produit as produit_id_produit,
									produit.id_auteur as produit_id_auteur,
									produit.id_categorie as produit_id_categorie,
									produit.id_editeur as produit_id_editeur,
									produit.id_type as produit_id_type,
									produit.reference as produit_reference,
									produit.issn as produit_issn,
									produit.isbn as produit_isbn,
									produit.realname as produit_realname,
									produit.nom_fr as produit_nom_fr,
									produit.nom_de as produit_nom_de,
									produit.nom_en as produit_nom_en,
									produit.nom_es as produit_nom_es,
									produit.nom_it as produit_nom_it,
									produit.langues as produit_langues,
									produit.ind as produit_ind,
									produit.nb_pages as produit_nb_pages,
									produit.nb_termes as produit_nb_termes,
									produit.info_divers_fr as produit_info_divers_fr,
									produit.info_divers_de as produit_info_divers_de,
									produit.info_divers_en as produit_info_divers_en,
									produit.info_divers_es as produit_info_divers_es,
									produit.description_fr as produit_description_fr,
									produit.description_de as produit_description_de,
									produit.description_en as produit_description_en,
									produit.description_es as produit_description_es,
									produit.description_it as produit_description_it,
									produit.prix as produit_prix,
									produit.prix_editeur as produit_prix_editeur,
									produit.rabais as produit_rabais,
									produit.delai_reapprovisionnement as produit_delai_reapprovisionnement,
									produit.date_parution as produit_date_parution,
									produit.date_insertion as produit_date_insertion,
									produit.poid as produit_poid,
									produit.nb_dispo as produit_nb_dispo,
									produit.disponible as produit_disponible,
									produit.url as produit_url,
									produit.libraire as produit_libraire,
									produit.image as produit_image,
									produit.rank as produit_rank,
									produit.score as produit_score,
									produit.sommeil as produit_sommeil,
									produit.affaire as produit_affaire,
									produit.remise_lvl as produit_remise_lvl,
									produit.date_modif as produit_date_modif,
									produit.modif_desc as produit_modif_desc,
									produit.modif_info as produit_modif_info,
									produit.modif_name as produit_modif_name,
									produit.monthbook as produit_monthbook,
									produit.meta_fr as produit_meta_fr,
									produit.meta_es as produit_meta_es,
									produit.meta_de as produit_meta_de,
									produit.meta_it as produit_meta_it,
									produit.meta_en as produit_meta_en,
									produit.thematic_fr as produit_thematic_fr,
									produit.thematic_es as produit_thematic_es,
									produit.thematic_de as produit_thematic_de,
									produit.thematic_it as produit_thematic_it,
									produit.thematic_en as produit_thematic_en,
									`type`.id_type,
									`type`.nom_fr,
									`type`.nom_de,
									`type`.nom_en,
									`type`.nom_es,
									`type`.nom_it,
									`type`.tva
					FROM panier,
								produit,
								type
					WHERE panier.id_commande = '" . $id_order . "'
						AND panier.id_produit = produit.id_produit
						AND produit.id_type = type.id_type
					GROUP BY panier.id_produit
					;";
	$res = mysqli_query($link,$sql) or die(mysqli_error($link));

	if (isset($res))
	{
		$i = 0;
		//fetch all article
		while($val =  mysqli_fetch_assoc($res))
		{
			$order_content[$i] = $val;
			$i++;
		}
	}

	return $order_content;
}

function order_address($commandid, $applicationlang)
{
	global $link;
		$sql = "SELECT 
							membre.id_membre as membre_id_membre,
							membre.login as membre_login,
							membre.email as membre_email,
							membre.societe as membre_societe,
							membre.nomsociete as membre_nomsociete,
							membre.intracommu as membre_intracommu,
							membre.civilite as membre_civilite,
							membre.nom as membre_nom,
							membre.prenom as membre_prenom,
							membre.adresse1 as membre_adresse1,
							membre.adresse2 as membre_adresse2,
							membre.adresse3 as membre_adresse3,
							membre.ville as membre_ville,
							membre.etat as membre_etat,
							membre.cp as membre_cp,
							membre.pays as membre_pays,
							membre.indicatif_tel as membre_indicatif_tel,
							membre.tel as membre_tel,
							membre.indicatif_fax as membre_indicatif_fax,
							membre.fax as membre_fax,
							livraison.id_livraison as livraison_id_livraison,
							livraison.id_frais_port as livraison_id_frais_port,
							livraison.civilite as livraison_civilite,
							livraison.nom as livraison_nom,
							livraison.prenom as livraison_prenom,
							livraison.adresse1 as livraison_adresse1,
							livraison.adresse2 as livraison_adresse2,
							livraison.adresse3 as livraison_adresse3,
							livraison.cp as livraison_cp,
							livraison.ville as livraison_ville,
							livraison.etat as livraison_etat,
							livraison.pays as livraison_pays,
							facturation.id_facturation as facturation_id_facturation,
							facturation.civilite as facturation_civilite,
							facturation.nom as facturation_nom,
							facturation.prenom as facturation_prenom,
							facturation.adresse1 as facturation_adresse1,
							facturation.adresse2 as facturation_adresse2,
							facturation.adresse3 as facturation_adresse3,
							facturation.cp as facturation_cp,
							facturation.ville as facturation_ville,
							facturation.etat as facturation_etat,
							facturation.pays as facturation_pays
						FROM
								commande,
								livraison,
								facturation,
								utilisateur,
								membre
						WHERE commande.id_commande = '" . $commandid . "'
						AND commande.id_livraison = livraison.id_livraison
						AND commande.id_facturation = facturation.id_facturation
						AND commande.id_utilisateur = utilisateur.id_utilisateur
						AND utilisateur.id_membre = membre.id_membre;";
	$res = mysqli_query($link,$sql);
	$address = mysqli_fetch_assoc($res);
	$address['membre_pays_name'] = abrev_to_country_name($address['membre_pays'], $applicationlang);
	$address['livraison_pays_name'] = abrev_to_country_name($address['livraison_pays'], $applicationlang);
	$address['facturation_pays_name'] = abrev_to_country_name($address['facturation_pays'], $applicationlang);

//echo '<pre>' . print_r($address, 1) . '</pre>';

	return $address;
}

function twodecimal($number)
{
	return sprintf("%01.2f", $number);
}

function round_up ($value, $places=0)
{
  if ($places < 0)
	{
		$places = 0;
	}

  $mult = pow(10, $places);

	return ceil($value * $mult) / $mult;
}

function abrev_to_country_name($abrev, $applicationlang)
{
	global $link;
  $sql = 'SELECT nom_' . $applicationlang . ' as pays
					FROM pays
					WHERE abrev = "' . $abrev . '"
					;';
  $res = mysqli_query($link,$sql);
  $name = mysqli_fetch_array($res);

	return $name['pays'];
}

function europe_member($abrev)
{
	global $link;
	$sql = 'SELECT europe
					FROM pays, membre
					WHERE pays.abrev = "' . $abrev . '"
					;';

	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);

	if (isset($val))
		return $val['europe'];
}

function answer($lang = 'fr')
{
	global $link;
	$sql = 'SELECT id_answer, title, subject_' . $lang . ', content_' . $lang . '
					FROM answer
					;';
	$res = mysqli_query($link,$sql);
	
	while ($val = mysqli_fetch_assoc($res))
	{
		$answer[] = $val;
	}

	if (isset($answer))
	{
		return $answer;
	}
}


function copy_to_cancel($id_basket)
{
	global $link;
	$sql = 'SELECT *
					FROM panier
					WHERE id_panier = "' . $id_basket . '"
					LIMIT 1
					;';

	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);

	$sql = 'INSERT INTO `cancel`
						(
							`id_commande`,
							`id_produit`,
							`prix_unitaire`,
							`discount`,
							`quantite`,
							`taxes`
						)
					VALUES
						(
							"' . $val['id_commande'] . '",
							"' . $val['id_produit'] . '",
							"' . $val['prix_unitaire'] . '",
							"' . $val['discount'] . '",
							"' . $val['quantite'] . '",
							"' . $val['taxes'] . '"
						)
					;';

	mysqli_query($link,$sql);
}


function delete_from_order($id_basket = '')
{
	global $link;

	//supprime l'article de la table panier
	$sql = 'DELETE FROM `panier`
					WHERE `id_panier` = "' . addslashes($id_basket) . '"
					LIMIT 1;';
	mysqli_query($link,$sql);
}

function give_answer($id_answer, $lang = 'fr')
{
	global $link;
	$sql = 'SELECT subject_' . $lang . ' as subject,
						content_' . $lang . ' as content
					FROM answer
					WHERE id_answer = "' . $id_answer . '"
					;';
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);

	if (isset($val))
	{
		return $val;
	}
}

function add_correspondence($id_order, $sender, $recipient, $subject, $content)
{
	global $link;
	$sql = 'INSERT INTO `correspondence`
						(
						`id_order`,
						`sender`,
						`recipient`,
						`subject`,
						`content`
						)
					VALUES
						(
							"' . addslashes($id_order) . '",
							"' . addslashes($sender) . '",
							"' . addslashes($recipient) . '",
							"' . addslashes($subject) . '",
							"' . addslashes($content) . '"
						);';
	mysqli_query($link,$sql);
}

function all_correspondence($id_order)
{
	global $link;
	$sql = 'SELECT *
					FROM correspondence
					WHERE id_order = "' .  $id_order . '"
					ORDER BY date_corres DESC
					;';
	$res = mysqli_query($link,$sql);
	
	while ($val = mysqli_fetch_assoc($res))
	{
		$message[] = $val;
	}

	if (isset($message))
	{
		return $message;
	}
}

function give_mail($id_mail)
{
	global $link;
	$sql = 'SELECT * FROM correspondence WHERE id_correspondence = "' . $id_mail . '";';
	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);
	if (isset($val))
		return $val;
}

function statut2txt($num)
{
	switch ($num)
	{
		case '1':
			$txt = 'En attente de paiement';
			break;
		case '2':
			$txt = 'Commande refus�e';
			break;
		case '3':
			$txt = 'Commande r�gl�e';
			break;
		case '4':
			$txt = 'Commande acc�pt�e';
			break;
		case '5':
			$txt = 'Commande en cours de traitement';
			break;
		case '6':
			$txt = 'Commande exp�di�e';
			break;
		case '8':
			$txt = 'Commande masqu�e';
			break;
		case '9':
			$txt = 'Commande annul�e';
			break;
	}
	
	return $txt;
}

function cp_name($codename)
{
	$name = '';
  switch ($codename)
  {
    case "courrier_ordinaire":
      $name = 'Courrier ordinaire';
      break;
    case "colissimo_fr":
      $name = 'Colissimo France';
      break;
    case "colispostal_prio_a":
      $name = 'Colispostal Prioritaire Zone A';
      break;
    case "colispostal_prio_b":
      $name = 'Colispostal Prioritaire Zone B';
      break;
    case "colispostal_prio_c":
      $name = 'Colispostal Prioritaire Zone C';
      break;
    case "colispostal_prio_d":
      $name = 'Colispostal Prioritaire Zone D';
      break;
    case "colispostal_eco_a":
      $name = 'Colispostal Economique Zone A';
      break;
    case "colispostal_eco_b":
      $name = 'Colispostal Economique Zone B';
      break;
    case "colispostal_eco_c":
      $name = 'Colispostal Economique Zone C';
      break;
    case "colispostal_eco_d":
      $name = 'Colispostal Economique Zone D';
      break;
    case "colissimo_int":
      $name = 'Colissimo international';
      break;
    case "colissimo_dom":
      $name = 'Colissimo Dom';
      break;
  }
  return $name;
}

function recredit($id_basket, $id_order)
{
	global $link;
	//recupere la somme deja "arendre" et ajoute le montant
	$sql = 'SELECT *
					FROM panier
					WHERE id_panier = "' . $id_basket . '"
					LIMIT 1
					;';

	$res = mysqli_query($link,$sql);
	$val = mysqli_fetch_assoc($res);

	/*
		$sql = 'INSERT INTO `cancel`
							(
								`id_commande`,
								`id_produit`,
								`prix_unitaire`,
								`discount`,
								`quantite`,
								`taxes`
							)
						VALUES
							(
								"' . $val['id_commande'] . '",
								"' . $val['id_produit'] . '",
								"' . $val['prix_unitaire'] . '",
								"' . $val['discount'] . '",
								"' . $val['quantite'] . '",
								"' . $val['taxes'] . '"
							)
						;';
	
		mysqli_query($link,$sql);
	*/

}

function Is_UsDate($dateUS)
{
	if (!trim($dateUS)) { return 0;}
	$ReturnVar = 1;
	if (strtotime($dateUS) == -1)
	{
		$ReturnVar = 0;
	}
	return $ReturnVar;
}

function Is_ValidePrixPort($prix_port)
{
	$ReturnVar = 0;
	if (!trim($prix_port))
	{
		return $ReturnVar;
	}
	if (preg_match('/^[0-9]{1,3}(\.[0-9]{1,2})?$/', $prix_port))
	{
		$ReturnVar = 1;
	}
	return $ReturnVar;
}

function frais_port_is_in_use($id_frais_port)
{
	global $link;
  $sql = "SELECT frais_port.id_frais_port
          FROM `frais_port`, `livraison`
          WHERE frais_port.id_frais_port='" . $id_frais_port . "'
          AND frais_port.id_frais_port=livraison.id_frais_port
          ;";
  $res = mysqli_query($link,$sql);
  $count = mysqli_num_rows($res);
  //echo $sql;
  mysqli_free_result($res);

  if ($count > 0)
    return true;
  else
    return false;
}

function frais_port_mode_exists($nom_mode_port,$PAYS)
{
	global $link;
  $sql = "SELECT frais_port.mode, frais_port.pays
          FROM `frais_port`
          WHERE frais_port.mode='" . $nom_mode_port . "'
          AND frais_port.pays='" . $PAYS . "'
          ;";
  $res = mysqli_query($link,$sql);
  $count = mysqli_num_rows($res);
  mysqli_free_result($res);
  
  if ($count > 0)
    return true;
  else
    return false;
}

function get_frais_port_infos($id_frais_port)
{
	global $link;
	$sql = 'SELECT *
					FROM `frais_port`
					WHERE id_frais_port = "' .  $id_frais_port . '"
					LIMIT 1
					;';
	$res = mysqli_query($link,$sql);
	
	while ($val = mysqli_fetch_assoc($res))
	{
		$infos_frais_port[] = $val;
	}

	if (isset($infos_frais_port))
	{
		return $infos_frais_port;
	}
}

function get_frais_port_infos_for_email($id_frais_port)
{
	global $link;
	$contenu = "";
	$infos = get_frais_port_infos($id_frais_port);
	foreach ($infos[0] as $key => $value)
	{
		$contenu.= "Colonne : $key; Valeur : $value\r\n";
	}
	return $contenu;
}

// Fonction de transformation de la date US en FR
function FormateDateFR($DateUStoFormat)
{
	$separateur = "/";
	$annee = substr($DateUStoFormat,0,4);
	$mois  = substr($DateUStoFormat,5,2);
	$jour  = substr($DateUStoFormat,8,2);
	$heure = substr($DateUStoFormat,11);
	
	return($jour.$separateur.$mois.$separateur.$annee." - ".$heure);
}

function FormateDateOnlyFR($DateUStoFormat)
{
	$separateur = "/";
	$annee = substr($DateUStoFormat,0,4);
	$mois  = substr($DateUStoFormat,5,2);
	$jour  = substr($DateUStoFormat,8,2);
	$heure = substr($DateUStoFormat,11);
	
	return $jour.$separateur.$mois.$separateur.$annee;
}

// Fonction de transformation de la date US en FR et test si format d'entree contient aussi l'heure ou pas
function FormateDateTimeFR($DateUStoFormat,$separateur)
{
	$annee = substr($DateUStoFormat,0,4);
	$mois  = substr($DateUStoFormat,5,2);
	$jour  = substr($DateUStoFormat,8,2);
	$heure = substr($DateUStoFormat,11);
	
	if ($heure)
	{
		return($jour.$separateur.$mois.$separateur.$annee." � ".$heure);
	}
	else
	{
		return($jour.$separateur.$mois.$separateur.$annee);	
	}
}

function isValideUSdate($DateUS)
{
	$returnVar = 0;
	if ( preg_match( "/(^[0-9]{4})-([0-1]{1}[0-9]{1})-([0-3]{1}[0-9]{1}$)/", $DateUS) )
	{
		$returnVar = 1;
	}
	return $returnVar;
}

/**
 * Fonction v�rifiant la validit� d'un email
 *
 * @param string $emailToVerify email � v�rifier
 *
 * @return int $returnVar 0 si pas un mail valide et 1 sinon
 */
function EmailIsValide($emailToVerify)
{
	$returnVar = 0;
	if (preg_match("/^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$/i", $emailToVerify)) 
	{
		$returnVar = 1;
	}
	return $returnVar;
}

function showNbrDestinatairesEmailing($ID_EMAILING,$FILTRE)
{
	global $link;
 	$query = "SELECT * FROM emailings_destinataires WHERE ID_EMAILING=".$ID_EMAILING;
 	if(isset($FILTRE) && $FILTRE == "envoyes")
 	{
 		$query.= " AND DATETIME_ENVOI<>'0000-00-00 00:00:00'";
 	}
 	if(isset($FILTRE) && $FILTRE == "attente")
 	{
 		$query.= " AND DATETIME_ENVOI='0000-00-00 00:00:00'";
 	}
 	$result = mysqli_query($link,$query);
 	$nbr = mysqli_num_rows($result);
 	return $nbr;
 	
}

function EmailDejaImportee($email,$ID_EMAILING)
{
	global $link;
	$ReturnVar = 0;
	$query = "SELECT ID_EMAILING,EMAIL_DESTINATAIRE FROM emailings_destinataires WHERE ID_EMAILING='".$ID_EMAILING."' AND EMAIL_DESTINATAIRE='".$email."' LIMIT 1";
  $result = mysqli_query($link,$query);
  $num = mysqli_num_rows($result);
  if ($num)
  {
  	$ReturnVar = 1;
  }
  return $ReturnVar;
}

function EmailIsBlackListee($email)
{
	global $link;
	$ReturnVar = 0;
	$query = "SELECT EMAIL FROM emailings_blacklist WHERE EMAIL='".$email."' LIMIT 1";
  $result = mysqli_query($link,$query);
  $num = mysqli_num_rows($result);
  if ($num)
  {
  	$ReturnVar = 1;
  }
  return $ReturnVar;
}

function EmailDejaEnvoye($ID_DESTINATAIRE)
{
	global $link;
	$ReturnVar = 0;
	$query = "SELECT ID_EMAILING,DATETIME_ENVOI FROM emailings_destinataires WHERE ID='".$ID_DESTINATAIRE."' AND DATETIME_ENVOI<>'0000-00-00 00:00:00' LIMIT 1";
  $result = mysqli_query($link,$query);
  $num = mysqli_num_rows($result);
  if ($num)
  {
  	$ReturnVar = 1;
  }
  return $ReturnVar;
}

/**
 * Fonction r�cup�rant la liste des fichiers contenus dans le dossier des pieces jointes d'emailings
 * les pla�ant dans un tableau o� ils sont tri�s par ordre alphab�tique.
 * Ne prend que les fichier ayant comme format de nom ("[ID_EMAILING]_[nomFichierSansEspaces]_.[extension]")
 * 
 * @return array $array_file tableau contenant les fichiers class�s par ordre alphab�tique.
 */
function getArrayEmailingsPJFile($ID_EMAILING,$PATH_PJ)
{
	$array_file = array();
	// si le dossier cens� contenir les pieces jointes n'existe pas, on renvoie NULL.
	if (!file_exists("".$PATH_PJ.""))
	{
	  return NULL;
	}
	// si le dossier existe, on l'ouvre pour y rechercher les fichiers ayant le bon format.
	$handler = opendir("".$PATH_PJ."");
	// tant qu'on lit des fichiers dedans
	while ($file = readdir($handler))
	{		
		if ( (filetype("".$PATH_PJ."/".$file."") == "file") && (preg_match("/^".$ID_EMAILING."_+([a-zA-Z0-9_])+\.([a-zA-Z]{3})$/",$file)) )
		{
			// si format correct on ajoute le fichier dans le tableau.
			array_push($array_file, $file);
		}
	}
	closedir($handler);
	// on trie le tableau
	sort($array_file);
	return $array_file;
}

function NettoyerChaineParUnderscores($chaine)
{
	if (!isset($chaine) || !trim($chaine))
	{
		return "";
	}
	$chaine = strtolower(trim($chaine));
	$nouvelle_chaine = "";
	$num_caractere = 0;
	// On boucle sur toutes les lettres de la chaine pour verifier son contenu
	while($num_caractere < strlen($chaine))
	{
		$caractere_actuel = $chaine{$num_caractere};
		if(!preg_match("/^([a-z0-9_{1}])$/", $caractere_actuel))
		{
			$nouvelle_chaine.= "_";
		}
		else
		{
			$nouvelle_chaine.= $caractere_actuel;
		}		
		$num_caractere++;
	}
	return $nouvelle_chaine;
}

/**
 * Fonction r�cup�rant une date au format FR � partir :
 *  - une date de format mysql (YYYY-MM-DD)
 *  - un datetime mysql (YYYY-MM-DD HH:MM:SS)
 *
 * @param string $date date ou datetime au format mysql
 *
 * @return string $date_fr date au format fr (exemple : 01 octobre 2004)
 */
function getDateFR($date) {
	$annee = substr("$date", 0, 4);
	$date_fr = getDateWithoutYearFR($date)." $annee";
	return $date_fr;
}

/**
 * Fonction r�cup�rant une date au format FR � partir :
 *  - une date de format mysql (YYYY-MM-DD)
 *  - un datetime mysql (YYYY-MM-DD HH:MM:SS)
 *
 * @param string $date date ou datetime au format mysql
 *
 * @return string $date_fr date au format fr (exemple : 01 octobre)
 */
function getDateWithoutYearFR($date) {
	$mois = substr("$date", 5, 2);
	$jour = substr("$date", 8, 2);
	switch ($mois)
	{
		case "01" : $mois = "Janvier"; break;
		case "02" : $mois = "F�vrier"; break;
		case "03" : $mois = "Mars"; break;
		case "04" : $mois = "Avril"; break;
		case "05" : $mois = "Mai"; break;
		case "06" : $mois = "Juin"; break;
		case "07" : $mois = "Juillet"; break;
		case "08" : $mois = "Ao�t"; break;
		case "09" : $mois = "Septembre"; break;
		case "10" : $mois = "Octobre"; break;
		case "11" : $mois = "Novembre"; break;
		case "12" : $mois = "D�cembre"; break;
	}
	$date_fr = "$jour"." "."$mois";
	return $date_fr;
}

function getStatut($val) {
    if ($val == 1)
        $toWrite = "<FONT COLOR='green'><B>ON-LINE</B></FONT>";
    else
        $toWrite = "<FONT COLOR='red'><B>OFF-LINE</B></FONT>";

    return $toWrite;
}

function selectStatus($theName,$selection) {
	echo "<SELECT NAME=\"".$theName."\" CLASS=\"case_admin\">";
	echo "<OPTION VALUE=\"1\"";
		if ($selection == 1) echo" selected";
		echo ">ON-LINE</OPTION>";
	echo "<OPTION VALUE=\"0\"";
		if ($selection == 0) echo" selected";
		echo ">OFF-LINE</OPTION>";
	echo "</SELECT>";
}

/**
 * Fonction supprimant un fichier si il existe
 * 
 * @param string $path chemin du fichier
 *
 * @return boolean $isDelete true si le fichier n'existe plus et false sinon
 */
function deleteFile($path) {
	if (file_exists($path))
		if (!unlink($path))
			return false;
	return true;	
}

function getTotalEmailNumberOfEmailing($ID_EMAILING)
{
	global $link;
	$query = 	"SELECT ID_EMAILING FROM emailings_destinataires WHERE ID_EMAILING=".$ID_EMAILING;
  $result = mysqli_query($link,$query);
  $num_destinataires = mysqli_num_rows($result);
  return $num_destinataires;
} 
?>
