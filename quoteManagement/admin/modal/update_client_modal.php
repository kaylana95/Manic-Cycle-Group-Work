<?php 
require_once('../class/Client.php'); 


?>
<div class="modal fade" id="modal-update-client">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<!-- main form -->
					<form class="form-horizontal" role="form" id="update-employee-form">
					<input type="hidden" id="update-eid">
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="update-fN">First Name:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="update-fN" placeholder="Enter First Name" autofocus>
					    </div>
					  </div>

				
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="update-lN">Last Name:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="update-lN" placeholder="Enter Last Name">
					    </div>
					  </div>
					  
					  <div class="form-group">
					      
					  </div>


				     

					  <div class="form-group"> 
					    <div class="col-sm-offset-2 col-sm-10">
					      <button type="submit" class="btn btn-primary" value="addClient">Save
					      <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
					      </button>
					    </div>
					  </div>
					</form>
				<!-- /main form -->
			</div>
		</div>
	</div>
</div>
