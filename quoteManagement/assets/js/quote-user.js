/*
*sdfsd
*/
var valid = true;

//fill the changepassword form
$(document).ready(function() {
	$.ajax({
			url: '../data/get_session_logged_data.php',
			type: 'post',
			dataType: 'json',
			success: function (data) {
				$('#change-username').val(data.user_un);
				$('#user-id').val(data.user_id);
				$('#user-account').html('<span class="glyphicon glyphicon-user"></span> '+
					data.user_fname+' '+data.user_lname+'<span class="caret"></span>');
			},
			error: function (){
				alert('Error: L7+');
			}
		});
});

/*change user password */
$(document).on('submit', '#form-changepassword', function(event) {
	event.preventDefault();
	/* Act on the event */
	var validate = '';
	var form_data = new Array(
									$('#change-username'),	
									$('#change-password'),	
									$('#confirm-password')
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

	if(validate == '012'){
		if(form_data[1].val() != form_data[2].val()){
			$('#changepass-msg').text('Password not match!');
			$('#change-password').parent().parent().addClass('has-error');
			$('#confirm-password').parent().parent().addClass('has-error');
		}else{
			// alert('match');
			$.ajax({
					url: '../data/save_changepassword.php',
					type: 'post',
					dataType: 'json',
					data: {
						uid : $('#user-id').val(),
						un : $('#change-username').val(),
						pwd : $('#confirm-password').val()
					},
					success: function (data) {
						if(data.valid == valid){
							$('#modal-changepass').modal('hide');
							$('#modal-message-box').find('.modal-body').text(data.msg);
							$('#modal-message-box').modal('show');
						}
					},
					error: function (){
						alert('Error: L66+ #form-changepassword');
					}
				});
			//tiger reset form .reminder
		}

	}
});
/*end change user password */



function confirm_done(quote_id){
	$.ajax({
			url: '../data/confirm_done.php',
			type: 'post',
			data: {
				item_id : item_id
			},
			success: function (data) {
				show_all_owners_request();
			},
			error: function(){
				alert('Error: L162+ confirm_done');
			}
		});
}
//end this is to confirm the request in the request list
