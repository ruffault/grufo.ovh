﻿{literal}
<script type="text/javascript">
$(document).ready(function(){
	//Configuration
		  var retour = true;
		  var tempsTransition = 1000;
		  var affichePlayPause = true;
		  var lectureAutomatique = true;
		  	var tempsAttente = 6000;
			
		  var icones = new Array();
		  		icones['play'] = 'img/play_slider.png';
		  		icones['pause'] = 'img/pause_slider.png';	
			
		  var currentPosition = 0;
		  var slideWidth = 500;
		  var slides = $('.slide');
		  var numberOfSlides = slides.length;
		  var interval;
		  var lectureEnCours = false;
  // Supprime la scrollbar en JS
  $('#slidesContainer').css('overflow', 'hidden');

  // Attribue  #slideInner  à toutes les div .slide
  slides
    .wrapAll('<div id="slideInner"></div>')
    // Float left to display horizontally, readjust .slides width
	.css({
      'float' : 'left',
      'width' : slideWidth
    });

  // Longueur de #slideInner égale au total de la longueur de tous les slides
  $('#slideInner').css('width', slideWidth * numberOfSlides);

  // Insert controls in the DOM
  $('#slideshow')
    .prepend('<span class="control" id="leftControl">Précédent</span>')
    .append('<span class="control" id="rightControl">Suivant</span>');


  
  // Hide left arrow control on first load
  manageControls(currentPosition);

  //Crée un écouteur d'évènement de type clic sur les classes .control
  $('.control')
    .bind('click', function(){
		
    // Determine la nouvelle position
	currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
    
	if(currentPosition == numberOfSlides && retour == false ){
		currentPosition--;
		pause();
	}
	
	// Cache ou montre les controles
    manageControls(currentPosition);
    // Fais bouger le slide
    $('#slideInner').animate({
      'marginLeft' : slideWidth*(-currentPosition)
    },tempsTransition);
  });

  // manageControls: Cache ou montre les flêches de controle en fonction de la position courante
  function manageControls(position){
    // Cache la fleche "précédent" si on est sur le premier slide
	if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
	// Cache la fleche "suivant" si on est sur le dernier slide (et que le retour automatique n'est pas activé)
    if(position==numberOfSlides-1 && retour == false){
		$('#rightControl').hide();
	} else {
		$('#rightControl').show();
	}
	if(position == numberOfSlides && retour == true){
		currentPosition = 0;
		 $('#leftControl').hide();
	}
  }
  function suivant(){
	$('#rightControl').click();
	}
  function start() {
  	lectureEnCours = true;
    interval = setInterval(suivant, tempsAttente );
  }
  function pause() {
  	lectureEnCours = false;
   clearInterval(interval);
  }
  
 //Si le diapo est activé 
if(lectureAutomatique == true){
  start();
}
if(affichePlayPause == true){

	if(lectureAutomatique == true){
		$('#navDiapo').attr('src',icones['pause']);
	}else{
		$('#navDiapo').attr('src',icones['play']);	
	}
	$('#navDiapo').bind('click', function(){
		if(lectureEnCours == true){
			$(this).attr('src',icones['play']);
			pause();
		}else{
			$(this).attr('src',icones['pause']);
			start();
		}
	});
}


  	
});
{/literal}
</script>
<!-- je supprime cette section annonce que je mets dans header.tpl 
<section class= "annonce">  
	<label
		for="gauche-toggle" class="top-nav-label"
		id="gauche-toggle-label" >
		<i class="gauche__icon"></i>
	</label>


	<label
		for="droite-toggle" class="top-nav-label"
		id="droite-toggle-label" >
		<span class="droite__icon">+</span>
	</label>

</section> -->
 <!-- fin de la section promotion -->
	<!-- Slideshow HTML -->
	<div id="slideshow">
		<div id="slidesContainer" class="border_all">
	{*
      <div class="slide">
     	 <div class="titre">
       		 <h2>EuRom5 - Conférence Expolangues Vendredi 3 février 2012</h2>
         </div>
         <div class="contenu">
				<a href="https://www.expolangues.fr/conferencesEL/vendredi.html">
				<img width="110"src="{$urlsite}img/expolang-2012.jpg" alt="expo-langues" /></a>
				<h3><a href="https://www.expolangues.fr/conferencesEL/vendredi.html">Une innovation dans l’apprentissage des langues : l’intercompréhension.<br>Présentation de la méthode « EuRom5 ».</a></h3>
				<p class='description'>Conférence organisée par la Délégation générale à la langue française et aux langues de France, ministère de la Culture et de la Communication.
				<br>Vendredi 3 février 2012 - 17h - 18h salle AIMÉ CÉSAIRE
				<br><a href="https://www.expolangues.fr/infosEL.html">Infos pratiques</a>
				<br>
				<br><span style="font-weight: bold;">Venez nombreux !</span>
				</p>
      	 </div>
      </div>*}
      
	    {section name=messages loop=$messages}
      <div class="slide">
     	 <div class="titre">
       		 <h2>{$messages[messages].TITRE_MSG|utf8_encode}</h2>
         </div>
      	<div class="contenu">
        	<br>
			<h3>{$messages[messages].CONTENU_MSG|utf8_encode|nl2br}</h3>
      	</div>
      </div>
      {/section}
      
	{section name=carrousel loop=$carrousel}
      <div class="slide">
     	 <div class="titre">
       		 <h2>Sélection de produits</h2>
         </div>
         <div class="contenu">
				<a href="{$urlsite}{$carrousel[carrousel].id_produit|product_link:$carrousel[carrousel].nom:$carrousel[carrousel].categorie}">
				<img src="{$urlsite}picproduct/{$carrousel[carrousel].id_produit}_mini.jpg" alt="{$carrousel[carrousel].nom|utf8_encode}" /></a>
				<h3><a href="{$urlsite}{$carrousel[carrousel].id_produit|product_link:$carrousel[carrousel].nom:$carrousel[carrousel].categorie}">{$carrousel[carrousel].nom|utf8_encode}</a></h3>
				<p class='description'>{$carrousel[carrousel].description|utf8_encode|truncate:205:"...":true}</p>
      	 </div>
      </div>
      {/section}



      
     
	</div>
	</div>
	<!-- fin de Slideshow HTML -->

