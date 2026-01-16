

function validate_addblog(){
	var blogtitle = $("#blogtitle").val().trim();
	var blog_url = $("#blog_url").val().trim();
	var blog_id = $("#blog_id").val().trim();
	if(blog_url !=""){
		var res = false;
		$.ajax({
			type        : "POST",
			url         : $("#check_url").val(),
			data        : "blog_url="+blog_url+"&blog_id="+blog_id,
			async		: false,
			success: function(results) {
				if(results.trim() == 'Yes'){
					res = true;
				}else{
					res = false;
				}
			},
			error: function() {
				toastr.error('Oops...! somthing went wrong please try again!!');
			}
		});
	}
	if(res){
		$('#blog_url').focus();
		$('.alert-danger').hide();
        $("#blog_url").siblings('.alert-danger').show();
        $("#blog_url").siblings('.alert-danger').text('Blog URL already exist! please enter the different blog URL');
		return false;
	}

	if(blogtitle==""){
		$('#blogtitle').focus();
        $('.alert-danger').hide();
        $("#blogtitle").siblings('.alert-danger').show();
        $("#blogtitle").siblings('.alert-danger').text('Please add the blog title');
        return false;
	}else if(blog_url ==""){
		$('#blog_url').focus();
        $('.alert-danger').hide();
        $("#blog_url").siblings('.alert-danger').show();
        $("#blog_url").siblings('.alert-danger').text('Please add the blog URL');
        return false;
	}else{
		return true;
	}
	
}
function convertToSlug()
{
	var Text = $("#blogtitle").val().trim();
	var urlval = Text.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
	$("#blog_url").val(urlval);
}