var chk_val = []; //for save method string
var table;
jQuery(document).ready(function () {
    getOrderList();
    jQuery(document).on('click', '#searchSubmit', function(){
        getOrderList();
    });
    

    jQuery(document).on('click', '#searchReset', function(){        
        jQuery(this).closest('form').find("input[type=text], textarea, select").val("");        
        jQuery(".ms-options").each(function(){
            jQuery(this).find('li:not(.optgroup).selected input[type="checkbox"]').trigger('click');
        });
		 jQuery('#date_filter_type').val('Placed Date');
        table.ajax.reload();              
    });
    // export data 
    // export data
    jQuery("#searchExport").click(function() {        
        $.ajax({            
            type: "POST",
            url: site_url + 'adminpanel/controller_myorders/export_order_list',
            data:{
                'search_keyword' : $('#searchText').val(),
                'date_filter_type' : $('#date_filter_type').val(),
                'order_from_date' : $('#order_from_date').val(),
                'order_to_date' : $('#order_to_date').val(),
                'activity_to_date' : $('#activity_to_date').val(),
                'ss_id' : $('#ss_id').val(),                
                'bd_id' : $('#bd_id').val(),
                'pm_id' : $('#pm_id').val(),
				'v_id' : $('#v_id').val(),
                'product_id' : $('#product_id').val(),
                'project_status' : $('#project_status').val(),
                'order_type' : $('#order_type_name').val(),
                'order_from_amount' : $('#order_from_amount').val(),
                'order_to_amount' : $('#order_to_amount').val(),
                'o_payment_type' : $('#o_payment_type').val(),
                'o_payment_status' : $('#o_payment_status').val(),
                'p_payment_method' : $('#p_payment_method').val(),
                'p_payment_status' : $('#p_payment_status').val(),
                'order_user_id' : $('#filter_order_user_id').val()
            },
            success: function (data, textStatus, request) {                
            //you could need to decode json here, my app do it automaticly, use a try catch cause csv are not jsoned
            //already json decoded? custom return from controller so format is xls
            if(jQuery.isPlainObject(data)) {
              data = data.data; //because my return data have a 'data' parameter with the content
            }
            
            var filename = "";
            var disposition = request.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
              var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
              var matches = filenameRegex.exec(disposition);
              if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }
            if(!jQuery.isPlainObject(data)) { //is CSV - we use blob
               var type = request.getResponseHeader('Content-Type');
               var blob = new Blob([data], { type: type ,endings:'native'});
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
            toastr.error('Export error: '+ajaxContext.responseText);
          }
        });            
    });

});

function getOrderList() {
    table = jQuery('#tbl_order_list').DataTable({
        //"dom": '<"top"lpf>t<"bottom"i>',
        "dom":'<"topsearch"li<"pagination_div"p>fB>rt<"bottom"<"clear">>',                
        "destroy": true,
        "autoWidth": false,
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "order": [],
        //"info": true,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            ['10', '25', '50', '100', 'All']
        ],
        "language": {
            "info": " _TOTAL_ entries", // Showing _START_ to _END_ of _TOTAL_ entries
            "infoFiltered": "", // - filtering from _MAX_ records
            "lengthMenu": 'Showing <select>'+
                          '<option value="10">10</option>'+
                          '<option value="20">20</option>'+
                          '<option value="30">30</option>'+
                          '<option value="40">40</option>'+
                          '<option value="50">50</option>'+
                          '<option value="100">100</option>'+
                          //'<option value="-1">All</option>'+
                          '</select> of ',
            "paginate": {
                "first": "<<",
                "last": ">>",
                "next": ">",
                "previous": "<"
            },
            "search":"",
            "searchPlaceholder": "Search..."
        },
        "paging": true,
        "pagingType": "input",

        "ajax": {
            "url": site_url + 'adminpanel/controller_myorders/ajax_get_order_list',
            "type": "POST",
            "async": true,
            "data": function (d) {
                d.search_keyword = $('#searchText').val();
                d.date_filter_type = $('#date_filter_type').val();
                d.order_from_date = $('#order_from_date').val();
                d.order_to_date = $('#order_to_date').val();
                d.activity_to_date = $('#activity_to_date').val();
                d.ss_id = $('#ss_id').val();                
                d.bd_id = $('#bd_id').val();
                d.pm_id = $('#pm_id').val();
				d.v_id = $('#v_id').val();
                d.product_id = $('#product_id').val();
                d.project_status = $('#project_status').val();
                d.order_type = $('#order_type_name').val();
                d.o_payment_type = $('#o_payment_type').val();
                d.o_payment_status = $('#o_payment_status').val();
                d.p_payment_method = $('#p_payment_method').val();
                d.p_payment_status = $('#p_payment_status').val();
                d.order_from_amount = $('#order_from_amount').val();
                d.order_to_amount = $('#order_to_amount').val();
                d.order_user_id = $('#filter_order_user_id').val();
				d.src_mode= $('#src_mode').val();
            },
        },
        "columnDefs": [
                {
                    "orderable": false,
                    "targets": [0,21,22],
                },
                {"className": "float-right", "targets": [10]}

        ],
        "fnDrawCallback": function () {
            $(".paginate_button").show();
            //$(".disabled").hide();
        },
        "footerCallback": function ( row, data, start, end, display ) {             
            var api = this.api(), data;
        
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };            
            
            netTotal = api
                .column( 8, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
            }, 0 );
            netpagetotal = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                }, 0 );
                
            $( api.column( 8 ).footer() ).html(
                    '$'+netTotal.toFixed(2)+"<hr>$"+netpagetotal.toFixed(2)+""
            );

            paidTotal = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
            }, 0 );
            paidpagetotal = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                }, 0 );
            
            $( api.column( 9 ).footer() ).html(
                    '$'+paidTotal.toFixed(2)+"<hr>$"+paidpagetotal.toFixed(2)+""
            );

            debtTotal = api
                .column( 10, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
            }, 0 );
            debtpagetotal = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                }, 0 );
                
            $( api.column( 10 ).footer() ).html('$'+debtTotal.toFixed(2)+"<hr>$"+debtpagetotal.toFixed(2)+"");

            finalTotal = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
            }, 0 );
            finalpageTotal = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return parseFloat(a) + parseFloat(b);
            }, 0 );
                
            $( api.column( 11 ).footer() ).html('$'+finalTotal.toFixed(2)+"<hr>$"+finalpageTotal.toFixed(2)+"");
        },
    });
    //$('span.paginate_page').hide();
    $('.dataTables_info').css('width', 'auto');
    // $(".top").addClass('topsearch');    
    // $(".paging_input").wrap("<div class='pagination_div'></div>");
    $("input:text.paginate_input").addClass('form-control');
    //$('<span class="paginate_page">Go To </span>').insertBefore(".paginate_input");
    $(".dataTables_filter").attr("id","example_filter").css('float:right');
}