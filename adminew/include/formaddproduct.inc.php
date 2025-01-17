<?php
if (isset($_GET["modifier"]) && $_GET["modifier"] == 1)
{
  $sql_modif = "SELECT *
                FROM produit
                WHERE id_produit = '" . $_GET["id_produit"] . "'
               ;";

  $sql_modif_result = mysqli_query($link,$sql_modif);
  $val_sql_modif = mysqli_fetch_array($sql_modif_result);

	$_SESSION["product_realname"] = htmlentities($val_sql_modif["realname"]);
	$_SESSION["product_name_fr"] = htmlentities($val_sql_modif["nom_fr"]);
  $_SESSION["product_name_de"] = htmlentities($val_sql_modif["nom_de"]);
  $_SESSION["product_name_es"] = htmlentities($val_sql_modif["nom_es"]);
  $_SESSION["product_name_it"] = htmlentities($val_sql_modif["nom_it"]);
  $_SESSION["product_name_en"] = htmlentities($val_sql_modif["nom_en"]);
	$_SESSION["product_type"] = htmlentities($val_sql_modif["id_type"]);
  $_SESSION["product_remise_lvl"] = htmlentities($val_sql_modif["remise_lvl"]);
  $_SESSION["product_auteur_choice"] = htmlentities($val_sql_modif["id_auteur"]);
  $_SESSION["product_editeur_choice"] = htmlentities($val_sql_modif["id_editeur"]);
  $_SESSION["product_reference"] = htmlentities($val_sql_modif["reference"]);
  $_SESSION["product_categorie"] = htmlentities($val_sql_modif["id_categorie"]);
  $_SESSION["product_issn"] = htmlentities($val_sql_modif["issn"]);
  $_SESSION["product_isbn"] = htmlentities($val_sql_modif["isbn"]);
  $_SESSION["product_page"] = htmlentities($val_sql_modif["nb_pages"]);
  $_SESSION["product_terme"] = htmlentities($val_sql_modif["nb_termes"]);
  $_SESSION["product_poid"] = htmlentities($val_sql_modif["poid"]);
  $sql_all_lang = "SELECT id_langue_source, id_langue_cible
                   FROM langue_produit
                   WHERE id_produit = '" . $_GET["id_produit"] . "'
								   ORDER BY langue_produit.id_langue_source
                   ;";
  $sql_all_lang_result = mysqli_query($link,$sql_all_lang);
  $i = 1;
  while($val_alllang = mysqli_fetch_array($sql_all_lang_result))
  {
    $_SESSION["product_source$i"] = $val_alllang["id_langue_source"];
    $_SESSION["product_cible$i"] = $val_alllang["id_langue_cible"];
		$i++;
  }
  $_SESSION["product_description_fr"] = htmlentities($val_sql_modif["description_fr"]);
  $_SESSION["product_description_de"] = htmlentities($val_sql_modif["description_de"]);
  $_SESSION["product_description_es"] = htmlentities($val_sql_modif["description_es"]);
  $_SESSION["product_description_it"] = htmlentities($val_sql_modif["description_it"]);
  $_SESSION["product_description_en"] = htmlentities($val_sql_modif["description_en"]);

  $_SESSION["product_index"] = htmlentities($val_sql_modif["ind"]);
  $_SESSION["product_libraire"] = htmlentities($val_sql_modif["libraire"]);
  $_SESSION["product_divers_fr"] = htmlentities($val_sql_modif["info_divers_fr"]);
  $_SESSION["product_divers_de"] = htmlentities($val_sql_modif["info_divers_de"]);
  $_SESSION["product_divers_es"] = htmlentities($val_sql_modif["info_divers_es"]);
  $_SESSION["product_divers_it"] = htmlentities($val_sql_modif["info_divers_it"]);
  $_SESSION["product_divers_en"] = htmlentities($val_sql_modif["info_divers_en"]);

	$_SESSION["product_prix"] = htmlentities($val_sql_modif["prix"]);
  $_SESSION["product_prix_editeur"] = htmlentities($val_sql_modif["prix_editeur"]);
  $_SESSION["product_rabais"] = htmlentities($val_sql_modif["rabais"]);
  $_SESSION["product_delai_reapprovisionnement"] = htmlentities($val_sql_modif["delai_reapprovisionnement"]);
  $_SESSION["product_nb_dispo"] = htmlentities($val_sql_modif["nb_dispo"]);
  $_SESSION["product_disponible"] = htmlentities($val_sql_modif["disponible"]);
  $_SESSION["product_url"] = htmlentities($val_sql_modif["url"]);
  $_SESSION["product_pdfap"] = htmlentities($val_sql_modif["pdfap"]);
  $_SESSION["product_jourparution"] = htmlentities(substr($val_sql_modif["date_parution"], 8, 2));
  $_SESSION["product_moisparution"] = htmlentities(substr($val_sql_modif["date_parution"], 5, 2));
  $_SESSION["product_anneeparution"] = htmlentities(substr($val_sql_modif["date_parution"], 0, 4));
  $_SESSION["langues"] = $val_sql_modif["langues"];
  $_SESSION["product_actif"] = $val_sql_modif["sommeil"];
  $_SESSION["product_affaire"] = $val_sql_modif["affaire"];
  $_SESSION["product_ofmonth"] = $val_sql_modif["monthbook"];
  $_SESSION["product_carrousel"] = $val_sql_modif["carrousel"];
  $_SESSION["product_occasion"] = $val_sql_modif["occasion"];



  $sql_avant = "SELECT *
                FROM vitrine
                WHERE id_produit = '" . $_GET["id_produit"] . "'
               ;";
  $sql_avant_result = mysqli_query($link,$sql_avant);
  if (mysqli_num_rows($sql_avant_result))
    $_SESSION["product_avant"] = 1;

}
$nblangue = 15;
if (isset($_SESSION["product_error"]) && $_SESSION["product_error"] != '')
{
  echo "<div id='error'>";
  echo "<b>Erreur</b><br />";
  echo $_SESSION["product_error"];
  echo "</div>";
}

if (isset($_GET["etat"]) && ($_GET["etat"] == "Verifier" or $_GET["etat"] == "Supprimer") )
{
  //On affiche un aper�u
  echo "<div id='preview'>";
  echo "R�f�rence : " . $_SESSION["product_reference"] . "<br /><br />";
  echo "<strong>Nom de l'ouvrage (fr): " . $_SESSION["product_name_fr"] . "</strong><br /><br />";
  /*
	echo "<strong>Nom de l'ouvrage (de): " . $_SESSION["product_name_de"] . "</strong><br /><br />";
  echo "<strong>Nom de l'ouvrage (es): " . $_SESSION["product_name_es"] . "</strong><br /><br />";
  echo "<strong>Nom de l'ouvrage (it): " . $_SESSION["product_name_it"] . "</strong><br /><br />";
  echo "<strong>Nom de l'ouvrage (en): " . $_SESSION["product_name_en"] . "</strong><br /><br />";
	*/
  if ($_SESSION["product_categorie"])
  {
    //On r�cup�re la cat�gorie
    $sql_affichecateg = "SELECT nom_fr as nom
                        FROM categorie
                        WHERE id_categorie = '" . $_SESSION["product_categorie"] . "'
                        ;";
    $sql_affichecateg_result = mysqli_query($link,$sql_affichecateg);
    $val_affichecateg = mysqli_fetch_array($sql_affichecateg_result);

    echo "Cat�gorie : " . $val_affichecateg["nom"] . "<br />";
  }


  if ($_SESSION["product_auteur"] != "")
    echo "Auteur : " . $_SESSION["product_auteur"] . "<br />";
  else
  {
    $nom_auteur = "SELECT nom
                   FROM auteur
                   WHERE id_auteur = '" . $_SESSION["product_auteur_choice"] . "'
                  ;";
    $nom_auteur_result = mysqli_query($link,$nom_auteur);
    $val_nom_auteur = mysqli_fetch_array($nom_auteur_result);
    echo "Auteur : " . $val_nom_auteur["nom"] . "<br />";
  }
  if ($_SESSION["product_editeur"] != "")
    echo "Editeur : " . $_SESSION["product_editeur"] . "<br />";
  else
  {
    $nom_editeur = "SELECT nom
                   FROM editeur
                   WHERE id_editeur = '" . $_SESSION["product_editeur_choice"] . "'
                  ;";
    $nom_editeur_result = mysqli_query($link,$nom_editeur);
    $val_nom_editeur = mysqli_fetch_array($nom_editeur_result);
    echo "Editeur : " . $val_nom_editeur["nom"] . "<br />";
  }


  if ($_SESSION["product_prix"] == 0 or trim($_SESSION["product_prix"]) == "")
    echo "<b>Indisponible</b><br />";
  else
    echo "Prix HT : " . $_SESSION["product_prix"] . "&euro;<br />";

	echo "Description (fr) : " . nl2br($_SESSION["product_description_fr"]) . "<br />";
  /*
	echo "Description (de) : " . nl2br($_SESSION["product_description_de"]) . "<br />";
  echo "Description (es) : " . nl2br($_SESSION["product_description_es"]) . "<br />";
  echo "Description (it) : " . nl2br($_SESSION["product_description_it"]) . "<br />";
  echo "Description (en) : " . nl2br($_SESSION["product_description_en"]) . "<br />";
  */

  echo "Informations diverses (fr) : " . nl2br($_SESSION["product_divers_fr"]) . "<br />";
  /*
	echo "Informations diverses (de) : " . nl2br($_SESSION["product_divers_de"]) . "<br />";
  echo "Informations diverses (es) : " . nl2br($_SESSION["product_divers_es"]) . "<br />";
  echo "Informations diverses (it) : " . nl2br($_SESSION["product_divers_it"]) . "<br />";
  echo "Informations diverses (en) : " . nl2br($_SESSION["product_divers_en"]) . "<br />";
  */

	echo "Date de parution : " . $_SESSION["product_jourparution"] . "/" . $_SESSION["product_moisparution"] . "/" . $_SESSION["product_anneeparution"] . "<br />";
  echo "</div>";
}
if (isset($_GET["id_produit"]))
	echo "<form action='modifproduct.php?id_produit=" . $_GET["id_produit"] . "' method='post' id='formaddproduct'>";
else
	echo "<form action='modifproduct.php?id_produit=' method='post' id='formaddproduct'>";

if (isset($_GET["modifier"]) && $_GET["modifier"] == 1)
{
  echo "<a href='index2.php?page=showpic&produit=" . $_GET["id_produit"] . "' class='modifimg'>"
	."<img src='css/modifimg.png' alt='' /> "
	."Image associ�e</a>";

// modification pour introduire un bouton supprimer . on ajoute ce bouton
// on cree ce bouton qui donnera la valeur "Supprimer" a product_submitaddproduct et qui appellera modifproduct.php
//  echo "<input class="btn btn-info" type ="submit" value="Supprimer" name="product_submitaddproduct" />";

}

if (isset($_GET["etat"]) && $_GET["etat"] == "Supprimer" && $_GET["id_produit"] != "" && ($_SESSION["product_error"] == "" or !$_SESSION["product_error"]))

// suite modification relative a la suppresion: la on est dans le cas du retour de modifproduct.php et avec etat="Supprimer" on cree le bouton demandant
// la confirmation de la suppression qui rappellera modifproduct.php pour execution de la suppression 
{
  echo "<input type='submit' value='Supprimer vraiment ?' name='product_submitaddproduct' />";
  $_SESSION["product_error"] = "";
}

?>
<input class="btn btn-info" type ="submit" value="Supprimer" name="product_submitaddproduct" />
<table class="table table-brdered"	>
<tr>
  <td>Type de produit</td>
  <td>
    <select  class="selectpicker show-menu-arrow" name="product_type">
<?php
$sql_recuptva = "SELECT id_type, nom_fr as nom
                 FROM type
                 ORDER by nom
                 ;";
$sql_recuptva_result = mysqli_query($link,$sql_recuptva);
if(isset($_SESSION["product_type"]) && $_SESSION["product_type"] != '')
{
  while ($val_recuptva = mysqli_fetch_array($sql_recuptva_result))
  {
    if (isset($_SESSION["product_type"]) && $_SESSION["product_type"] == $val_recuptva["id_type"])
    {
      echo "<option value='" . $val_recuptva["id_type"] . "' selected>" . $val_recuptva["nom"] . "</option>";
    }
    else
      echo "<option value='" . $val_recuptva["id_type"] . "'>" . $val_recuptva["nom"] . "</option>";
  }
}
else
{
  while ($val_recuptva = mysqli_fetch_array($sql_recuptva_result))
  {
    if ($val_recuptva["id_type"] == 1)
    {
      echo "<option value='" . $val_recuptva["id_type"] . "' selected>" . $val_recuptva["nom"] . "</option>";
    }
    else
      echo "<option value='" . $val_recuptva["id_type"] . "'>" . $val_recuptva["nom"] . "</option>";
  }
}
?>
    </select>
  </td>
</tr>
<tr>
  <td>Nom original*</td>
  <td><input type="text" size="50" value="<?php if (isset($_SESSION["product_realname"])) {echo $_SESSION["product_realname"];} ?>" name="product_realname" /></td>
</tr>
<tr>
  <td>Nom de l'ouvrage (fr)*</td>
  <td><input type="text" size="50" value="<?php if (isset($_SESSION["product_name_fr"])) {echo $_SESSION["product_name_fr"];} ?>" name="product_name_fr" /></td>
</tr>
<tr>
  <td>Nom de l'ouvrage (de) *</td>
  <td><input type="text" size="50" value="<?php if (isset($_SESSION["product_name_de"])) {echo $_SESSION["product_name_de"];} ?>" name="product_name_de" /></td>
</tr>
<tr>
  <td>Nom de l'ouvrage (es) *</td>
  <td><input type="text" size="50" value="<?php if (isset($_SESSION["product_name_es"])) {echo $_SESSION["product_name_es"];} ?>" name="product_name_es" /></td>
</tr>
<tr>
  <td>Nom de l'ouvrage (it) *</td>
  <td><input type="text" size="50" value="<?php if (isset($_SESSION["product_name_it"])) {echo $_SESSION["product_name_it"];} ?>" name="product_name_it" /></td>
</tr>
<tr>
  <td>Nom de l'ouvrage (en) *</td>
  <td><input type="text" size="50" value="<?php if (isset($_SESSION["product_name_en"])) {echo $_SESSION["product_name_en"];} ?>" name="product_name_en" /></td>
</tr>
<tr>
  <td>Existe en format num�rique </td>
  <td><input type="radio" name="product_numerique" /></td>
</tr>

<tr>
  <td valign='top'>Auteur*</td>
  <td><input type="text" value="<?php if(isset($_SESSION["product_auteur"])) {echo $_SESSION["product_auteur"];} ?>" name="product_auteur" /><br />ou s�lectionner un auteur existant<br />
    <select class="selectpicker" data-live-search="true" name="product_auteur_choice">
      <option value=""></option>
<?php
$sql_recupauteur = "SELECT *
                   FROM auteur
                   ORDER by nom
                  ;";
$sql_recupauteur_result = mysqli_query($link,$sql_recupauteur);
while ($val_recupauteur = mysqli_fetch_array($sql_recupauteur_result))
{
  if (isset($_SESSION["product_auteur_choice"]) && $_SESSION["product_auteur_choice"] == $val_recupauteur["id_auteur"])
    echo "<option value='" . $val_recupauteur["id_auteur"] . "' selected>" . htmlentities($val_recupauteur["nom"]) . "</option>";
	else
	  echo "<option value='" . $val_recupauteur["id_auteur"] . "'>" . htmlentities($val_recupauteur["nom"]) . "</option>";
}
?>
    </select>
  </td>
</tr>
<tr>
  <td>Editeur*</td>
  <td><input type="text" value="<?php if (isset($_SESSION["product_editeur"])) {echo $_SESSION["product_editeur"];} ?>" name="product_editeur" /><br />ou s�lectionner un �diteur existant<br />
    <select class="selectpicker" name="product_editeur_choice">
      <option value=""></option>
<?php
$sql_recupediteur = "SELECT *
                   FROM editeur
                   ORDER by nom
                  ;";
$sql_recupediteur_result = mysqli_query($link,$sql_recupediteur);
while ($val_recupediteur = mysqli_fetch_array($sql_recupediteur_result))
{
  if (isset($_SESSION["product_editeur_choice"]) && $_SESSION["product_editeur_choice"] == $val_recupediteur["id_editeur"])
    echo "<option value='" . $val_recupediteur["id_editeur"] . "' selected>" . $val_recupediteur["nom"] . "</option>";
	else
		echo "<option value='" . $val_recupediteur["id_editeur"] . "'>" . $val_recupediteur["nom"] . "</option>";
}
?>
    </select>
  </td>
</tr>
<tr>
  <td>Cat�gorie*</td>
  <td>
    <select class="selectpicker" name="product_categorie">
      <option value="<?php if (isset($_SESSION["product_categorie"])) {echo $_SESSION["product_categorie"];} ?>"></option>
<?php
$sql_recupcateg = "SELECT id_categorie, nom_fr as nom
                   FROM categorie
                   ORDER by nom_fr
                  ;";
$sql_recupcateg_result = mysqli_query($link,$sql_recupcateg);
while ($val_recupcateg = mysqli_fetch_array($sql_recupcateg_result))
{
  if (isset($_SESSION["product_categorie"]) && $_SESSION["product_categorie"] == $val_recupcateg["id_categorie"])
    echo "<option value='" . $val_recupcateg["id_categorie"] . "' selected>" . $val_recupcateg["nom"] . "</option>";
	else
  	echo "<option value='" . $val_recupcateg["id_categorie"] . "'>" . $val_recupcateg["nom"] . "</option>";
}
?>
    </select>
  </td>
</tr>
<tr>
  <td>R�f�rence*</td>
  <td><input type="text" size="15" maxlenght="15" value="<?php if (isset($_SESSION["product_reference"])) {echo $_SESSION["product_reference"];} ?>" name="product_reference" /></td>
</tr>
<tr>
  <td>ISBN</td>
  <td><input type="text" size="15" maxlenght="15" value="<?php if (isset($_SESSION["product_isbn"])) {echo $_SESSION["product_isbn"];} ?>" name="product_isbn" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      ISSN <input type="text" size="15" maxlenght="15" value="<?php if (isset($_SESSION["product_issn"])) {echo $_SESSION["product_issn"];} ?>" name="product_issn" />
 </td>
</tr>
<tr>
  <td>Nombre de pages*</td>
  <td><input type="text" size="6" value="<?php if (isset($_SESSION["product_page"])) {echo $_SESSION["product_page"];} ?>" name="product_page" />pages</td>
</tr>
<tr>
<td>Nombre de termes*</td>
<td><input type="text" size="8" value="<?php if (isset($_SESSION["product_terme"])) {echo $_SESSION["product_terme"];} ?>" name="product_terme" />termes</td>
</tr>
<tr>
<td>Poids</td>
<td><input type="text" value="<?php if (isset($_SESSION["product_poid"])) {echo $_SESSION["product_poid"];} ?>" name="product_poid" />grammes</td>
</tr>
<tr>
  <td>langues</td>
  <td>
<?php

$sql_recuplang = "SELECT id_langue_dispo, nom_fr as nom
                  FROM langue_dispo
                  ORDER by nom_fr
                  ;";
$sql_recuplang_result = mysqli_query($link,$sql_recuplang);

$num = 0;
while ($val_recuplang = mysqli_fetch_array($sql_recuplang_result))
{
  $tabidlang[$num] = $val_recuplang["id_langue_dispo"];
  $tabnomlang[$num] = $val_recuplang["nom"];
  $num++;
}

for ($j=0; $j<$nblangue; $j++)
{
	echo "Source&nbsp;";
	echo "<select class='selectpicker' name='product_source" . ($j+1) . "'>";
	echo "<option value=''>&nbsp;</option>";
	$i=0;
    $pos = $j+1;
	foreach($tabidlang as $v)
	{
		if(isset($_SESSION["product_source$pos"]) && $_SESSION["product_source$pos"] == $v)
	    echo "<option value='" . $v . "' selected>" . $tabnomlang[$i] . "</option>";
	  else
	    echo "<option value='" . $v . "'>" . $tabnomlang[$i] . "</option>";
	  $i++;
	}
	echo "</select>";

	echo "&nbsp;Cible&nbsp;";
	echo "<select class='selectpicker' name='product_cible" . ($j+1) . "'>";
	echo "<option value=''>&nbsp;</option>";
	$i=0;
	foreach($tabidlang as $v)
	{
		if(isset($_SESSION["product_cible$pos"]) && $_SESSION["product_cible$pos"] == $v)
	  	echo "<option value='" . $v . "' selected>" . $tabnomlang[$i] . "</option>";
	  else
	  	echo "<option value='" . $v . "'>" . $tabnomlang[$i] . "</option>";
	  $i++;
	}
	echo "</select>";
	echo "<br />";
}
?>

   </td>
</tr>

<?php
if (isset($_SESSION["langues"]) && isset($_GET["id_produit"]))
{
?>
<tr>
  <td>Langues � corriger</td>
  <td><?php echo $_SESSION["langues"]; ?></td>
</tr>
<?php
}
?>
<tr>
  <td>Index<br />(cocher si oui)</td>
  <td>
  <?php
    if (isset($_SESSION["product_index"]) && $_SESSION["product_index"] == 1)
      echo "<input type='checkbox' name='product_index' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_index' value='1' />";
  ?>
  </td>
</tr>

<tr>
  <td>Description (fr)</td>
  <td><textarea cols="50" rows="10" name="product_description_fr"><?php if (isset($_SESSION["product_description_fr"])) {echo  stripslashes(html_entity_decode($_SESSION["product_description_fr"]));} ?></textarea></td>
</tr>
<tr>
  <td>Description (de)</td>
  <td><textarea cols="50" rows="10" name="product_description_de"><?php if (isset($_SESSION["product_description_de"])) {echo  stripslashes(html_entity_decode($_SESSION["product_description_de"]));} ?></textarea></td>
</tr>
<tr>
  <td>Description (es)</td>
  <td><textarea cols="50" rows="10" name="product_description_es"><?php if (isset($_SESSION["product_description_es"])) {echo  stripslashes(html_entity_decode($_SESSION["product_description_es"]));} ?></textarea></td>
</tr>
<tr>
  <td>Description (it)</td>
  <td><textarea cols="50" rows="10" name="product_description_it"><?php if (isset($_SESSION["product_description_it"])) {echo  stripslashes(html_entity_decode($_SESSION["product_description_it"]));} ?></textarea></td>
</tr>
<tr>
  <td>Description (en)</td>
  <td><textarea cols="50" rows="10" name="product_description_en"><?php if (isset($_SESSION["product_description_en"])) {echo  stripslashes(html_entity_decode($_SESSION["product_description_en"]));} ?></textarea></td>
</tr>
<tr>
  <td>Divers (fr) Obligatoire pour la traduction</td>
  <td><textarea cols="50" rows="10" name="product_divers_fr"><?php if (isset($_SESSION["product_divers_fr"])) {echo  stripslashes(html_entity_decode($_SESSION["product_divers_fr"]));} ?></textarea></td>
</tr>
<tr>
  <td>Divers (de)</td>
  <td><textarea cols="50" rows="10" name="product_divers_de"><?php if (isset($_SESSION["product_divers_de"])) {echo  stripslashes(html_entity_decode($_SESSION["product_divers_de"]));} ?></textarea></td>
</tr>
<tr>
  <td>Divers (es)</td>
  <td><textarea cols="50" rows="10" name="product_divers_es"><?php if (isset($_SESSION["product_divers_es"])) {echo  stripslashes(html_entity_decode($_SESSION["product_divers_es"]));} ?></textarea></td>
</tr>
<tr>
  <td>Divers (it)</td>
  <td><textarea cols="50" rows="10" name="product_divers_it"><?php if (isset($_SESSION["product_divers_it"])) {echo  stripslashes(html_entity_decode($_SESSION["product_divers_it"]));} ?></textarea></td>
</tr>
<tr>
  <td>Divers (en)</td>
  <td><textarea cols="50" rows="10" name="product_divers_en"><?php if (isset($_SESSION["product_divers_en"])) {echo  stripslashes(html_entity_decode($_SESSION["product_divers_en"]));} ?></textarea></td>
</tr>
<tr>
  <td>Prix HT *</td>
  <td><input type="text" size="10" maxlenght="10" value="<?php if (isset($_SESSION["product_prix"])) {echo $_SESSION["product_prix"];} ?>" name="product_prix" />&euro;</td>
</tr>
<tr>
  <td>Prix �diteur</td>
  <td><input type="text" size="10" maxlenght="10" value="<?php if (isset($_SESSION["product_prix_editeur"])) {echo $_SESSION["product_prix_editeur"];} ?>" name="product_prix_editeur" />&euro;</td>
</tr>
<tr>
  <td>Remise</td>
  <td><input type="text" size="10" maxlenght="10" value="<?php if (isset($_SESSION["product_rabais"])) {echo $_SESSION["product_rabais"];} ?>" name="product_rabais" />%</td>
</tr>
<tr>
  <td>Niveau<br />de remise</td>
  <td>
    <select class="selectpicker" name="product_remise_lvl">
<?php
  for($i=0; $i<10; $i++)
  {
    if (isset($_SESSION["product_remise_lvl"]) && $_SESSION["product_remise_lvl"] == $i)
      echo "<option value='$i' selected>$i</option>";
    else
      echo "<option value='$i'>$i</option>";
  }
?>
    </select>
  </td>
</tr>
<tr>
  <td>D�lai de<br />r�approvisionnement</td>
  <td><input type="text" size="10" maxlenght="10" value="<?php if (isset($_SESSION["product_delai_reapprovisionnement"])) {echo $_SESSION["product_delai_reapprovisionnement"];} ?>" name="product_delai_reapprovisionnement" />jours</td>
</tr>
<tr>
  <td>Nombre d'ouvrages<br />disponibles</td>
  <td><input type="text" size="10" maxlenght="10" value="<?php if (isset($_SESSION["product_nb_dispo"])) {echo $_SESSION["product_nb_dispo"];} ?>" name="product_nb_dispo" /></td>
</tr>
<tr>
  <td>Disponible<br />(cocher si oui)</td>
  <td>
  <?php
    if (isset($_SESSION["product_disponible"]) && $_SESSION["product_disponible"] == 1)
      echo "<input type='checkbox' name='product_disponible' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_disponible' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>PDF pr�sentation</td>
  <td><input type="text" size="50" maxlength="255" value="<?php if (isset($_SESSION["product_pdfap"])) {echo $_SESSION["product_pdfap"];}?>" name="product_pdfap" /></td>
</tr>
<tr>
<td>Date de parution*<br />(jj/mm/aaaa)</td>
  <td>
      <select class="selectpicker" name="product_jourparution">
      <option value="">&nbsp;</option>
<?php
for ($i=1; $i <= 31; $i++)
{
  $a = $i;
  if (strlen($a) == 1)
    $a = "0$a";

  if (isset($_SESSION["product_jourparution"]) && $_SESSION["product_jourparution"] != '')
  {
    if (isset($_SESSION["product_jourparution"]) && $_SESSION["product_jourparution"] == $a)
      echo "<option value='$a' selected>$a</option>\n";
    else
      echo "<option value='$a'>$a</option>\n";
  }
  else
  {
    if ($a == 1)
      echo "<option value='$a' selected='selected'>$a</option>\n";
    else
      echo "<option value='$a'>$a</option>\n";
  }
}
?>
    </select>/
    <select class="selectpicker" name="product_moisparution">
      <option value=''>&nbsp;</option>
<?php
for ($i=1; $i <= 12; $i++)
{
  $a = $i;
  if (strlen($a) == 1)
    $a = "0$a";
  if (isset($_SESSION["product_moisparution"]) && $_SESSION["product_moisparution"] == $a)
    echo "<option value='$a' selected>$a</option>\n";
  else
    echo "<option value='$a'>$a</option>\n";
}
?>
    </select>/
      <select class="selectpicker"  name="product_anneeparution">
      <option value=''>&nbsp;</option>
<?php
for ($i=date("Y")+15; $i > 1849; $i--)
{
  if (isset($_SESSION["product_anneeparution"]) && $_SESSION["product_anneeparution"] == $i)
    echo "<option value='$i' selected>$i</option>\n";
  else
 		echo "<option value='$i'>$i</option>\n";
}
?>
    </select>
  </td>
</tr>
<tr>
  <td>Produit en vitrine</td>
  <td>
  <?php
    if (isset($_SESSION["product_avant"]) && $_SESSION["product_avant"] == 1)
      echo "<input type='checkbox' name='product_avant' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_avant' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>Acc�s libraire</td>
  <td>
  <?php
    if (isset($_SESSION["product_libraire"]) && $_SESSION["product_libraire"] == 1)
      echo "<input type='checkbox' name='product_libraire' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_libraire' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>Cocher pour<br /> d�sactiver</td>
  <td>
  <?php
    if (isset($_SESSION["product_actif"]) && $_SESSION["product_actif"] == 1)
      echo "<input type='checkbox' name='product_actif' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_actif' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>Affaire<br />du jour</td>
  <td>
  <?php
    if (isset($_SESSION["product_affaire"]) && $_SESSION["product_affaire"] == 1)
      echo "<input type='checkbox' name='product_affaire' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_affaire' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>Produit du mois</td>
  <td>
  <?php
    if (isset($_SESSION["product_ofmonth"]) && $_SESSION["product_ofmonth"] == 1)
      echo "<input type='checkbox' name='product_ofmonth' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_ofmonth' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>Produit dans le carrousel</td>
  <td>
  <?php
    if (isset($_SESSION["product_carrousel"]) && $_SESSION["product_carrousel"] == 1)
      echo "<input type='checkbox' name='product_carrousel' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_carrousel' value='1' />";
  ?>
  </td>
</tr>
<tr>
  <td>Occasion</td>
  <td>
  <?php
    if (isset($_SESSION["product_occasion"]) && $_SESSION["product_occasion"] == 1)
      echo "<input type='checkbox' name='product_occasion' value='1' checked />";
    else
      echo "<input type='checkbox' name='product_occasion' value='1' />";
  ?>
  </td>
</tr>
</table>
<input class="btn btn-info" type="submit" value="Verifier" name="product_submitaddproduct" />
<?php

if (isset($_GET["etat"]) && $_GET["etat"] == "Verifier" && ($_SESSION["product_error"] == "" or !$_SESSION["product_error"]) && (!$_GET["id_produit"] or $_GET["id_produit"] == ""))
{
  echo "<input type='submit' value='Ajouter' name='product_submitaddproduct' />";
  $_SESSION["product_error"] = "";
}


if (isset($_GET["etat"]) && $_GET["etat"] == "Verifier" && $_GET["id_produit"] != "" && ($_SESSION["product_error"] == "" or !$_SESSION["product_error"]))
{
  echo "<input type='submit' value='Enregistrer les modifications' name='product_submitaddproduct' />";
  $_SESSION["product_error"] = "";
}

?>
</form>
<?php
//on efface les sessions
$id_newproduct = "";

$_SESSION["product_realname"] = "";
$_SESSION["product_name_fr"] = "";
$_SESSION["product_name_de"] = "";
$_SESSION["product_name_es"] = "";
$_SESSION["product_name_it"] = "";
$_SESSION["product_name_en"] = "";
$_SESSION["product_auteur"] = "";
$_SESSION["product_auteur_choice"] = "";
$_SESSION["product_type"] = "";
$_SESSION["product_remise_lvl"] = "";
$_SESSION["product_categorie"] = "";
$_SESSION["product_reference"] = "";
$_SESSION["product_issn"] = "";
$_SESSION["product_isbn"] = "";
$_SESSION["product_page"] = "";
$_SESSION["product_terme"] = "";
$_SESSION["product_poid"] = "";
$i = 0;
for ($d=1; $d < ($nblangue + 1) ;$d++)
{
  $_SESSION["product_source$d"] = "";
  $_SESSION["product_cible$d"] = "";
}

$_SESSION["product_index"] = "";
$_SESSION["product_libraire"] = "";
$_SESSION["product_description_fr"] = "";
$_SESSION["product_description_de"] = "";
$_SESSION["product_description_es"] = "";
$_SESSION["product_description_it"] = "";
$_SESSION["product_description_en"] = "";
$_SESSION["product_divers_fr"] = "";
$_SESSION["product_divers_de"] = "";
$_SESSION["product_divers_es"] = "";
$_SESSION["product_divers_it"] = "";
$_SESSION["product_divers_en"] = "";
$_SESSION["product_prix"] = "";
$_SESSION["product_prix_editeur"] = "";
$_SESSION["product_rabais"] = "";
$_SESSION["product_delai_reapprovisionnement"] = "";
$_SESSION["product_nb_dispo"] = "";
$_SESSION["product_disponible"] = "";
$_SESSION["product_url"] = "";
$_SESSION["product_pdfap"] = "";
$_SESSION["product_jourparution"] = '';
$_SESSION["product_moisparution"] = "";
$_SESSION["product_anneeparution"] = "";
$_SESSION["product_disponible"] = "";
$_SESSION["product_actif"] = "";
$_SESSION["product_affaire"] = "";
$_SESSION["product_libraire"] = "";
$_SESSION["product_ofmonth"] = "";
$_SESSION["product_avant"] = "";
$_SESSION["product_error"] = "";
?>
