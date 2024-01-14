<?php 
require_once('../class/Client.php'); 



?>
<div class="modal fade" id="modal-add-client">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Client</h4>
			</div>
			<div class="modal-body">
				<!-- main form -->
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<strong>System:</strong>
					<span id="system-msg-add-emp">Default Username and Password is your firstname_lastname</span>
				</div>
					<form class="form-horizontal" role="form" id="add-client-form">

					  <div class="form-group">
					    <label class="control-label col-sm-3" for="fN">First Name:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="fN" placeholder="Enter First Name" autofocus>
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="control-label col-sm-3" for="lN">Last Name:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="mN" placeholder="Enter Last Name">
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="control-label col-sm-3" for="pN">Phone Number:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="pN" placeholder="Enter Phone Number">
					    </div>
					  </div>

					  
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="eD">Email Address:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="eD" placeholder="Enter Email Address">
					    </div>
					  </div>

					  
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="cW">Client Weight:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="cW" placeholder="Enter Client Weight">
					    </div>
					  </div>
					  
					  
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="eN">Event:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="eN" placeholder="Enter Event">
					    </div>
					  </div>
					  
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="rN">Race Number:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="rN" placeholder="Enter Race Number">
					    </div>
					  </div>


					  <div class="form-group"> 
					    <div class="col-sm-offset-2 col-sm-10">
					      <button type="submit" class="btn btn-primary" value="addEmployee">Save
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
