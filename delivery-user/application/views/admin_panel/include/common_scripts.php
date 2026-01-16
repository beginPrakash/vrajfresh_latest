<!--******* COMMON POPUP **************************-->
<div class="modal fade" id="common_popup" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="common_popup_title">Loading...</h4>
			</div>
			<div class="modal-body" id="common_popup_content">
				<img src="<?php echo base_url() ?>themes/admin_panel/dist/img/loader.gif" />
			</div>
		</div>
	</div>
</div>
<!--******* COMMON POPUP TWO **************************-->
<div class="modal fade" id="dashboard_popup" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="dashboard_popup_close close" id="">&times;</button>
				<h4 class="modal-title" id="dashboard_popup_title">Loading...</h4>
			</div>
			<div class="modal-body" id="dashboard_popup_content">
				<img src="<?php echo base_url() ?>themes/admin_panel/dist/img/loader.gif" />
			</div>
		</div>
	</div>
</div>
<!-- details pop up start -->
<div class="modal fade" id="Full_widthPopUp" role="dialog">
	<div class="modal-dialog modal-lg custom_popup_width">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header padding_bts">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="Full_widthdetailsPopUpTitle"></h4>
			</div>
			<div style="padding-top:0px;" class="modal-body text_line_height innner_tbls"
				id="Full_widthdetailsPopUpData">
				<div class="loading">
					<span class="circle1"></span>
					<span class="circle2"></span>
					<span class="circle3"></span>
					<span class="circle4"></span>
					<span class="circle5"></span>
					<b>LODING</b>
					<p>Please give me a MOMENT to COLLECT my data</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- details pop up end -->

<link href="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/css/toastr.css" rel="stylesheet" />
<script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/toastr.js"></script>
<script>
	if ($(".comm_editer").length > 0) {
		$(function () {
			if (typeof tinymce === "undefined") {
			} else {
				tinymce.remove();
				tinymce.init({
					selector: "textarea.comm_editer",
					setup: function (editor) {
						editor.on('change', function () {
							editor.save();
						});
					},
					plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen",
						"insertdatetime media table contextmenu paste jbimages"
					],
					toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
					relative_urls: false
				});
			}
		});
	}
	$(function () {
		$('.select2').select2({ allowClear: true });
		$('.select_with_clear').select2({ placeholder: "Select", allowClear: true });
		$('.select_with_noclear').select2();
		$('.multiple_category_select').select2({ placeholder: "Select Category", allowClear: true });
		$('.multiple_product_select').select2({ placeholder: "Select Product", allowClear: true });
		$('.multiple_industry_list_select').select2({ placeholder: "Select industry", allowClear: true });
		$('.plateform_list_select').select2({ placeholder: "Select plateform", allowClear: true });
		$('.select_with_other_parm').select2({ tags: true });
		$('.select_user').select2({ placeholder: "Select User", allowClear: true });
		$('.select_customer').select2({ placeholder: "Select Customer", allowClear: true });
		$('.multiple_brand_select').select2({ placeholder: "Select Brand", allowClear: true });

		$('.select_customer_ajax').select2({
			placeholder: "Select Customer",
			ajax: {
				url: site_url + 'adminpanel/controller_user/getUserJson',
				dataType: 'json',
			},
		});

		$('.select_product_ajax').select2({
			minimumInputLength: 4,
			placeholder: "Select Product",
			ajax: {
				url: site_url + 'adminpanel/controller_product/getProductJson',
				dataType: 'json',
			},
		});
	});
	function ajax_page_drop_down(my_cls = 'select2') {
		$(function () {
			$('.' + my_cls).select2();
		});
	}

	/* DISPLAY FLASH MESSAGE */
	$(document).ready(function () {
		toastr.options = { "debug": false, "positionClass": "toast-bottom-right", "onclick": null, "fadeIn": 300, "fadeOut": 1000, "timeOut": 3000, "extendedTimeOut": 1000 }
		<?php $msg = $this->session->flashdata('success_message');
		if (isset($msg)) { ?>
			toastr.success('<?php echo $msg; ?>');
		<?php } ?>
		<?php $err = $this->session->flashdata('error_message');
		if (isset($err)) { ?>
			toastr.error('<?php echo $err; ?>');
		<?php } ?>
	});



	/* DATA TABLE CREATE */
	$(document).ready(function () {
		var initCompleteFunction = function (settings, json) { };
		if ($('#tblListData').length > 0) {
			var oTable = $('#tblListData').DataTable({
				"scrollX": true,
				//"bScrollCollapse": true,
				"aoColumns": cols,
				"paging": true,
				"aaSorting": [],
				"pageLength": 10,
				"lengthChange": true,
				"searching": false,
				"ordering": true,
				"order": orderCol,
				"info": true,
				"autoWidth": false,
				"bPaginate": true,
				//"sDom" : 'lipt',
				"sDom": '<"topsearch"li<"pagination_div"p>fB>rt<"bottom"<"clear">>',
				"processing": true,
				"serverSide": true,
				"ajax": ajaxURL1,
				"pagingType": "input",
				"language": {
					"info": " _TOTAL_ entries", // Showing _START_ to _END_ of _TOTAL_ entries
					"infoFiltered": "", // - filtering from _MAX_ records
					"lengthMenu": 'Showing <select>' +
						'<option value="10">10</option>' +
						'<option value="20">20</option>' +
						'<option value="30">30</option>' +
						'<option value="40">40</option>' +
						'<option value="50">50</option>' +
						'<option value="100">100</option>' +
						//'<option value="-1">All</option>'+
						'</select> of ',
					"sSearch": "",
					"sSearchPlaceholder": "Search",
					"paginate": {
						"first": "<<",
						"last": ">>",
						"next": ">",
						"previous": "<",
					},
					"sInfoEmpty": " 0 entries"
				},
				"columnDefs": colDef,
				"footerCallback": footerCallBack,
				"aoColumnDefs": [
					{ "sClass": "numericCol", "aTargets": aoColumnDefs },
					{ "sClass": "centerClass", "aTargets": aoColumnDefsCenter }
				],
				"initComplete": initCompleteFunction,
				
			});
		}
		/* DATA TABLE SUBMIT BUTTON ACTION */
		$('#frmListDataFilter').on('submit', function (e) {
			oTable.draw();
			e.preventDefault();
		});

		$("#searchReset").click(function () { location.reload(); });

		$("#searchExport").click(function () {

			console.log($("#frmListDataFilter").serialize());

			$(document.body).css({ 'cursor': 'wait' });
			$.ajax({
				type: "POST",
				url: $("#searchExportaction").val(),
				data: $("#frmListDataFilter").serialize(),
				success: function (data, textStatus, request) {
					$(document.body).css({ 'cursor': 'default' });
					if (jQuery.isPlainObject(data)) {
						data = data.data; //because my return data have a 'data' parameter with the content
					}


				},
				error: function (ajaxContext) {
					$(document.body).css({ 'cursor': 'default' });
					toastr.error('Export error: ' + ajaxContext.responseText);
				}
			});
		});
	});
	/* DATA TABLE END */
	/* IS ACTIVE - UPDATE AJAX CALL */
	function updateIsActiveValue(primary_id, tablename, column_name) {
		$(document.body).css({ 'cursor': 'wait' });
		$.ajax({
			type: "POST",
			url: "<?php echo base_url() ?>controller_common/ajaxStatusUpdate",
			data: "cms_id=" + primary_id + "&tablename=" + tablename + "&column_name=" + column_name,
			success: function (results) {
				var obj = JSON.parse(results);
				$(document.body).css({ 'cursor': 'default' });
				if (obj.message == "success" && obj.is_active == '0') {
					$(".update_status_i" + primary_id).html('<small class="label label-warning">No</small>');
				}
				if (obj.message == "success" && obj.is_active == '1') {
					$(".update_status_i" + primary_id).html('<small class="label label-info">Yes</small>');
				}
				toastr.success('Staus has been updated successfully!');
			},
			error: function () {
				$(document.body).css({ 'cursor': 'default' });
				toastr.error('Oops...! active status updating failed, please try again.');
			}
		});
	}

	/* IS SOLD - UPDATE AJAX CALL */
	function updateIsSoldValue(primary_id, tablename, column_name) {
		console.log('soldout'+primary_id);
		$(document.body).css({ 'cursor': 'wait' });
		$.ajax({
			type: "POST",
			url: "<?php echo base_url() ?>controller_common/ajaxStatusSoldUpdate",
			data: "cms_id=" + primary_id + "&tablename=" + tablename + "&column_name=" + column_name,
			success: function (results) {
				var obj = JSON.parse(results);
				$(document.body).css({ 'cursor': 'default' });
				if (obj.message == "success" && obj.is_active == '1') {
					$(".update_sold_status_i" + primary_id).html('<small class="label label-warning">No</small>');
				}
				if (obj.message == "success" && obj.is_active == '0') {
					$(".update_sold_status_i" + primary_id).html('<small class="label label-info">Yes</small>');
				}
				toastr.success('Staus has been updated successfully!');
			},
			error: function () {
				$(document.body).css({ 'cursor': 'default' });
				toastr.error('Oops...! active status updating failed, please try again.');
			}
		});
	}
	/* DELETE SINGLE RECORD -AJAX CALL */
	$(document).on('click', '.deleteRecord', function () {
		var action_url = $(this).attr('rel');
		swal({
			title: "Are you sure? Do you want to delete this record?",
			text: "You will not be able to recover this record!",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yes, delete it!",
			closeOnConfirm: true
		},
			function (isConfirm) {
				if (isConfirm) {
					$.ajax({
						type: "POST",
						url: action_url,
						success: function (results) {
							if (results.trim() == "Yes") {
								$('#tblListData').DataTable().ajax.reload();
								toastr.success('Record has been deleted successfully');
							} else {
								toastr.error('Oops...! record(s) deletion operation has been failed, please try again.');
							}
						},
						error: function () {
							toastr.error('Oops...! record(s) deletion operation has been failed, please try again.');
						}
					});
				}
			});
	});
	/* DELETE MULTIPLE RECORDS -AJAX CALL */
	function delete_multiple_record() {
		var primary_id = [];
		$(':checkbox:checked').each(function (i) {
			primary_id[i] = $(this).val();
		});
		if (primary_id.length > 0) {

			swal({
				title: "Are you sure? Do you want to delete this record?",
				text: "You will not be able to recover this record!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: "btn-danger",
				confirmButtonText: "Yes, delete it!",
				closeOnConfirm: true
			},
				function (isConfirm) {
					if (isConfirm) {
						$.ajax({
							type: "POST",
							url: $('#multiple_delete_action').val(),
							data: "primary_id=" + primary_id,
							success: function (results) {
								if (results.trim() == 1) {
									$('#tblListData').DataTable().ajax.reload();
									toastr.success('Record has been deleted successfully');
								} else {
									toastr.error('Oops...! record(s) deletion operation has been failed, please try again.');
								}
							},
							error: function () {
								toastr.error('Oops...! record(s) deletion operation has been failed, please try again.');
							}
						});
					}
				});
		}
		else {
			toastr.error('Oops...! please select atleast one record and try again.');
		}
	}


	/* LOADER */
	document.onreadystatechange = function () {
		var state = document.readyState
		if (state == 'interactive') {
			//document.getElementById('contents').style.visibility="hidden";
		} else if (state == 'complete') {
			setTimeout(function () {
				document.getElementById('interactive');
				document.getElementById('load').style.visibility = "hidden";
				//document.getElementById('contents').style.visibility="visible";
			}, 1000);
		}
	}
	/* QUICK VIEW POP UP */
	$(document).on('click', '.view_action', function () {
		var action_url = $(this).attr('rel');
		var title = $(this).attr('title');
		var id = $(this).attr('id');
		$.ajax({
			type: "POST",
			url: action_url,
			data: { id: id, title: title },
			success: function (results) {
				$(document.body).css({ 'cursor': 'default' });
				$('#recordDetailsPopUp').modal('show');
				$('#detailsPopUpData').html(results);
				$('#detailsPopUpTitle').html(title);
			},
			error: function () {
				$(document.body).css({ 'cursor': 'default' });
				toastr.error('Oops..!! something went wrong please try again.');
			}
		});
	});

	/* MENU */
	$(document).ready(function () {
		$(".dropdown").hover(
			function () {
				$('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("400");
				$(this).toggleClass('open');
			},
			function () {
				$('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("400");
				$(this).toggleClass('open');
			}
		);
	});


	$(document).ready(function () {
		$("#search-box").keyup(function () {
			$.ajax({
				type: "POST",
				url: "<?php echo site_url(); ?>adminpanel/controller_promotional_code/get_user_email/",
				data: 'keyword=' + $(this).val(),
				beforeSend: function () {
					$("#search-box").css("background", "#FFF url(<?php echo site_url(); ?>themes/adminpanel/dist/img/LoaderIcon.gif) no-repeat 165px");
				},
				success: function (data) {
					$("#suggesstion-box").show();
					$("#suggesstion-box").html(data);
					$("#search-box").css("background", "#FFF");
				}
			});
		});
	});

	function selectCountry(val, user_id) {
		$("#search-box").val(val);
		$("#suggesstion-box").hide();
		$("#search-box-hides").val(user_id);
	}

	$('.numberonly').keypress(function (e) {
		var charCode = (e.which) ? e.which : event.keyCode
		if (String.fromCharCode(charCode).match(/[^0-9]/g))
			return false;
	});
	$('.select_all_delete').change(function () {
		let isChecked = $(this).is(':checked');
		if (isChecked) {
			$('.chkDelete').prop('checked', true);
		}
		else {
			$('.chkDelete').prop('checked', false);
		}
	});
</script>