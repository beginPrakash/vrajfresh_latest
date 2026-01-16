/* Using validation lead and customer edit page */

function isNumber(str)
{
	regexp = /^[0-9]*$/;
	return regexp.test(str);
}
function isNumberComma(str)
{
	regexp = /^[0-9,]*$/;
	return regexp.test(str);
}

function IsPhoneNumber( no, flied )
{
  //var regexp = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
    regexpr = /^([\s\.]?[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]+)+$/;
        
	var special_char = regexpr.test(no);
	
	if(special_char){
	  alert('Only special characters not allowed in '+flied+'.');
	  return false;
	}else {
		var regexp2 = /^([0-9-+() ])+$/;
 
	    var phone = regexp2.test(no);
		if(!phone){
			 alert('Only Numbers, +, dash ( and ) are allowed in '+flied+'.');
			 return false;
		}else {
			 return true;
		}
	}
}

function IsPhoneNumberHtml(no, flied)
{
  //var regexp = /^\+?([0-9]{2})\)?[-. ]?([0-9]{4})[-. ]?([0-9]{4})$/;
   regexpr = /^([\s\.]?[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]+)+$/;
        
	var special_char = regexpr.test(no);
	
	if(special_char){
	  $(flied).html('Only special characters not allowed.').fadeIn('slow').delay(3000).fadeOut('slow');
	  return false;
	}else {
		
        var regexp2 = /^([0-9-+() ])+$/;
	    var phone = regexp2.test(no);
		if(!phone){
			 $(flied).html('Please enter numbers, +, dash ( and ).').fadeIn('slow').delay(3000).fadeOut('slow');
			 return false;
		}else {
			 return true;
		}
	} 
}

function isFloat(no)
{
	regexp = /^-?\d*(\.\d{2,2})|(\.\d{3,4})$/;
	return regexp.test(no);
}

function isAlpha(str)
{
	regexp = /^[a-zA-Z ]*$/;
	return regexp.test(str);
}


function isAlphanumeric(str)
{
	regexp = /^[a-zA-Z0-9 ]*$/;
	return regexp.test(str);
}

function isAlphanumericDot(str)
{
	regexp = /^([a-zA-Z]{1,})+([0-9]{0,})+([.]{0,1}?)*$/;
	return regexp.test(str);
}

function isBothAlphaNumber(str, flied)
{
	regexpr2 = /^[0-9]*$/;
	var number = regexpr2.test(str);
	
	if(number){
		alert('Only numeric value not allowed in '+flied+' field.');
		return false;
	} else {
		regexp = /^[a-zA-Z0-9 ]*$/;
		
		var alphaNumber = regexp.test(str);
		if(alphaNumber){
			return true;
		}else{
			alert('Only alphabets and numbers are allowed in '+flied+'.');
		}
	}
}
 
function isBothAlphaNumberHtml(str, flied)
{
	regexpr2 = /^[0-9]*$/;
	var number = regexpr2.test(str);
	
	if(number){
		$(flied).html('Only numbers not allowed.').fadeIn('slow').delay(3000).fadeOut('slow');
		return false;
	} else {
		regexp = /^[a-zA-Z0-9 ]*$/;
		
		var alphaNumber = regexp.test(str);
		if(alphaNumber){
			return true;
		}else{
			$(flied).html('Please enter alphabets and numbers.').fadeIn('slow').delay(3000).fadeOut('slow');
		}
	}
}

function isAlphanumericDashAndComma(str)
{
	regexp = /^[a-zA-Z0-9-, ]*$/;
	return regexp.test(str);
}

function isTitleHtml( str, flied )
{
	regexpr2 = /^[0-9]*$/;
	var number = regexpr2.test(str);
	
	if(number){
		$(flied).html('Only numeric value not allowed in title field.').fadeIn('slow').delay(3000).fadeOut('slow');
		return false;
	} else {
		regexp = /^[a-zA-Z0-9. ]*$/;
		var title = regexp.test(str);
		if(title){
			return true;
		}else{
			$(flied).html('Please enter only alphabets, numbers, and dots.').fadeIn('slow').delay(3000).fadeOut('slow');
			return false;
		}
		
	}
}

function isAddress(str)
{
	regexp = /^[a-zA-Z0-9-,. ]*$/;
	return regexp.test(str);
}

function isDateTime(str)
{
	regexp = /^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01]) (00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$/g;
	return regexp.test(str);
}


function isName(str)
{
	regexp = /^[a-zA-Z]+(\s{0,1}[a-zA-Z-'. ])*$/;
	return regexp.test(str);
}


function isWebURL(website,flied)
{
	var website = website.toLowerCase();
	var str = website.substring(0, 4);
	website2 = website;
	
	if( str != 'http' && str != 'www.' ){
		website2 = 'www.'+website;
	}
	$(flied).val(website2);
	//regexp = /(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&=]*)/;
	regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;

	//regexp = /^((https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9\.\-]+\.([a-zA-Z]{2,4})+([a-zA-Z.]{2,4})?)|(www\.[a-zA-Z0-9\-]+\.([a-zA-Z]{2,4})+([a-zA-Z.]{2,4})?))$/;
	//regexp = /^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9\.\-])$/;
	//regexp = /^[a-zA-Z0-9.-\/\/_]+$/i;
	return regexp.test(website2);
	//return true;
}

function isWebURLTooltip(website,flied)
{					
	var str = website.substring(0, 4);
	website2 = website;
	
	if( str != 'http' && str != 'www.' ){
		website2 = 'www.'+website;
	}
	$(flied).val(website2);
	regexp = /(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;
	
	var website_url = regexp.test(website2);
	if(!website_url) {
		$(flied).attr("data-original-title","Please enter a valid URL.");
		$(flied).tooltip('show');
	}
	return website_url;
}

function isLoginName(str)
{
	//regexpr = /^([a-zA-Z0-9._%+-]+@[a-zA-Z0-9]+\.([a-zA-Z]{2,6})+([a-zA-Z.]{2,6})?)$/;
	regexpr = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
	var email = regexpr.test(str);
	if(!email){
		regexpr2 = /^[a-zA-Z0-9]*$/;
		return regexpr2.test(str);
	}
	return regexpr.test(str);
}


function isMixCharacters( str, flied_name )
{
	//regexp = /^([a-zA-Z])|([a-zA-Z0-9 @!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`])*$/;
	
	regexp = /^[a-zA-Z]*$/;
	
	var alpha = regexp.test(str);
	
	if(!alpha){	
		regexpr2 = /^[0-9]*$/;
		var number = regexpr2.test(str);
		
		if(number){
			//alert(flied_name+" numbers not allowed only.");
				alert("Only numeric value not allowed in "+flied_name+" field.");
		}else{
			
			regexpr3 = /^[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]*$/;
			
			var special_char = regexpr3.test(str);
			
			if(special_char){
				alert("Only special characters not allowed in "+flied_name+" field.");
				//alert(flied_name+" field not allow only special characters.");
			}else {
				return true;
			}
		}
	}
	
	return regexp.test(str);
	
}

// this validation using for customer page
function isMixCharactersHTML(str,flied)
{
	//regexp = /^([a-zA-Z])|([a-zA-Z0-9 @!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`])*$/;
	
	regexp = /^[a-zA-Z]*$/;
	
	var alpha = regexp.test(str);
	
	if(!alpha){	
		regexpr2 = /^[0-9]*$/;
		var number = regexpr2.test(str);
		
		if(number){
			$(flied).html('Only numbers not allowed.').fadeIn('slow').delay(3000).fadeOut('slow');
		}else{
			
			regexpr3 = /^[0-9@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]*$/;
			
			var special_char = regexpr3.test(str);
			
			if(special_char){
				$(flied).html('Only special characters not allowed.').fadeIn('slow').delay(3000).fadeOut('slow');
			}else {
				return true;
			}
		}
	}
	
	return regexp.test(str);
	
}

// this validation using for customer page
function isExperience(str,flied)
{
	regexpr2 = /^[0-9]*$/;
	var number = regexpr2.test(str);
	
	if(number){
		return true;
	}else{
		
		regexpr3 = /^[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]*$/;
		
		var special_char = regexpr3.test(str);
		
		if(special_char){
			$(flied).html('Only special characters not allowed.').fadeIn('slow').delay(3000).fadeOut('slow');
		}else {
			return true;
		}
	}
		
	return regexp.test(str);
}

function isAlphanumericDashUnderscore(str)
{
	regexp = /^[a-zA-Z0-9-_ ]*$/;
	return regexp.test(str);
}

/*
function isSocialMediaWebURL(website)
{
	var str = website.substring(0, 4);
	
	if( str != 'www.' && str != 'http' ){
		website = 'www.'+website;
	}
			
	$('#social_media_link').val(website);
	//document.getElementById("contact_website_url").focus();
	
	regexp = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9\.]+\.[a-zA-Z]{2,}|www\.[a-zA-Z0-9]+\.[a-zA-Z]{2,})/;
	
	return regexp.test(website);
} */
function isEmailvalid(email)
{
	regexp = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,15})+$/;
	//regexp = /^([a-zA-Z0-9.]+@[a-zA-Z0-9]+\.([a-zA-Z]{2,6})+([a-zA-Z.]{2,6})?)$/; // as per gmail validation
	return regexp.test(email);
}

function isMI(str)
{
	regexp = /^[a-zA-Z0-9.]*$/;
	
	var name = regexp.test(str);
	
	if(!name){	
		regexpr2 = /^([a-zA-Z0-9.]+@[a-zA-Z0-9]+\.([a-zA-Z]{2,6})+([a-zA-Z.]{2,6})?)$/;
		return regexpr2.test(str);
	}
	return regexp.test(str);
}

jQuery.validator.addMethod("noSpace", function(value, element) { 
	return value.indexOf(" ") < 0 ; 
  }, "No space please and don't leave it empty");

$.validator.addMethod("phone_no", function(value, element) {
    var no = value.trim();
	regexpr = /^([\s\.]?[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]+)+$/;
    if( no == '' ) {
		return true;
	}   
	var special_char = regexpr.test(no);
	
	if(special_char){
	  message =  'Only Special characters not allowed.';
	  return false;
	}else {
		var regexp2 = /^([0-9-+() ])+$/;
 
	    var phone = regexp2.test(no);
		if(!phone){
			 message = 'Only Numbers, +, dash ( and ) are allowed';
			 return false;
		}else {
			 return true;
		}
	}
}, function() { return message; });


$.validator.addMethod("isBothAlphaNumber", function(value, element) {
	var str = value.trim();
	regexpr = /^[0-9]*$/;
	if( str == '' ) {
		return true;
	}
	var number = regexpr.test(str);
	if(number){
	  message = 'Only numbers not allowed.';
	  return false;
	}else{
		regexp2 = /^[a-zA-Z0-9 ]*$/;
	    var alphaNumber = regexp2.test(str);
		if(!alphaNumber){
			 message = 'Only alphabets and numbers are allowed.';
			 return false;
		}else {
			 return true;
		} 
	}
}, function() { return message; });


jQuery.validator.addMethod("trim_value", function(value, element) { 
	return value.trim(); 
}, "This field is required.");

$.validator.addMethod("check_login_name", function(value, element, regexpr) {
	var value = value.trim();
	var email = regexpr.test(value);
	if(!email){
		regexpr2 = /^[a-zA-Z]+(\s{0,1}[a-zA-Z-'. ])*$/;
		return regexpr2.test(value);
	}
	return regexpr.test(value);
}, "Please enter a valid user name.");
/*
$.validator.addMethod("webiste_url", function(value, element) {
	//regexp = /^((https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9\.]+\.([a-zA-Z]{2,4})+([a-zA-Z.]{2,4})?)|(www\.[a-zA-Z0-9]+\.([a-zA-Z]{2,4})+([a-zA-Z.]{2,4})?))$/;
	regexp = /(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/;

	website = value;
	
	//var str = value.substring(0, 3);
	//var str2 = value.substring(0, 4);
	
	//if( str2 != 'http' && str != 'www' ){
	//	website = 'www.'+website;
	//}
	
	//$(element).val(website); 
	return regexp.test(website);
}, "Please enter a valid URL.");
*/
$.validator.addMethod("email_valid", function(value, element) {
	var value = value.trim();
	if( value == '' ) {
		return true;
	}
	regexpr = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,15})+$/;
	return regexpr.test(value);
}, 'Only alphabets, numbers, dots, and dash are allowed.');

$.validator.addMethod("isMixCharacters", function(value, element) {
	var value = value.trim();
	
	regexp = /^[a-zA-Z]*$/;
	
	var alpha = regexp.test(value);
	
	if(!alpha){	
		regexpr2 = /^[0-9]*$/;
		var number = regexpr2.test(value);
		
		if(number){
			message = 'Only numbers not allowed.';
			return false;
		}else{
			
			regexpr3 = /^[@!#\$\^%&*()+=\-\[\]\\\';,\.\/\{\}\|\":<>\?~_`]*$/;
			var special_char = regexpr3.test(value);
			
			if(special_char){
				message = 'Only Special characters not allowed.';
				return false;
			}else {
				return true;
			}
		}
	}
	return regexp.test(value);
}, function() { return message; });

$.validator.addMethod("isAlphanumeric", function(value, element) {
	var value = value.trim();
	regexpr = /^[a-zA-Z0-9 ]*$/;
	return regexpr.test(value);
}, "Only alphabets and numbers are allowed.");

/******************************************** TEXTBOX VALIDATION *****************************************************/
$("input").blur(function()
{
	var temp_val = $(this).val().trim();
	$(this).val(temp_val);
});
function allowOnlyAlphaDot(evt)
{ 
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode == 8 || charCode == 46 || (charCode >= 65 && charCode <=90) || (charCode >= 97 && charCode <=122) ) {return true; } return false;
}
function allowOnlyNumberDot(evt)
{ 
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode == 8|| charCode == 46 || (charCode >= 48 && charCode <=57) ) {return true; } return false;
}
function allowOnlyNumber(evt)
{ 
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode == 8 || (charCode >= 48 && charCode <=57) ) {return true; } return false;
}
function allowOnlyAlpha(evt)
{ 
	var charCode = (evt.which) ? evt.which : evt.keyCode
	if (charCode == 8 || (charCode >= 65 && charCode <=90) || (charCode >= 97 && charCode <=122) ) {return true; } return false;
}
function confirm_delete()
{
	if(confirm('Are you sure you want to permanently delete record?'))
		return true;
	else
		return false;
}