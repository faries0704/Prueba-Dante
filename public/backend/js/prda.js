$(function(){

	// Lista de docente
	$.post( '../../frontend/functions/peri.php' ).done( function(respuesta)
	{
		$( '#prd' ).html( respuesta );
	});
	
	
	// Lista de Ciudades
	$( '#prd' ).change( function()
	{
		var el_continente = $(this).val();
		// Lista de Paises
		$.post( '../../frontend/functions/secgra.php', { continente: el_continente} ).done( function( respuesta )
		{
			$( '#grd' ).html( respuesta );
		});

	});

// Lista de Ciudades
	$( '#grd' ).change( function()
	{
		var pais = $(this).val();
		
		$.post( '../../frontend/functions/secsub.php', { paises: pais} ).done( function( respuesta )
		{
			$( '#sub' ).html( respuesta );
		});	
		
	});


	// Lista de Ciudades




	// Lista de Ciudades
	$( '#sub' ).change( function()
	{
		var curso = $(this).val();
		
		$.post( '../../frontend/functions/seccurs.php', { cursos: curso} ).done( function( respuesta )
		{
			$( '#curso' ).html( respuesta );
		});	
		
	});

	

})
