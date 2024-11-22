<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header filter_div">
				<form class="filter_form" id="frmListDataFilter" action="<?php echo SITE_URL ?>cms" method="post">
					<div class="row">
						<div class="col-sm-2">
							<input size="15" type="text" class="extra_field form-control" name="txtSearchKeyWord"
								id="txtSearchKeyWord" placeholder="Search Keyword" />
						</div>
						<div class="col-sm-2">
							<select class="form-control select2" style="width: 100%;" name="ddIsActive" id="ddIsActive">
								<option value="">Is Active</option>
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</div>
						<div class="col-sm-2">
							<input type="submit" class="btn btn-success" name="searchSubmit" id="searchSubmit"
								value="Search"> <!-- //- -->
							<input type="button" class="btn btn-info" name="searchReset" id="searchReset" value="Reset">
						</div>
						<!-- add export btn - START //- ->
						<div class="col-sm-2">
							<input type="submit" class="btn btn-warning" name="searchExport" id="searchExport" value="Export to CSV">
							<input type="hidden" value="<?php echo SITE_URL ?>adminpanel/controller_cms/cms_list_export" id="searchExportaction">
						</div>
						<!-- add export btn - END //- -->
					</div>
				</form>
			</div>
			<div class="box-body">
				<table id="tblListData" class="display nowrap" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Sr No</th>
							<th>Created Date</th>
							<th>Page Title</th>
							<th>Page Url</th>
							<th>Is Active</th>
							<th>Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	/* DATA TABLE JS */
	var orderCol = [[1, "desc"]];
	var cols = [{ "bSortable": false }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": true }, { "bSortable": false },];
	var colDef = [0, 5];
	var aoColumnDefs = [];
	var aoColumnDefsCenter = [];
	var ajaxURL1 = {
		url: $('#frmListDataFilter').attr('action'),
		type: "POST",
		data: function (d) {
			d.txtSearchKeyWord = $("#txtSearchKeyWord").val();
			d.ddIsActive = $("#ddIsActive").val();
		}
	};
	var footerCallBack = function (row, data, start, end, display) { };

	/* DETAILS POP UP AJAX CALL */
	function cms_detail(cms_id) {
		$(document.body).css({ 'cursor': 'wait' });
		$.ajax({
			type: "POST",
			url: "<?php echo base_url() ?>adminpanel/controller_cms/ajaxShowTblcmsMasterData",
			data: "cms_id=" + cms_id,
			success: function (results) {
				$(document.body).css({ 'cursor': 'default' });
				$('#recordDetailsPopUp').modal('show');
				$('#detailsPopUpData').html(results);
				$('#detailsPopUpTitle').html('CMS Page Details');
			},
			error: function () {
				$(document.body).css({ 'cursor': 'default' });
				toastr.error('Oops somthing wrong please try again!!');
			}
		});
	}


	//- export csv file of cms list - START
	$("#searchExport").click(function () {

		$(document.body).css({ 'cursor': 'wait' });
		$.ajax({
			type: "POST",
			url: $("#searchExportaction").val(),
			data: $("#frmListDataFilter").serialize(),
			success: function (data, textStatus, request) {

				if (jQuery.isPlainObject(data)) {
					data = data.data; //because my return data have a 'data' parameter with the content
				}

				var filename = "";
				var disposition = request.getResponseHeader('Content-Disposition');
				if (disposition && disposition.indexOf('attachment') !== -1) {
					var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
					var matches = filenameRegex.exec(disposition);
					if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
				}
				if (!jQuery.isPlainObject(data)) { //is CSV - we use blob
					var type = request.getResponseHeader('Content-Type');
					var blob = new Blob([data], { type: type, endings: 'native' });
					var URL = window.URL || window.webkitURL;
					var downloadUrl = URL.createObjectURL(blob);
				}
				var a = document.createElement("a");
				a.href = jQuery.isPlainObject(data) ? data.file : downloadUrl;
				a.download = jQuery.isPlainObject(data) ? data.filename : filename;
				document.body.appendChild(a);
				a.click();
				a.remove();
			},
			error: function (ajaxContext) {
				toastr.error('Export error: ' + ajaxContext.responseText);
			}
		});
	});
//- export csv file of cms list - END




</script>