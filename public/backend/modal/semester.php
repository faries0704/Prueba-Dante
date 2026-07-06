<!-- add Modal HTML -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog " role="document">
            
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            REGISTRO DE SEMESTRES
                        </h5>
                        
                    </div>
                    <div class="modal-body">
            <form enctype="multipart/form-data" method="POST"  autocomplete="off">
               <div class="row">
                <div class="col-md-6 col-lg-6">
   <div class="form-group">
    <label for="email">SEMESTRES<span class="text-danger">*</span></label>
    <select class="form-control" required name="txtstte">
        <option value="I">I</option>    
        <option value="II">II</option>  
        <option value="III">III</option>                              
    </select>
    <small id="emailHelp" class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
</div>   
  </div>

  <div class="col-md-6 col-lg-6">
   <div class="form-group">
    <label for="email">AÑO<span class="text-danger">*</span></label>
    <?php foreach(explode("__", $obj->period) as $periodConcatenados){ 
                                $period = explode("..", $periodConcatenados)
                                ?>
    <input type="text" value="<?php echo $period[1] ?>" class="form-control"  name="txtnamper" required placeholder="ejm: inicial"> 
    <input type="hidden" name="txtidpe" value="<?php echo $period[0] ?>">    
    <input type="hidden" name="txtidse" value="<?php echo $obj->idsec;?>">                  
                <?php } ?>
    <small id="emailHelp" class="form-text text-muted"><span class="text-danger">Importante rellenar los campos.</span></small>
</div>   
  </div>   
               </div>
               <hr>
<div class="form-group">
        <div class="col-sm-12">
            <button name='staddsemste' class="btn btn-success text-white">Añadir</button>                       
            <a class="btn btn-danger text-white" href="../grado/mostrar.php">Cancelar</a>
        </div>
</div> 
            </form>
                    </div>
                </div>
        </div>
    </div>