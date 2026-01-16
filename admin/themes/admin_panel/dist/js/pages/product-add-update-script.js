function valdiateform() {
    if (parseFloat($('#sale_price').val()) > parseFloat($('#product_price').val())) {
        alert('Please enter valid sale price, sale price must be less than MRP.');

        $("#product_price").val('');
        $("#sale_price").val('');
        return false;
    }
    return true;
}

$(document).ready(function() {
	var product_associate_to_id = $("#product_associate_to_ids").val();
	var selected_category_id = $("#selected_category_id").val();
	if(selected_category_id!=""){
		var cat = selected_category_id+","
		$("#selected_category_id").val(cat.split(','));
	}
	if(product_associate_to_id!='') {
		var associate_to_id = product_associate_to_id+","
		$("#product_associate_to_id").val(associate_to_id.split(','));
	}
	$('#category_id').multiselect({
		columns: 1,
		placeholder: '-- Select Category --',
		selectAll: true,
	});
	$('#product_associate_to_id').multiselect({
		columns: 1,
		placeholder: '-- Select Product --',
		selectAll: true,
	});
    $("#product_images").on('click', '.remove-button', function(e) {
        var rowCount = $('#product_images tr').length;
        if (rowCount <= 1) {
            alert('You can not remove all the images');
        } else {
            var whichtr = $(this).closest("tr");
            whichtr.remove();
        }
	});

	var prod_id = $('#prod_id').val();
    

    $("#product_form").validate({
		rules: {
			product_sku: {
				required: true
			},
			product_name: {
				required: true
			},
            product_price: {
                required: true,
                number: true
            },
            sale_price: {
                required: true,
                number: true
            },
            category_id: {
                required: true,
                number: true
			},
		},
		messages: {
			product_sku: {
				required: "The field is required"
			},
			product_name: {
				required: "The field is required"
			},
            product_price: {
                required: "The Field is required",
                number : "Please add number only"
            },
            sale_price: {
                required: "The Field is required",
                number : "Please add number only"
            },
            category_id: {
                required: "The Field is required",
            },
			
		},
		submitHandler: function(form) { form.submit(); }
	});
    $("#submit").click(function(){
        var category_id= $("#category_id").val();
		$("#category_iderror").text('');
		if(category_id == null){
			$("#category_iderror").text('This Field is required');
			return false;
		}
		
	});
});


function convertToSlug()
{
	var Text = $("#product_name").val().trim();
	var urlval = Text.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
	$("#product_slug").val(urlval);
}