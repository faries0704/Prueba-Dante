
    $('#buscar').click(function(){
        dni=$('#documento').val();
        $.ajax({
           url:'consultadni.php',
           type:'post',
           data: 'dni='+dni,
           dataType:'json',
           success:function(r){
            if(r.numeroDocumento==dni){
                $('#nombres').val(r.nombres);
                $('#apellidoPaterno').val(r.apellidoPaterno);
              
             
            }else{
                alert(r.error);
            }
            console.log(r)
           }
        });
    });
