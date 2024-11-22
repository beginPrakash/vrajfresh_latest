<div id="wrapper">
	<div class="inner-main" style="width:100%;"> 
		<div class="in-wrap">
			<div class="project-status">
				<div class="return-paypal-inner-main"> 
					<div class="intersted-in-container-main">
						<?php echo form_open('admin/controller_clientgroup/removeClientGroupCustomer'); ?>
						<table id="listdata" class="table table-bordered table-striped">
							<thead>
							<tr>
								<th>Joining Date</th><th>Name</th><th>Email</th><th>Action</th>
							</tr>
							</thead>
							<tbody>
							<?php 
							if(is_array($ArrFieldData) && count($ArrFieldData)>0)
							{
								foreach($ArrFieldData as $client) 
								{ 
								?>
								<tr>
									<td><?php echo $client['format_joining_date']; ?></td>
									<td><?php echo $client['name']; ?></td>
									<td><?php echo $client['email']; ?></td>
									<td>
									<input type="checkbox" class="chkDelete" name="delete[]" value="<?php echo $client['clientgroup_details_id']; ?>" />
									</td>
								</tr>
								<?php 
								} 
							}
							?>
							</tbody>
							
						<tfoot>
						<tr> 
						<td colspan="3"></td>
						<td><input type="submit" name = "submitForm" value = "Delete" class = "btn btn-danger pull-left" onClick="return checkCheckedOrNot();" /> </td>
						</tr>
						</tfoot>
						
						</table>
						</form>
					</div>
				</div>
			</div>		
		</div>
	</div>
	


      <!-- Add the sidebar's background. This div must be placed

           immediately after the control sidebar -->

      <div class="control-sidebar-bg"></div>

    </div><!-- ./wrapper -->

	<!-- DataTables -->

<script src="<?php echo admin_media(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>

<script src="<?php echo admin_media(); ?>/plugins/datatables/dataTables.bootstrap.min.js"></script>

<script>

/* data list start */
$(function () {
	$('#listdata').DataTable({

		"paging": true,

		"aaSorting": [],

		"lengthChange": true,

		"searching": true,

		"ordering": true,

		"info": true,

		"autoWidth": false

	});


});

/* end */



</script>
   