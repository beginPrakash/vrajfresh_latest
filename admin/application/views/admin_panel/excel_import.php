<form method="post" id="import_form" enctype="multipart/form-data">
	<p><label>Select Excel File</label>
		<input type="file" name="file" id="file" required accept=".xls, .xlsx" />
	</p>
	<br />
	<input type="submit" name="import" value="Import" class="btn btn-info" />
	<div id="product_result" style="display:none;">Product importing.....</div>
</form>

<script>
	$(document).ready(function () {



		$('#import_form').on('submit', function (event) {
			event.preventDefault();
			$("#product_result").show();
			$.ajax({
				url: "<?php echo base_url(); ?>adminpanel/excel_import/import",
				method: "POST",
				data: new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success: function (data) {
					$('#file').val('');
					$("#product_result").html(data);
				}
			})
		});

	});
</script>