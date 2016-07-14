<?php
	include ($_SERVER['DOCUMENT_ROOT'] . '/control/seguridad.php');
?>
<link rel="stylesheet" href="/lib/dataTable/css/jquery.dataTables.css">
<script type="text/javascript" src="/lib/dataTable/jquery.dataTables.min.js"></script>



<div style="width:950px;margin:auto;"id='tabla'>   
    <table id="t_proyectos" class="hover">
        <thead>
            <tr>  
                <th width="200">id</th>
				<th width="30">Classified</th>
                <th width="200">Name</th>
                <th  width="100">Country</th>
                <th  width="100">City</th>
                <th  width="100">Execution date</th>				
				<th  width="30"></th>
			</tr>
		</thead>
		
        <tfoot>
            <tr>
                <th width="200">id</th>
				<th width="30">Classified</th>
                <th width="200">Name</th>
                <th  width="100">Country</th>
                <th  width="100">City</th>
                <th  width="100">Execution date</th>
				
				<th  width="30"></th>
			</tr>
		</tfoot>
	</table>
</div>
<style>
	#t_proyectos tr td:nth-child(1),#t_proyectos tr th:nth-child(1){display:none !important;}
</style>
<script>
    $(document).ready(function() {
        $('#t_proyectos').dataTable({
			/*"language": {
				"lengthMenu": "Ver _MENU_ registros por página",
				"zeroRecords": "No se han encontrado datos",
				"info": "Mostrando página _PAGE_ de _PAGES_",
				"infoEmpty": "No records available",
				"infoFiltered": "",
				"search":         "Búsqueda:",
				"paginate": {
					"next": "siguiente",
					"previous": "anterior",
				}
			},*/
			"order": [[ 4, "desc" ]],
            "processing": true,
            "ajax": {
                "url": "/secciones/proyectos/modulos/ver/consulta_proyectos.php"
			},
			
		});
		
        $('#t_proyectos').on('click', 'tr', function() {
            var td = $($(this).children('td')[0]).text();
            if (td != 'id' && td != '') {
                window.location.href = "/abrir/" + td;
			}
		});
		
		$('#t_proyectos').on('click', '[name^=elimina-]', function(e) {
			e.preventDefault();e.stopPropagation();
			
			$('[name=pel]').val( $(this).attr('name').split('-')[1] );
			$('[name=open_eliminar]').trigger('click');
			
		});
		
		
		$('#elimina_modal').on('click', '[name^=elimina_proyecto]', function(e) {
			var elimina = $('[name=pel]').val();
			$('[name=pel]').val('');
			$.ajax({
				type: 'POST', url: '/secciones/proyectos/modulos/ver/elimina.php',
				data: 'elimina=' +elimina,
				dataType: "HTML", 
				success: function() {
					$('#elimina_modal').modal('hide');
					window.location.reload();
				}
			});
		});
		
	});
	
	
</script>

<!-- Button trigger modal -->
<button style="display:none;" name="open_eliminar" type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#elimina_modal">
	
</button>

<!-- Modal -->
<div class="modal fade" id="elimina_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Delete project</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" name="pel" value="" />
				Are you sure you want to delete this project?
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
				<button type="button" class="btn btn-primary" name="elimina_proyecto">Yes</button>
			</div>
		</div>
	</div>
</div>			