<?php
/************************************************************************
 * Programme écrit par GR le 4 novembre 2020 et terminé le 19 novembre	*
 * pour									*
 * 	- gérér les tables qui stockent le moteur de calcul 		*
 * 	des frais de port						*
 * 	- gérer les différents mode de calcul				*
 *	- j'ajoute la partie css le 20 novembre 									*
 * 									*
 ************************************************************************/
header('content-type: text/html; charset=UTF-8');
// on va commencer par charger les tables pour la France
// donc selectionner les enregistrements de frais_port_new qui vérifient pays="fr"
//on affiche le pays par son libellé long / on doit avoir une fonction pour ça oui c'est abrev_to_country_name($abrev, $applicationlang)
	$sql= "select abrev, nom_fr  from pays order by nom_fr;";
	$respays = mysqli_query($link,$sql);
//on initialise le pays en fonction des appels précédents avec par défaut FR
	$pays =(isset($_GET['pays']) ? $_GET['pays'] : ((isset($_POST['pays']) ? $_POST['pays'] : 'FR'))); 
?>
<!--                       pavé entête de la page maitresse                                        --!>
<header id="FP">
	 <h2> GESTION DES FRAIX DE PORT </h2>
	<div id="FP_aide">
<!--	<img src="/home/gerard/fontawesome-free-5.5.15.1-web/svgs/solid/brain.svg" />
	<svg xmnls="/home/gerard/fontawesome-free-5.5.15.1-web/svgs/solid/brain.svg" role="img"><title>Test affichage svg</title></svg>--!>
	<p> Le Principe est pour un pays de créer/modifier/supprimer des régles: <br>limite de poids/prix en fonction d'un mode de transport et d'un tarif applicable </p>
	</div>
</header>  

<!--                       fin du pavé entête de la page maitresse                                        --!>

<!--                       pavé formulaire pays                                        --!>
<nav>
<form  id= "FP_form_pays" method="post" action="index2.php?page=new_frais_port"> <!--debut du formulaire pour choisir le pays --!> 
	<p class="FP_pays">

		<label for= "Pays">Renseignez le pays pour lequel vous voulez gérer les frais de port </label><br />
		<select name="pays" id = "pays" required>
<?php	while ($donnees=mysqli_fetch_array($respays))//on propose à l'affichage tous les pays pour sélection
	{	
	?>
			<option value ="<?php echo $donnees['abrev'];?>" <?php  echo (($donnees['abrev'] == $pays) ? "SELECTED" : ""); ?> >
			<?php echo $donnees['abrev'] . "-" . $donnees['nom_fr'] ;?>
			</option>
	<?php } ?>

		</select>
	</p>
	<input type="submit" value="Confirmer choix pays"> 
</form> 
</nav><!--fin du formulaire pour le pays --!>
<?php
//on initialise - en dur - les modes de calcul
		$mode_calcul = array(array("nom" =>"standard","texte"=>"c'est le cas standard"),array("nom" =>"promo éditeur","texte" => "les tarifs applicables pour les paniers contenant des livres maisons"));
// on boucle sur chaque mode de calcul
// on  affiche le mode de calcul en entête
foreach ($mode_calcul as $mod_calc => $lib_mod_calc)
{
?>
<section id="FP_mode_calcul">
	<p> Pour la règle de calcul: <?php echo $lib_mod_calc["nom"]; ?><br> <?php echo $lib_mod_calc["texte"];?></p>
<?php 
	// on va afficher pour chacun des mode_calcul l'ensemble des couples poids_tarif/prix stockés dans la base pour le pays,mode_calcul
	$sql = "SELECT * from frais_port_new WHERE pays = '" . $pays . "' AND mode_calcul=" . $mod_calc . " ORDER BY pays,mode_calcul,mode,poids_tarif;";
	$res = mysqli_query($link,$sql);
	if ($res <> false) { //il y a des enregistrements pour ce pays et ce mode_calcul
	//on initialise la variable  $mode et un id lui correspondant
		$prec_mode = ""; $prec_id = 0;
	//on boucle sur les enregistrements sélectionnés
	while ($regle = mysqli_fetch_array($res)) 
	{	?>
		
<?php
		// on imprime le premier enregistrement du mode de frais de port si c'est le premier ou s'il a changé. On l'aura initialiésé à null	
		if ($regle['mode'] != $prec_mode)
			{
			if($prec_mode !="") 
				{ //on ajoute le bouton pour ajouter un couple
?>
<article id="FP_mode"
			<div class="FP_edit_mode">
				<br><a href ='index2.php?page=new_frais_port&sous_page=modif&type=add&pays=<?php echo $pays; ?>&id=<?php echo $prec_id; ?>'>
				Ajouter une règle ou les modifier ? <img src="./css/picto_dupliquer.gif" alt="cliquer pour ajouter un couple"
				 title="cliquer pour ajouter un couple"></a>
				<br><a href ='index2.php?page=new_frais_port&sous_page=modif&type=dup&pays=<?php echo $pays; ?>
				&mode_calc=<?php echo $mod_calc;?>&mode=<?php echo $prec_mode; ?>'>
				Dupliquer(et/ou ajouter) une règle pour un nouveau pays et/ou mode de livraison ? <img src="./css/picto_dupliquer.gif" 
				alt="cliquer pour dupliquer ces rèles et/ou les compléter" title="cliquer pour dupliquer ces rèles et/ou les compléter"></a>
<?php 				} ?>
			</div> <!-- fin d'un bloc editition mode  FP_edit_mode--!>
				<br><br><p id='FP_mode'> <strong><?php echo $regle['mode'];?> </strong></p>
<?php	 		}?>
	
		<!--on écrit maintenant pour chaque poids son prix plus les icones de modification--!>
		<p id="FP_regles">
		<span id='FP_poids'> >  <?php echo $regle['poids_tarif'];?> </span>
		<span id='FP_prix'>Prix: <?php echo $regle['prix'] ; ?> </span>
		<a href ='index2.php?page=new_frais_port&pays=<?php echo $pays; ?>&sous_page=modif&mode_calcul=<?php echo $mod_calc; ?>
		&id=<?php echo $regle['id_frais_port']; ?>'><img src="./css/picto_edit.gif" alt="Modifier ces valeurs" title="Modifier ces valeurs"></a>
		<a href ='index2.php?page=new_frais_port&sous_page=sup&pays=<?php echo $pays; ?>&id=<?php echo $regle['id_frais_port']; ?>'>
		<img src="./css/picto_poubelle.gif" alt="Supprimer ces valeurs" title="Supprimer ces valeurs"></a>
	
		</p>
<?php
	$prec_mode = $regle['mode']; $prec_id = $regle['id_frais_port'];

	} //fin de la boucle sur l'enregistrement*/	
	//on ajoute le bouton pour ajouter un couple pour le dernier mode du mode de calcul
?>

	<div class="FP_edit_mode">

	<br><a href ='index2.php?page=new_frais_port&sous_page=modif&type=add&pays=<?php echo $pays; ?>&id=<?php echo $prec_id; ?>'>
	Ajouter une règle ou les modifier ? <img src="./css/picto_dupliquer.gif" alt="cliquer pour ajouter un couple" title="cliquer pour ajouter un couple"></a>

<?php
} //fin du test sur existence d'enregistrements?>
	<br><a href ='index2.php?page=new_frais_port&sous_page=modif&type=dup&pays=<?php echo $pays; ?>
	&mode_calc=<?php echo $mod_calc;?>&mode=<?php echo $prec_mode; ?>'>
	Dupliquer(et/ou ajouter) une règle pour un nouveau pays et/ou mode de livraison ? <img src="./css/picto_dupliquer.gif" 
	alt="cliquer pour dupliquer ces rèles et/ou les compléter" title="cliquer pour dupliquer ces rèles et/ou les compléter"></a>

	</div> <!-- fin d'un bloc editition mode  FP_edit_mode--!>
</article> <!-- fin du bloc sur le mode de livraison FP_mode --!>
</section> <!-- fin du bloc mode de calcul FP_mode_calcul --!>
<?php 
} //fin de la boucle sur le mode de calcul*/	
/****************************************************************************************************************
 *				fin de la période d'affichage pour un pays 					*
 ****************************************************************************************************************
 *				Début de la prise en charge des modifications					*
 ****************************************************************************************************************/				

switch((isset($_GET['sous_page']) ? $_GET['sous_page'] : '') )
{
	case "sup"://il s'agit de supprimer l'enregistrement souhaité identifié par son id
//je demande la confirmation
?>
<section id=FP_edit">
		<form action="include/sup_regle_frais_port.inc.php" method="post" id="sup_regle">
		<p>
			<input type="hidden" name="id" value=<?php echo $_GET['id']; ?>>
			<input type="hidden" name="pays" value=<?php echo $pays; ?>>
			<input type="submit" name="Valider" value="Confirmez vous la suppression de cette règle?"
		</p>
		</form>
<?php
	break;// fin du cas sup

	case "modif": //on crée le cadre pour accueillir les modifications

		//Si modif d'une règle existante j'ai récupéré l'id existant qui me donne accès à tout
		if ((isset($_GET['type']) ? $_GET['type'] : "") <> "dup") 
		{ 
		//je récupére les données du frais de port à modifier
		$sql= 'SELECT * from frais_port_new WHERE id_frais_port =' . $_GET['id'] .';';
		$res = mysqli_query($link,$sql);
		$row = mysqli_fetch_array($res); 
		}	
?>
		<!-- dans tous les cas je prépare le cadre table(vraiment utile?) et le formulaire pour saisir les données --!>
	<article id= FP_modif">
		<form id="FP_form_modif" action="include/edit_regle_frais_port.inc.php" method="post">
		<table id="FP_tab_modif">
		 <caption class="FP_pays"> 
		<!-- affichage du pays qui est différent dans le cas de la duplication de règles --!>

<?php 		if ((isset($_GET['type']) ? $_GET['type'] : "") <> "dup") //cas standard sans duplication
		{  ?>

			<h3><label for "pays">Pays: </label>
			<input type="text" name="pays" id="pays" value="<?php echo $pays; ?>" readonly></h3>
<?php 		}

		else // on affiche le pays d'importation qui pourra/devra être remplacé par le pays d'exportation
		{ mysqli_data_seek($respays,0) ;?>
			<label for= "Pays">Choisissez le pays vers lequel vous voulez exporter ces règles depuis ce pays: </label><br />
			<select name="pays" id = "pays" required>

<?php			while ($donnees=mysqli_fetch_array($respays))//on propose à l'affichage tous les pays pour sélection
			{ ?>
			<option value ="<?php echo $donnees['abrev'];?>" <?php  echo (($donnees['abrev'] == $pays) ? "SELECTED" : ""); ?> >
			<?php echo $donnees['abrev'] . "-" . $donnees['nom_fr'] ;?>
			</option>
<?php 			} ?>

			</select>
		</caption> <!-- fin de class: FP_pays --!>

	<?php } // fin du champ pour pays export ?>

		<thead>
		<tr>
		<th scope="col" colspan="2"><label for "mode_calcul">Régle de tarification: </label>
		<input type="text" name="mode_calcul" value="<?php echo (($_GET['type'] == "dup") ? $_GET['mode_calc'] :$row['mode_calcul']); ?>"></th>
		</tr>		
		<tr>
		<th scope="col" colspan="2"><label for "mode">Mode de livraison: </label>
		<input type="text" name="mode" id="mode" value="<?php echo (((isset($_GET['type']) ? $_GET['type'] : '') == "dup") ? 
		(isset($_GET['mode']) ? $_GET['mode'] : '') : $row['mode']); ?>" 
		<?php echo ((isset($_GET['type']) ? $_GET['type'] : '') == "" ? "readonly" : "required"); ?>  >
		</th>
		</tr>
		<tr>
			<th align"center"><strong>Limite poids</strong></th>
			<th align="center"><strong>Tarif</strong></th>
		
		</tr>
		</thead> <!-- fin de l'en-tête du tableau --!>

		<tbody>  <!-- début du corps du tableau ou on affiche les couples de régles renseilgnées ou pas --!>

<?php		if (((isset($_GET['type']) ? $_GET['type'] : '') == "add") ||((isset($_GET['type']) ? $_GET['type'] : '') == "dup"))	
		{// on affiche les zones pour ajouter un couple
?>	
		<tr>	
			<td align="center">
			<input type="text" name="poids_tarif[]" id="poids" value="" autofocus>
			</td>
			<td align="center">
			<input type="text" name="prix[]" id="prix" value="" >
			</td>
		</tr>
		<input type="hidden" name="id[]" value=""> 
<?php 		}// fin du cas particulier pour l'ajout d'un couple

		//il s'agit de modifier les enregistrements existants en mettant le curseur sur l'id d'appel
		//je récupère tous les enregistrements du mode donc 

		$sql= "select * from frais_port_new where pays='" . $pays. "' and mode_calcul=" . (((isset($_GET['type']) ? $_GET['type'] : '') == "dup") ? $_GET['mode_calc'] :$row['mode_calcul']) . " and mode='" .(((isset($_GET['type']) ? $_GET['type'] : '') == "dup") ? $_GET['mode'] : $row['mode']) . "' ORDER BY pays,mode_calcul,mode,poids_tarif;";
		$restot = mysqli_query($link,$sql);
 

//je boucle sur les enregistrements

		while($rowtot = mysqli_fetch_array($restot))
		{
?>
		<tr>	
			<td align="center">
			<input type="text" name="poids_tarif[]" id="poids" value=<?php echo $rowtot['poids_tarif'];?>>
			</td>
			<td align="center">
			<input type="text" name="prix[]" id="prix" value=<?php echo $rowtot['prix'];?> 
			<?php echo ($rowtot['id_frais_port'] == (isset($_GET['id']) ? $_GET['id'] : '') ? "autofocus" : "");?>>
			</td>
		</tr>
			<input type="hidden" name="id[]" value=<?php echo (((isset($_GET['type']) ? $_GET['type'] : '') == "dup") ? "" : $rowtot['id_frais_port']); ?>>
	
	
<?php  } //fin de la boucle sur les enregistrements
?>
		</tbody>  <!-- fin du corps du tableau --!>
		<tr>
			<td colspan="2" align="center"> 
			<input type="submit" name="Valider" value="Valider">
			</td> 
		</tr>
		</table> <!-- fin de FP_table_modif --!>
		</form> <!-- fin de FP_form_modif --!>
	</article> <!-- fin de l'article FP_modif la partie ou on affiche formulaire-tableau pour l'édition d'un mode --!>	
</section> <!-- fin de la section FP_edit--!>
<?php	break; //fin du case modif

} // fin du switch
?>
		


