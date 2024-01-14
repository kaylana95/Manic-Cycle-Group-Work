
var valid = true;
var action = '';

/*
	*naa ni siya add_item_modal.php
	*form ni siya kung mag add og item
*/

//fill the changepassword form
$(document).ready(function() {
	$.ajax({
			url: '../data/get_admin_data.php',
			type: 'post',
			dataType: 'json',
			success: function (data) {
				if(data.valid == valid){
					// console.log(data);
					$('#admin-account').append('<span class="glyphicon glyphicon-user" aria-hidden="true"></span> '+
								data.logged+
								'<span class="caret"></span>');
					$('#change-username').val(data.logged_un);
				}else{
					alert('Account is Invalid!');
				}
			},
			error: function(){
				alert('Error: L11+');
			}
		});
	
});
                       

$(document).on('submit', '#add-item-form', function(event) {
	event.preventDefault();
	/* Act on the event */
	var validate = '';
	var form_data = new Array(
								$('input[id=itemname]'),
								$('input[id=serialNumber]'),
								$('input[id=modelNumber]'),
								$('input[id=brand]'),
								$('input[id=amount]'),
								$('input[id=purDate]'),
								$('#empID'),
								$('#catID'),
								$('#conID')
							);
	
	var data = new Array(form_data.length);

	for(var i = 0; i < form_data.length; i++){
		if(form_data[i].val().length == 0){
			form_data[i].parent().parent().addClass('has-error');
		}else{
			form_data[i].parent().parent().removeClass('has-error');
			data[i] = form_data[i].val();
			validate += i;
		}
	}

	if(validate == '012345678'){
		$.ajax({
			url: '../data/addItem.php',
			type: 'post',
			dataType: 'json',
			data: {
				data: JSON.stringify(data)
			},
			success: function(event){
				if(event.valid == valid){
					$('#modal-add-item').modal('hide');
					$('#add-item-form').trigger('reset');
					$('#modal-message-box').modal('show');
					$('#modal-message-box').find('.modal-body').text(event.msg);
					action = event.action;
					show_all_item();
				}
			},
			error: function(){
				alert('Error: L57+ add item');
			}
		});//end ajax
	}//end validate
});//submit #add-item-form

//display all item
function show_all_item()
{
	$.ajax({
		url: '../data/show_all_item.php',
		type: 'post',
		async: false,
		success: function(event){
			$('#allItem').html(event);
		},
		error: function(){
			alert('Error: show all item L100+');
		}
	});

	
}

show_all_item();



/*kung e lick ang table nga row sa item table*/
function item_profile(iID)
{
	$('#modal-item-profile').modal('show');
	$.ajax({
		url: '../data/item_profile.php',
		dataType: 'json',
		type: 'post',
		data: {
			iID: iID
		},
		success: function(event){
			// console.log(event);
			$('.item-name').val(event.item_name);
			$('.item-brand').val(event.item_brand);
			$('.item-serial').val(event.item_serno);
			$('.item-model').val(event.item_modno);
			$('.item-amount').val(Number(event.item_amount).toLocaleString('en'));
			$('.item-purchased').val(event.item_purdate);
			$('.item-owner').val(event.emp_fname+' '+event.emp_mname+' '+event.emp_lname);
			$('.item-category').val(event.cat_desc);
			$('.item-condition').val(event.con_desc);
		},
		error: function(){
			alert('Error: item_profile L136+');
		}
	});
}//end function item_profile

/*
*e fill ang update modal
*/
function fill_update_modal(iID){
	}

// e update ang data nga naa sa modal kung e click ang save nga button
$(document).on('submit', '#update-quote-form', function(event) {
	event.preventDefault();
	/* Act on the event */
	var validate = '';
	var form_data = new Array(
								$('input[id=itemName-update]'), 
								$('input[id=serialNumber-update]'), 
								$('input[id=modelNumber-update]'), 
								$('input[id=brand-update]'), 
								$('input[id=amount-update]'), 
								$('input[id=purDate-update]'),
								$('#empID-update'),
								$('#catID-update'),
								$('#conID-update'),
								$('#iID')
							);

	var data = new Array(form_data.length);
	for(var i = 0; i < form_data.length; i++){
		if(form_data[i].val().length == 0){
			form_data[i].parent().parent().addClass('has-error');
		}else{
			form_data[i].parent().parent().removeClass('has-error');
			data[i] = form_data[i].val();
			validate += i;
		}
	}


	if(validate == '0123456789'){
		$.ajax({
				url: '../data/update_quote.php',
				type: 'post',
				dataType: 'json',
				data: {
					data: JSON.stringify(data)
				},
				success: function (data) {
					if(data.valid == valid){
						$('#modal-update-item').modal('hide');
						$('#modal-message-box').find('.modal-body').text(data.msg);
						$('#modal-message-box').modal('show');
						show_all_item();
					}
				},
				error: function (){
					alert('Error: update quote L250+');
				}
			});
	}//end valdidate
});

/*
*user logic begin here
*/
//clear inputs of add employeee if update used it
// for reusable used of modal we need this line of code to
//clean the inputs after update
$('#add-client-menu').click(function(event) {
	/* Act on the event */
	$('#add-client-form').trigger('reset');
});
$(document).on('submit', '#add-client-form', function(event) {
	event.preventDefault();
	/* Act on the event */
	var validate = '';
	var form_data = new Array(
								$('input[id=fN]'), 
								$('input[id=mN]'), 
								$('input[id=lN]'),
								$('#position'),
								$('#office'),
								$('#type')
							);
	var data = new Array(form_data.length);

	for(var i = 0; i < form_data.length; i++){
		if(form_data[i].val().length == 0){
			form_data[i].parent().parent().addClass('has-error');
		}else{
			form_data[i].parent().parent().removeClass('has-error');
			data[i] = form_data[i].val();
			validate += i;
		}
	}

	if(validate == '012345'){
		$.ajax({
				url: '../data/add_quote.php',
				type: 'post',
				dataType: 'json',
				data: { data: JSON.stringify(data) },
				success: function (response) {
					if(response.valid == valid){
						$('#modal-add-client').modal('hide');
						$('#modal-message-box').find('.modal-body').text(response.msg);
						$('#modal-message-box').modal('show');
						$('#add-employee-form').trigger('reset');
						 // window.location="../admin/client.php";
						show_all_employee();

					}
				},
				error: function (){
					alert('Error: L235+');
				}
			});
	}
	

});


/*
*show all employee function
*and display on the table
*/
function show_all_client()
{
	$.ajax({
			url: '../data/show_all_client.php',
			type: 'post',
			async: false,
			success: function (data) {
				$('#all_client').html(data);
			},
			error: function (){
				alert('Error: L266+ show_all_client');
			}
		});
}
show_all_client();

//client remove or undo 
var remove_undo_choice;
var clientID;//client id
function client_remove_undo(choice, clientID){
	$('#modal-client-remove-undo').modal('show');
	remove_undo_choice = choice;
	clientid = id;
}

$('#remove_undo').click(function(event) {
	//this event trigered when confirmed button is clicked
	var clientID_at_serviceman;
	if(remove_undo_choice == 'remove'){
		// emp_at_deped = false;
		clientID_at_serviceman = 0;
	}else{
		// emp_at_deped = true;
		emp_at_deped = 1;
	}
	$.ajax({
			url: '../data/client_remove_undo.php',
			type: 'post',
			dataType: 'json',
			data: {
				clientID_at_servicemanagement	: clientID_at_serviceman,
				clientID 			: clientID
			},
			success: function (data) {
				// console.log(data);
				$('#modal-client-remove-undo').modal('hide');
				show_all_employee();
			},
			error: function(){
				alert('Error: L294+ #remove_undo');
			}
		});
});


/*add position logic*/
$(document).on('submit', '#add-position-form', function(event) {
	event.preventDefault();
	/* Act on the event */
	var pos = $('input[id=position]');
 	if(pos.val() == ''){
 		pos.parent().parent().addClass('has-error');
 	}else{
 		pos.parent().parent().removeClass('has-error');
 		$.ajax({
 				url: '../data/add_position.php',
 				type: 'post',
 				dataType: 'json',
 				data: { 
 					pos: pos.val()
 				},
 				success: function (data) {
 					if(data.valid = valid){
 						$('#modal-add-position').modal('hide');
 						$('#modal-message-box').find('.modal-body').text(data.msg);
 						$('#modal-message-box').modal('show');
 						$('#add-position-form').trigger('reset');
 					}//end if
 				},
 				error: function(){
 					alert('Error: L328+ submit #add-position-form');
 				}
 			});
 	}



});	
/*end add position logic*/

/*add new office logic here*/
$(document).on('submit', '#add-office-form', function(event) {
	event.preventDefault();
	/* Act on the event */
	var office = $('input[id=office]');
	if(office.val() == ''){
		office.parent().parent().addClass('has-error');
	}else{
		office.parent().parent().removeClass('has-error');
		$.ajax({
				url: '../data/add_office.php',
				type: 'post',
				dataType: 'json',
				data: {
					off : office.val()
				},
				success: function (data) {
					if(data.valid == valid){
						$('#modal-add-office').modal('hide');
						$('#modal-message-box').find('.modal-body').text(data.msg);
 						$('#modal-message-box').modal('show');
 						$('#add-position-form').trigger('reset');
					}
				},
				error: function(){
					alert('Error: L366+ on submit #add-office-form');
				}
			});
	}
});
/*end add new office logic here*/


/*view employee 1 by 1*/
function employee_profile(eid){
	var at_deped;
	$('#modal-client-profile').modal('show');
	$.ajax({
			url: '../data/client_profile.php',
			type: 'post',
			dataType: 'json',
			data: {
				eid : eid
			},
			success: function (data) {
				console.log(data);
				if(data){
					$
                    //still neeed coding
				}
			},
			error: function(){
				alert('Error: L389+ client_profile');
			}
		});
}

function edit_client_fill(eid){
	$('#modal-update-client').modal('show');
	$('#modal-update-client').find('.modal-title').text('Update Client');

	//get client data using ajax and fill the modal 
	$.ajax({
			url: '../data/client_profile.php',
			type: 'post',
			dataType: 'json',
			data: {
				clientID : clientID
			},
			success: function (data) {
				if(data){
					$();
                    //not yet done

				}
			},
			error: function(){
				alert('Error: L434+ update client');
			}
		});
}

$(document).on('submit', '#update-client-form', function(event) {
	event.preventDefault();
	/* Act on the event */
	var validate = '';
	var form_data = new Array(
									$('#update-fN'),	
									$('#update-eD'),	
									$('#update-pN'),	
									$('#update-cW'),	
									$('#update-rN'),	
									$('#update-eD'),
									$('#update-rN')
								);

	var data = new Array(form_data.length);
	for(var i = 0; i < form_data.length; i++){
		if(form_data[i].val() == 0){
			form_data[i].parent().parent().addClass('has-error');
		}else{
			form_data[i].parent().parent().removeClass('has-error');
			data[i] = form_data[i].val();
			validate += i;
		}
	}


	if(validate == "0123456"){
		$.ajax({
				url: '../data/update_client.php',
				type: 'post',
				dataType: 'json',
				data: {
					data : JSON.stringify(data)
				},
				success: function (data) {
					if(data.valid == valid){
						$('#modal-update-client').modal('hide');
						show_all_employee();
						$('#"modal-message-box').find('.modal-body').text(data.msg);
					}
				},
				error: function(){
					alert('Error: L485+ update_client');
				}
			});
	}

});



//all request to admin
function all_request_to_admin()
{
	$.ajax({
			url: '../data/all_request_to_admin.php',
			type: 'post',
			success: function (data) {
				// console.log(data);
				$('#request-to-admin').html(data);
			},
			error: function(){
				alert('Error: L510+ all req to admin');
			}
		});
}
all_request_to_admin();

//request_action
var action = '';
var request_id = '';
var item_id = '';
var req_type = '';


$('#confirm-action').click(function(event) {
	/* Act on the event */
	$.ajax({
			url: '../data/admin_request_action.php',
			type: 'post',
			// dataType: 'json',
			data: {
	
		});
});
//end all request to admin


//changepass logic
$(document).on('submit', '#form-changepassword', function(event) {
	event.preventDefault();
	/* Act on the event */
	var validate = '';
	var form_att = new Array(
							$('input[id=change-username]'),
							$('input[id=change-password]'),
							$('input[id=confirm-password]')
						);
	var data = new Array(form_att.length);
	for(var i = 0; i < data.length; i ++){
		if(form_att[i].val() == 0){
			form_att[i].parent().parent().addClass('has-error');
		}else{
			form_att[i].parent().parent().removeClass('has-error');
			validate += i;
			data[i] = form_att[i].val();
		}
	}//end for
	//check if pass match
	if(validate == '012'){
		if($('input[id=change-password]').val() == $('input[id=confirm-password]').val()){
			// console.log('equal');
			$.ajax({
					url: '../data/update_admin_data.php',
					type: 'post',
					dataType: 'json',
					data: {
						data: JSON.stringify(data)
					},
					success: function (data) {
						// console.log(data);
						if(data.valid == valid){
							//if success
							$('#modal-changepass').modal('hide');
							$('#modal-message-box').modal('show');
							$('#modal-message-box').find('.modal-body').text(data.msg);
						}else{
							alert('Opps: Somethings went wrong! check db!');
						}
					},
					error: function(){
						alert('Error: L612+');
					}
				});
		}else{
			// console.log('not equal');
			alert('Password Not Matched!');
		}
	}
});



function show_report(){
	$.ajax({
			url: '../data/show_report.php',
			type: 'post',
			data: {
				choice: 'all'
			},
			success: function (data) {
				$('#show-report').html(data);
			},
			error: function(){
				alert('Error: L825+');
			}
		});
}//end function show_report
show_report();

$('#print-btn').click(function(event) {
	/* Act on the event */
	var choice = $('#report-choice').val();
	window.open('../data/print.php?choice='+choice,'name','width=600,height=400');
});
