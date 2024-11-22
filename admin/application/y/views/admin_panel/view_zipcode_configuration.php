<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>
.table-wrapper {
    width: 100%;
    background: #fff;
    padding: 20px;	
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.table-title {
    padding-bottom: 10px;
    margin: 0 0 10px;
}
.table-title h2 {
    margin: 6px 0 0;
    font-size: 22px;
}
.table-title .add-new {
    float: right;
    height: 30px;
    font-weight: bold;
    font-size: 12px;
    text-shadow: none;
    min-width: 100px;
    border-radius: 50px;
    line-height: 13px;
}
.table-title .add-new i {
    margin-right: 4px;
}
table.table {
    table-layout: fixed;
}
table.table tr th, table.table tr td {
    border-color: #e9e9e9;
}
table.table th i {
    font-size: 13px;
    margin: 0 5px;
    cursor: pointer;
}
table.table th:last-child {
    width: 100px;
}
table.table td a {
    cursor: pointer;
    display: inline-block;
    margin: 0 5px;
    min-width: 24px;
}    
table.table td a.add {
    color: #27C46B;
}
table.table td a.edit {
    color: #FFC107;
}
table.table td a.delete {
    color: #E34724;
}
table.table td i {
    font-size: 19px;
}
table.table td a.add i {
    font-size: 24px;
    margin-right: -1px;
    position: relative;
    top: 3px;
}    
table.table .form-control {
    height: 32px;
    line-height: 32px;
    box-shadow: none;
    border-radius: 2px;
}
table.table .form-control.error {
    border-color: #f50000;
}
table.table td .add {
    display: none;
}
</style>
<script>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();
	// Append table with add row form on add new button click
    $(".add-new").click(function(){
		
		$("#current_zip_code").val(0);
		$(this).attr("disabled", "disabled");
		var index = $("table tbody tr:last-child").index();
        
		var selOption = '';
		<?php foreach($ArrStateOption as $key=>$state) { ?>
		var is_selected = ''; 
		selOption = selOption + '<option value="<?php echo $state; ?>"'+is_selected+'><?php echo $state; ?></option>';
		<?php } ?>
		var ddData = '<select name="ArrState[]" class="form-control">'+selOption+'</select>';
	
        var ddData1 = '<select name="ArrCanDeliverPerishable[]" class="form-control"><option>Select</option><option value="Yes">Yes</option><option value="No">No</option></select>';
        var ddData2 = '<select name="ArrDeliveryTypes[]" class="form-control" onChange="setDay(this.value,000);"><option>Select</option><option value="Express Delivery">Express Delivery</option><option value="Same Day Delivery">Same Day Delivery</option><option value="Twise in a week">Twise in a week</option></select>';
		
		var style='display:none;';
        var ddData3 = '<select name="ArrDeliveryDays[]" class="form-control multiple_category_select days000" multiple="" style="'+style+'"><option>Select</option><option value="Monday">Monday</option><option value="Tuesday">Tuesday</option><option value="Wednesday">Wednesday</option><option value="Thursday">Thursday</option><option value="Friday">Friday</option><option value="Saturday">Saturday</option><option value="Sunday">Sunday</option></select>';
		    
		
        var row = '<tr>' +
            '<td>'+ddData+'</td>' +
            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="zipcode"></td>' +
            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="area_name"></td>' + 
            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="town_name"></td>' + 
            '<td><input type="text" class="form-control" name="ArrZipCodeData[]" id="minimum_order_value"></td>' +
            '<td>'+ddData1+'</td>' +
            '<td>'+ddData2+'</td>' +
            '<td>'+ddData3+'</td>' +
			'<td>' + actions + '</td>' +
        '</tr>';
    	$("table").append(row);		
		$("table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        $('[data-toggle="tooltip"]').tooltip();
		ajax_page_drop_down('multiple_category_select');
    });
	
	// Add row on add button click
	$(document).on("click", ".add", function(){
		var empty = false;
		var input = $(this).parents("tr").find('input[type="text"]');
		var select = $(this).parents("tr").find('select');
		var input_value = '';
        var number = 1;

        input.each(function(){
			if(!$(this).val()){
				$(this).addClass("error");
				empty = true;
			} else{
				input_value = input_value +"&"+ $(this).attr('name') + "="+$(this).val();
                $(this).removeClass("error");
            }
            number++;
		});


        select.each(function(){
			if(!$(this).val()){
				$(this).addClass("error");
				empty = true;
			} else{
				input_value = input_value +"&"+ $(this).attr('name') + "="+$(this).val();
                $(this).removeClass("error");
            }
            number++;
		});


		$(this).parents("tr").find(".error").first().focus();
		var colCount = 1;
		if(!empty){
			input.each(function(){
				$(this).parent("td").html($(this).val());
				colCount++;
			});	
			select.each(function(){
				if(colCount==8)
				{
					var dy = $(this).val();
					let dy_text = dy.toString();
					$(this).parent("td").html(dy_text);
				}
				else
				{
					$(this).parent("td").html($(this).val());
				}
				colCount++;
			});			
			$(this).parents("tr").find(".add, .edit").toggle();
			$(".add-new").removeAttr("disabled");
		}
		saveZIPCode(input_value);
    });
	
	
	// Edit row on edit button click
	$(document).on("click", ".edit", function(){
		
		var current_zip_code = $(this).attr("data");
		$("#current_zip_code").val(current_zip_code);	
        var number = 1;
        var twise_in_week = '';
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
            
            if(number==1){     
				var selOption = '';
				<?php foreach($ArrStateOption as $key=>$state) { ?>
                var is_selected = ''; 
                if($(this).text()=='<?php echo $state; ?>'){ is_selected=' selected'; }
				selOption = selOption + '<option value="<?php echo $state; ?>"'+is_selected+'><?php echo $state; ?></option>';
				<?php } ?>
                var ddData = '<select name="ArrState[]" class="form-control">'+selOption+'</select>';
			    $(this).html(ddData);
            }
			else if(number==6){     
                var yes_chk = '';     
                var no_chk = ''; 
                if($(this).text()=='Yes'){ yes_chk=' selected'; }
                else if($(this).text()=='No'){ no_chk=' selected'; }
                var ddData = '<select name="ArrCanDeliverPerishable[]" class="form-control"><option>Select</option><option value="Yes"'+yes_chk+'>Yes</option><option value="No"'+no_chk+'>No</option></select>';
			    $(this).html(ddData);
            }
            else if(number==7){
                var option1_chk = '';   
                var option2_chk = ''; 
                var option3_chk = '';    
				twise_in_week = $(this).text();
                if($(this).text()=='Express Delivery'){ option1_chk=' selected'; }
                else if($(this).text()=='Same Day Delivery'){ option2_chk=' selected'; }
                else if($(this).text()=='Twise in a week'){ option3_chk=' selected'; }
                var ddData = '<select name="ArrDeliveryTypes[]" class="form-control" onChange="setDay(this.value,'+current_zip_code+');"><option>Select</option><option value="Express Delivery"'+option1_chk+'>Express Delivery</option><option value="Same Day Delivery"'+option2_chk+'>Same Day Delivery</option><option value="Twise in a week"'+option3_chk+'>Twise in a week</option></select>';
			    $(this).html(ddData);
            }
            else if(number==8){
                var m_chk = '';   
                var t_chk = ''; 
                var w_chk = '';       
                var th_chk = '';       
                var f_chk = '';       
                var st_chk = '';       
                var s_chk = '';   
				days = $(this).text();
				const dayArray = days.split(",");
				if(dayArray.indexOf("Monday") > -1) {  m_chk=' selected'; }
				if(dayArray.indexOf("Tuesday") > -1) {  t_chk=' selected'; }
				if(dayArray.indexOf("Wednesday") > -1) {  w_chk=' selected'; }
				if(dayArray.indexOf("Thursday") > -1) {  th_chk=' selected'; }
				if(dayArray.indexOf("Friday") > -1) {  f_chk=' selected'; }
				if(dayArray.indexOf("Saturday") > -1) {  st_chk=' selected'; }
				if(dayArray.indexOf("Sunday") > -1) {  s_chk=' selected'; }
				var style='display:none;';
				if(twise_in_week=='Twise in a week'){ style='display:block;'; }
				var ddData3 = '<select name="ArrDeliveryDays[]" class="form-control multiple_category_select days'+current_zip_code+'" multiple="" style="'+style+'"><option>Select</option><option value="Monday"'+m_chk+'>Monday</option><option value="Tuesday"'+t_chk+'>Tuesday</option><option value="Wednesday"'+w_chk+'>Wednesday</option><option value="Thursday"'+th_chk+'>Thursday</option><option value="Friday"'+f_chk+'>Friday</option><option value="Saturday"'+st_chk+'>Saturday</option><option value="Sunday"'+s_chk+'>Sunday</option></select>';
			    $(this).html(ddData3);
				ajax_page_drop_down('multiple_category_select');
            }
            else
			{
				$(this).html('<input type="text" name="ArrZipCodeData[]" class="form-control" value="' + $(this).text() + '">');
            }
            number++;
		});		
		$(this).parents("tr").find(".add, .edit").toggle();
		$(".add-new").attr("disabled", "disabled");
    });
	
	
	// Delete row on delete button click
	$(document).on("click", ".delete", function(){
		var zipcode_id = $(this).attr("data");
        $(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
		deleteZIPCode(zipcode_id);
    });
});
function setDay(day,current_zip_code)
{
	if(day=='Twise in a week')
	{
		$(".days"+current_zip_code).show();
	}
	else
	{
		$(".days"+current_zip_code).hide();
	}
}
function saveZIPCode(input_value)
{
	var current_zip_code = $("#current_zip_code").val();
	$.ajax({
	type        : "POST",
	url         : "<?php echo base_url() ?>save-zipcode",
	data        : "zipcode_id="+current_zip_code+input_value,
	success: function(results) {
		toastr.success('A record has been saved successfully!');
	},
	error: function() {
		toastr.error('Oops...! record(s) added operation has been failed, please try again.');
	}
	});
}
function deleteZIPCode(zipcode_id)
{
	$.ajax({
	type        : "POST",
	url         : "<?php echo base_url() ?>delete-zipcode",
	data        : "zipcode_id="+zipcode_id,
	success: function(results) {
		toastr.success('A record has been deleted successfully!');
	},
	error: function() {
		toastr.error('Oops...! record(s) deletion operation has been failed, please try again.');
	}
	});
}
</script>
</head>
<body>
<div class="container-lg">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"></div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info add-new"><i class="fa fa-plus"></i> Add New</button>
						<input type="hidden" name="current_zip_code" id="current_zip_code" value="" />
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>State</th>
                        <th>ZIP Code</th>
                        <th>Area</th>
                        <th>Town Name</th>
                        <th>Minimum Order Value</th>
                        <th>Deliver Perishable Products?</th>
                        <th>Delivery Types</th>
                        <th>Delivery Days</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
					<?php foreach($Arrzipcode as $data) { ?>
                    <tr>
                        <td><?php echo $data['state']; ?></td>
                        <td><?php echo $data['zipcode']; ?></td>
                        <td><?php echo $data['area_name']; ?></td>
                        <td><?php echo $data['town_name']; ?></td>
                        <td><?php echo $data['minimum_order_value']; ?></td>
                        <td><?php echo $data['can_deliver_perishable_products']; ?></td>
                        <td><?php echo $data['delivery_types']; ?></td>
                        <td><?php echo $data['delivery_days']; ?></td>
                        <td>
                            <a data="<?php echo $data['zipcode_id']; ?>"  class="add" title="Add" data-toggle="tooltip"><i class="material-icons">&#xE03B;</i></a>
                            <a data="<?php echo $data['zipcode_id']; ?>"  class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>
                            <a data="<?php echo $data['zipcode_id']; ?>" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>
                        </td>
                    </tr>  
					<?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>