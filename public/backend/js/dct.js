$(function(){

    // Lista de docente
    $.post( '../../frontend/functions/dnt.php' ).done( function(respuesta)
    {
        $( '#docn' ).html( respuesta );
    });
    
    
    // Lista de Ciudades
    $( '#docn' ).change( function()
    {
        var el_continente = $(this).val();

    });


    
    
    

})
