<div class="modal fade" id="modal-add-quote">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">New Quote Seivices</h4>
			</div>
			<div class="modal-body">
				<!-- main form -->
					<form class="form-horizontal" role="form" id="add-quote-form">
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="fN">Quote Services ID:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="qS" placeholder="Enter Quote Services ID" autofocus>
					    </div>
					  </div>
					   <div class="form-group">
					    <label class="control-label col-sm-3" for="purDate">Produced On:</label>
					    <div class="col-sm-9"> 
					      <input type="date" class="form-control" id="purDate" >
					    </div>
					  </div>	

					  <div class="form-group" id="sr">
					    <label class="control-label col-sm-3" for="serialNumber">Produced At:</label>
					    <div class="col-sm-9"> 
					      <input type="time" class="form-control" id="serialNumber">
					    </div>
					  </div>

                        <div class="form-group">
					    <label class="control-label col-sm-3" for="empID">Client:</label>
					    <div class="col-sm-9"> 
					    	<select class="btn btn-default" id="empID">
					    		
								<?php 
								
								 ?>					    		
					    	</select>
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="control-label col-sm-3" for="lN">Service Description:</label>
					    <div class="col-sm-9">
					    <textarea id="comments" class="form-control" name="comments" rows="10"></textarea>
					    </div>
					  </div>
                      <div class="form-group">
					    <label class="control-label col-sm-3" for="lN">Estimated Price:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="eP" placeholder="Enter Amount">
					    </div>
					  </div>
                       <div class="form-group">
					    <label class="control-label col-sm-3" for="lN">Quote ID:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="qD" placeholder="Enter Quote ID">
					    </div>
					  </div>
					      <br />
					      <button type="submit" class="btn btn-primary">Save
					      <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
					      </button>
					    
					  
					</form>
				<!-- /main form -->
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>