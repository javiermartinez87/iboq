<?php
	include ($_SERVER['DOCUMENT_ROOT'].'/control/seguridad.php');
?>
<style>
	.t_app{
	color:#3F51B5;
	width: 100%;
	text-align:center;
	}
	
	.info-app{
	text-align: justify;
	}
</style>

<div class="row">
	<div class="col-sm-12">
		<h2 class="t_app">i-BoQ</h2>
		<h3 class="t_app">Intelligent System for the Acquisition and Management of information from Bill of Quantities in Building Projects</h3>
		<p>
			
		</p>
	</div>
</div>

<div class="row">
	<div class="col-sm-4 info-app">
		<h4>Adquisition</h4>
		<p>
			The data acquisition module allows the acquisition and classification of the information contained in the BoQ document regardless of the tool used to elaborate the document and its own structure and linguistic description.
			
		</p>
	</div>
	<div class="col-sm-4 info-app">
		<h4>Edition</h4>
		<p>
			The data edition module allow the edition of the obtained results after the classification proccess in order to locate each work description in their corresponding chapter and subchapter.
		</p>
	</div>
	<div class="col-sm-4 info-app">
		<h4>Retrieval</h4>
		<p>
			The data retrieval module allow to access to stored data in the reference structure in an integrated way in order to support decision making.
		</p>
	</div>
	
	<div class="col-sm-12">
		<div class="col-sm-4">
			<div class="botoncenter"><input type="button" value="Go" onclick="window.location.href='/proyectos/nuevo'"/></div>
		</div>
		<div class="col-sm-4">
			<div class="botoncenter"><input type="button" value="Go" onclick="window.location.href='/proyectos/ver'"/></div>
		</div>
		<div class="col-sm-4">		
			<div class="botoncenter"><input type="button" value="Go" onclick="window.location.href='/proyectos/busqueda'"/></div>
		</div>
	</div>
</div>

