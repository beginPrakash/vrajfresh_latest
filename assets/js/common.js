function showProgress(progressbar_id) {
    var spinnerVisible = false;
    if (!spinnerVisible) {
        $(progressbar_id).fadeIn("fast");
        spinnerVisible = true;
    }
};

function hideProgress(progressbar_id) {
    var spinnerVisible = true;
    if (spinnerVisible) {
        var spinner = $(progressbar_id);
        spinner.stop();
        spinner.fadeOut("fast");
        spinnerVisible = false;
    }
}

function setCookie(cname, cvalue) {
	const d = new Date();
	var exdays = 100; 
	d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	let expires = "expires=" + d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function get_user_address(user_id) {
	var json_request = {

		"oauth_key": "F1CEC5YC4rrNhTzkP4aNR4Td3XAzCcHAWM4Eh1iDoofbl6xT",
		"user_id": user_id,
		"user_role_id": 4

	};

	$.ajax({
		"type": "POST",
		"url": api_url_prefix + 'get-user-address',
		"data": JSON.stringify(json_request),
		"dataType": "JSON",
		"success": function (response) {
			console.log(response);
			
			if (response.data != "" && response.data != null) {

				setCookie('address', response.data[0].address);
				setCookie('shipping_apartment', response.data[0].shipping_apartment);
				setCookie('address2', response.data[0].address2);
				setCookie('city', response.data[0].city);
				setCookie('zip', response.data[0].zip);
				setCookie('phone', response.data[0].phone);
				setCookie('state', response.data[0].state);


				setCookie('shipping_street_address', response.data[0].shipping_street_address);
				setCookie('shipping_apartment', response.data[0].shipping_apartment);
				setCookie('shipping_city', response.data[0].shipping_city);
				setCookie('shipping_state', response.data[0].shipping_state);
				setCookie('shipping_zip_code', response.data[0].shipping_zip_code);
				setCookie('shipping_phone', response.data[0].shipping_phone);
			}

			//  $("#section-content").html(response.data[0].cms_description);

		},
		"error": function (response) {
			console.log(response.errors);
		}
	});
}