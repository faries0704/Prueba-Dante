<!-- add Modal HTML -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="material-icons">person</i>REGISTRO DEL ALUMNOS

                        </h5>
                        
                    </div>
                    <div class="modal-body">
                <div class="col-sm-12" style="display:none;">
                   <div class="form-group">
                      <label class="control-label">Nombre de la seccion</label>
                        <input type="text" value="<?php echo $obj->idsec;?>" name="idsec" required="" class="form-control">
                    </div>
                </div>  

<div class="col-sm-12" >
    <?php
   
 $sentencia = $connect->prepare("SELECT * FROM students GROUP BY idstu DESC;");
 $sentencia->execute();

$data =  array();
if($sentencia){
  while($r = $sentencia->fetchObject()){
    $data[] = $r;
  }
}
   ?>
   <?php if(count($data)>0):?> 
    <table class="table table-hover" id="example">
      <thead class="text-primary">
         <tr>
             <th>FOTO</th>
             <th>DNI</th>
             <th>NOMBRE</th>
             <th>ACCIONES</th>
         </tr> 
      </thead>
      <tbody>
        <?php foreach($data as $g):?>
          <tr>
              <td><img src="../../backend/img/subidas/<?php echo $g->foto ?>" width='50'></td>
              <td><?php echo  $g->dnist; ?></td>
              <td><?php echo  $g->nomstu; ?></td>
              <td>
                  <form enctype="multipart/form-data" method="POST"  autocomplete="off">
                      <input type="hidden" name="txtidsec" value="<?php echo $obj->idsec;?>">
                      <input type="hidden" name="txtidalum" value="<?php echo $g->idstu;?>">

                      <button  name='staddstalu_sc' class="btn btn-success">AÑADIR</button>
                  </form>
              </td>
          </tr>
          <?php endforeach; ?>
      </tbody>  
    </table>
 <?php else:?>
        
<div class="alert alert-warning" style="position: relative;
    margin-top: 14px;
    margin-bottom: 0px;">
            <strong>No hay datos!</strong>
        </div>
    <?php endif; ?>
</div>

                    </div>
                    
                </div>
            
        </div>
    </div>