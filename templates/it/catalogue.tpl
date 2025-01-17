<div id="catalogue">

{if $smarty.get.idcat ne 0}
<h2>
    <span class="deco">></span>
    <span id='current'>
      <a href="{$urlsite}dictionnaire-et-lexique-c0">Catalogo</a>
      {section name=uppercat loop=$uppercat}
        &#062; <a href="{$urlsite}{$uppercat[uppercat].id|categ_link:$uppercat[uppercat].nom}">{$uppercat[uppercat].nom|utf8_encode}</a>
      {/section}
    </span>
</h2>
  {if $categorie_precise}
  <div id="categ">
    <table>
    <tr>
    {section name=categorie_precise loop=$categorie_precise}
      {if $categorie_total > 30}
        {cycle name="plop2" values="<td><ul>,,,,,,,,,,,,,,,,,"}
      {elseif $categorie_total > 20}
        {cycle name="plop2" values="<td><ul>,,,,,,,,,,,,,"}
      {elseif $categorie_total > 10}
        {cycle name="plop2" values="<td><ul>,,,,,"}
      {elseif $categorie_total > 6}
        {cycle name="plop2" values="<td><ul>,,"}
      {else}
        {cycle name="plop2" values="<td><ul>,,,"}
      {/if}
      
      	<li><a href="{$urlsite}{$categorie_precise[categorie_precise].id|categ_link:$categorie_precise[categorie_precise].nom}">{$categorie_precise[categorie_precise].nom|utf8_encode}</a></li>
      {if $categorie_total > 30}
        {cycle name="plop" values=",,,,,,,,,,,,,,,,,</ul></td>"}
      {elseif $categorie_total > 20}
        {cycle name="plop" values=",,,,,,,,,,,,,</ul></td>"}
      {elseif $categorie_total > 10}
        {cycle name="plop" values=",,,,,</ul></td>"}
      {elseif $categorie_total > 6}
        {cycle name="plop" values=",,</ul></td>"}
      {else}
        {cycle name="plop" values=",,,</ul></td>"}
      {/if}

    {/section}
    </tr>
    </table>
  </div>
  {/if}
{else}
  {if !$notitle}
    <h2><span class="deco">></span>Catalogo</h2>
  {/if}
  <table id="tabcatalogue">
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}abbreviazioni-c1">Abbreviazioni</a></h3>
    <a href="{$urlsite}sigle-c256">Sigle</a>
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}lingue-d-asia-e-vicino-oriente-oceania-c303">Lingue Asia e Vicino Oriente - Oceania</a></h3>
    <a href="{$urlsite}aramaico-c23">Aramaico</a>, <a href="{$urlsite}cinese-c65">Cinese</a>, <a href="{$urlsite}laotiano-c167">Laotiano</a>...
		<a href="{$urlsite}lingue-d-asia-e-vicino-oriente-oceania-c303">(Altro)
		</a>
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}amministrazione-e-gestione-d-impresa-c308">Amministrazione e gestione aziendale</a></h3>
    <a href="{$urlsite}communautes-europeennes-c70">Comunità Europea</a>, <a href="{$urlsite}economie-c103">Economia</a>, <a href="{$urlsite}management-c181">Management</a>...
		<a href="{$urlsite}administration-et-gestion-de-l-entreprise-c308">(Altro)
		</a>  
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}langues-europeennes-europe-de-l-est-c300">Lingue europee + Europa dell'Est</a></h3>
    <a href="{$urlsite}croate-c87">Croato</a>, <a href="{$urlsite}grec-c137">Greco</a>, <a href="{$urlsite}irlandais-c153">Irlandese</a>...
		<a href="{$urlsite}langues-europeennes-europe-de-l-est-c300">(Altro)</a>
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}batiment-travaux-publics-c305">Edilizia - Lavori pubblici</a></h3>
    <a href="{$urlsite}architecture-c24">Architettura</a>, <a href="{$urlsite}domotique-c98">Domotica</a>, <a href="{$urlsite}mecanique-des-sols-c191">Meccanica del terreno</a>...
		<a href="{$urlsite}batiment-travaux-publics-c305">(Altro)</a>
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}langues-indiennes-c301">Lingue indiane</a></h3>
    <a href="{$urlsite}hindi-c140">Hindi</a>, <a href="{$urlsite}nepali-c203">Nepalese</a>, <a href="{$urlsite}tibetain-c279">Tibetano</a>...
		<a href="{$urlsite}langues-indiennes-301">(Altro)</a>  
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}dictionnaires-electroniques-c312">Digital Dizionari</a></h3>
    <a href="{$urlsite}dictionnaires-electroniques-c312">Dizionari elettronici</a>, <a href="{$urlsite}E-Book-c351">E-Book</a>, <a href="{$urlsite}cd-rom-c59">CD-ROM</a>, <a href="{$urlsite}logiciel-c174">Software</a>...
		<a href="{$urlsite}dictionnaires-electroniques-c312">(Altro)</a>  
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}langues-regionales-de-france-c299">Lingue regionali della Francia</a></h3>
    <a href="{$urlsite}alsacien-c12">Alsaziano</a>, <a href="{$urlsite}occitan-c208">Occitano</a>, <a href="{$urlsite}savoyard-c250">Savoiardo</a>...
		<a href="{$urlsite}langues-regionales-de-france-c299">(Altro)</a>
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}dictionnaires-techniques-generaux-c304">Dizionari tecnici generali</a></h3>
    <a href="{$urlsite}anglais-technique-c19">Inglese tecnico</a>, <a href="{$urlsite}espagnol-technique-c116">Spagno tecnico</a>, <a href="{$urlsite}français-technique-c125">Francese tecnico</a>...
		<a href="{$urlsite}dictionnaires-techniques-generaux-c304">(Altro)</a>
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}sciences-de-la-terre-c309">Scienze della Terra</a></h3>
    <a href="{$urlsite}botanique-c50">Botanica</a>, <a href="{$urlsite}geographie-c133">Geografia</a>, <a href="{$urlsite}geologie-c134">Geologia</a>...
		<a href="{$urlsite}sciences-de-la-terre-c309">(Altro)</a>
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}industries-chimiques-c306">Industrie chimiche</a></h3>
    <a href="{$urlsite}cosmetiques-c78">Cosmetica</a>, <a href="{$urlsite}imprimerie-c149">Tipografia</a>, <a href="{$urlsite}textile-c277">Tessile</a>...
		<a href="{$urlsite}industries-chimiques-c306">(Altro)</a>  
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}sciences-de-la-vie-c310">Scienze della Vita</a></h3>
    <a href="{$urlsite}agro-alimentaire-c7">Agroalimentare</a>, <a href="{$urlsite}genetique-c131">Genetica</a>, <a href="{$urlsite}zoologie-c297">Zoologia</a>...
		<a href="{$urlsite}sciences-de-la-vie-c310">(Altro)</a>
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}industries-diverses-c307">Industrie diverse</a></h3>
    <a href="{$urlsite}arts-spectacles-c28">Arte-Spettacolo</a>, <a href="{$urlsite}sports-c262">Sport</a>, <a href="{$urlsite}tourisme-c283">Turismo</a>...
		<a href="{$urlsite}industries-diverses-c307">(Altro)</a>
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}sciences-physiques-c311">Scienze fisiche</a></h3>
    <a href="{$urlsite}chimie-c64">Chimica</a>, <a href="{$urlsite}electronique-c108">Elettronica</a>, <a href="{$urlsite}optique-c209">Ottica</a>...
		<a href="{$urlsite}sciences-physiques-c311">(Altro)</a> 
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}langue-francaise-c166">Lingua francese</a></h3>
    <a href="{$urlsite}analogie-francais-c16">Analogia</a>, <a href="{$urlsite}etymologie-francais-c119">Etimologia</a>, <a href="{$urlsite}linguistique-francais-c170">Linguistica</a>...
		<a href="{$urlsite}langue-francaise-c166">(Altro)</a> 
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}terminologie-c275">Terminologia</a></h3>
    <a href="{$urlsite}divers-c95">Varie</a>...
  </td>
  </tr>
  <tr>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}langues-africaines-c302">Lingue africane</a></h3>
    <a href="{$urlsite}afrikaans-c5">Afrikaans</a>, <a href="{$urlsite}swahili-c266">Swahili</a>, <a href="{$urlsite}wolof-c294">Wolof</a>...
		<a href="{$urlsite}langues-africaines-c302">(Altro)</a>
  </td>
  <td>
    <h3><img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}transports-c285">Trasporti</a></h3>
    <a href="{$urlsite}aeronautique-c4">Aeronautica</a>, <a href="{$urlsite}automobile-c31">Automobili</a>, <a href="{$urlsite}marine-c184">Marina</a>...
		<a href="{$urlsite}transports-c285">(Altro)</a>
  </td>
  </tr>
  </table>
  
{*
  {section name=categorie loop=$categorie}
  	 <h3><a href="{$urlsite}catalogue-{$categorie[categorie].id}-a.html">{$categorie[categorie].nom|utf8_encode}</a></h3>
      {section name=subcateg loop=$subcateg}
        {if $subcateg[subcateg].parent == $categorie[categorie].id}
    	   <h4><a href="{$urlsite}catalogue-{$subcateg[subcateg].id}-a.html">{$subcateg[subcateg].nom|utf8_encode}</a></h4>
        {/if}
      {/section}
  {/section}
*}
</div>

<div id="catalogue">
{if $show_liste_editeurs_dans_catalogue}
<h2><span class="deco">></span>Catalogo dal redattore</h2><br />
    <table>
      {section name=tab_editeurs loop=$tab_editeurs}
		  <tr>
		  <td>
       <img src="{$urlsite}css/puce2.gif" alt="" /> <a href="{$urlsite}catalogue-editeur-{$tab_editeurs[tab_editeurs].nom}-e{$tab_editeurs[tab_editeurs].id_editeur|urlencode}"><b>{$tab_editeurs[tab_editeurs].nom|utf8_encode|strtolower|ucwords}</b></a>
       {if $show_details == "1"} ({$tab_editeurs[tab_editeurs].nbr_online_products} produits){/if}
       <br />
      </td>  
			</tr>
      {/section}
    </table>
{/if}
{/if}
</div>