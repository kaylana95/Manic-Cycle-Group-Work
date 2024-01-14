<?php 
require_once('../class/Quote.php'); 
require_once('../class/Client.php'); 



?>
<div class="modal fade" id="modal-update-quote">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Quote</h4>
			</div>
			<div class="modal-body">
				<!-- main form -->
					<form class="form-horizontal" role="form" id="update-item-form">
					<input type="hidden" id="iID">
					  <div class="form-group">
					    <label class="control-label col-sm-3" for="itemName-update">Quote ID:</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="itemName-update" placeholder="Enter Item Name" autofocus>
					    </div>
					  </div>

					  <div class="form-group">
					    <label class="control-label col-sm-3" for="serialNumber-update">Client Name.:</label>
					    <div class="col-sm-9"> 
					      <input type="text" class="form-control" id="serialNumber-update" placeholder="Enter Client ">
					    </div>
					  </div>

					   <div class="form-group">
					    <label class="control-label col-sm-3" for="modelNumber-update">Description.:</label>
					    <div class="col-sm-9"> 
					      <input type="text" class="form-control" id="modelNumber-update" placeholder="Enter Model No">
					    </div>
					  </div>
					

					  <div class="form-group">
					    <label class="control-label col-sm-3" for="amount-update">Amount:</label>
					    <div class="col-sm-9"> 
					      <input type="number" step="any"  class="form-control" id="amount-update" placeholder="Enter Amount">
					    </div>
					  </div>		
	
	


					  <div class="form-group"> 
					    <div class="col-sm-offset-2 col-sm-10">
					      <button type="submit" id="btn-update-submit" class="btn btn-primary">Save
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
