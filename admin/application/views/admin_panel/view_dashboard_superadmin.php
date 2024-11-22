<script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/highcharts.js"></script>
<script src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/plotly-latest.min.js"></script>
<script type="text/javascript" language="javascript"
    src="<?php echo ADMIN_PANEL_THEME_PATH; ?>dist/js/jscharts.js"></script>

<div class="row">
    <div class="col-lg-12 col-xs-12">
        <div class="form-group">
            <form name="frmFilter" id="frmFilter" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <input type="hidden" id="from_date_val" name="from_date_val" value="">
                <input type="hidden" id="to_date_val" name="to_date_val" value="">
                <input type="hidden" id="searchSubmit" name="searchSubmit" value="Search">
            </form>
        </div>
    </div>
    <!--<div class="col-lg-3 col-xs-6 responsive_break_word">-->
    <!--   <div class="small-box bg-aqua">-->
    <!--      <div class="inner">-->
    <!--         <h3>-->
    <!--          444-->
    <!--         </h3>-->
    <!--         <p>-->
    <!--            Customers-->
    <!--         </p>-->
    <!--      </div>-->
    <!--      <div class="icon">-->
    <!--        <i class="fa fa-briefcase" aria-hidden="true"></i>-->
    <!--      </div>-->
    <!--      <a href="<?php //echo SITE_URL; ?>customers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
    <!--   </div>-->
    <!--</div>-->
    <!--<div class="col-lg-3 col-xs-6 responsive_break_word">-->
    <!--   <div class="small-box bg-green">-->

    <!--      <div class="inner">-->
    <!--         <h3>-->
    <!--            3333-->
    <!--         </h3>-->
    <!--         <p>-->
    <!--            Today Orders-->
    <!--         </p>-->
    <!--      </div>-->
    <!--      <div class="icon">-->
    <!--         <i class="fa fa-shopping-cart" aria-hidden="true"></i>-->
    <!--      </div>-->
    <!--      <a href="<?php //echo SITE_URL; ?>orders" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
    <!--   </div>-->
    <!--</div>-->
    <!--<div class="col-lg-3 col-xs-6 responsive_break_word">-->
    <!--   <div class="small-box bg-yellow">-->
    <!--      <div class="inner">-->
    <!--         <h3>-->
    <!--           444-->
    <!--         </h3>-->
    <!--         <p>-->
    <!--            This Week Order-->
    <!--         </p>-->
    <!--      </div>-->
    <!--      <div class="icon">-->
    <!--         <i class="fa fa-file-image-o" aria-hidden="true"></i>-->
    <!--      </div>-->
    <!--      <a href="<?php //echo SITE_URL; ?>orders"  class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
    <!--   </div>-->
    <!--</div>-->
    <!--<div class="col-lg-3 col-xs-6 responsive_break_word">-->
    <!--   <div class="small-box bg-red">-->
    <!--      <div class="inner">-->

    <!--         <h3>3333</h3>-->
    <!--         <p>Unfulfilled Order</p>-->
    <!--      </div>-->
    <!--      <div class="icon">-->
    <!--         <i class="fa fa-cart-plus" aria-hidden="true"></i>-->
    <!--      </div>-->
    <!--      <a href="<?php //echo SITE_URL; ?>orders" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
    <!--   </div>-->
    <!--</div>-->
</div>




<div class="row">
    <div class="col-lg-12 col-xs-12">



        <div class="col-lg-6 col-xs-6">

            <figure class="highcharts-figure">
                <div id="monthly_order_amount"></div>
            </figure>
            <script>
                Highcharts.chart('monthly_order_amount', {
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: 'Monthly Order Amount'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {

                        categories: [<?php foreach ($ArrOrderCounts as $data) {
                            echo "'" . $data['month'] . "',";
                        } ?>],

                        accessibility: {
                            description: 'Months of the year'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Order Amount'
                        },
                        labels: {
                            formatter: function () {
                                return this.value;
                            }
                        }
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        }
                    },
                    series: [{
                        name: 'Orders',
                        marker: {
                            symbol: 'square'
                        },
                        data: [<?php foreach ($ArrOrderCounts as $data) {
                            echo $data['order_total'] . ",";
                        } ?>]
                    }]
                });
            </script>

        </div>

        <div class="col-lg-6 col-xs-6">

            <figure class="highcharts-figure">
                <div id="monthly_order_count"></div>
            </figure>
            <script>
                Highcharts.chart('monthly_order_count', {
                    chart: {
                        type: 'spline'
                    },
                    title: {
                        text: 'Monthly Order Count'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [<?php foreach ($ArrOrderCounts as $data) {
                            echo "'" . $data['month'] . "',";
                        } ?>],
                        accessibility: {
                            description: 'Months of the year'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Order Count'
                        },
                        labels: {
                            formatter: function () {
                                return this.value;
                            }
                        }
                    },
                    tooltip: {
                        crosshairs: true,
                        shared: true
                    },
                    plotOptions: {
                        spline: {
                            marker: {
                                radius: 4,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        }
                    },
                    series: [{
                        name: 'Orders',
                        marker: {
                            symbol: 'square'
                        },
                        data: [<?php foreach ($ArrOrderCounts as $data) {
                            echo $data['order_count'] . ",";
                        } ?>]

                    }]
                });
            </script>

        </div>


    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-xs-12">
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-xs-12">


        <div class="col-lg-6 col-xs-6">

            <figure class="highcharts-figure">
                <div id="product_category_shares_sales" style="display:none;"></div>
            </figure>
            <script>
                Highcharts.chart('product_category_shares_sales', {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Product Category Shares'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    series: [{
                        name: 'Category',
                        colorByPoint: true,
                        data: [{
                            name: 'Grocery',
                            y: 61.41,
                            sliced: true,
                            selected: true
                        }, {
                            name: 'Fresh Produce',
                            y: 11.84
                        }, {
                            name: 'Frozen',
                            y: 10.85
                        }, {
                            name: 'Dairy Products',
                            y: 4.67
                        }, {
                            name: 'Vraj Bakery',
                            y: 4.18
                        }, {
                            name: 'Personal & Healthcare',
                            y: 1.64
                        }, {
                            name: 'Canned Food',
                            y: 1.6
                        }, {
                            name: 'Organic',
                            y: 1.2
                        }, {
                            name: 'Other',
                            y: 2.61
                        }]
                    }]
                });
            </script>
        </div>

        <div class="col-lg-6 col-xs-6">
            <figure class="highcharts-figure">
                <div id="monthly_new_repeat_customer" style="display:none;"></div>
            </figure>
            <script>
                Highcharts.chart('monthly_new_repeat_customer', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Monthly Average New Customer & Repeat Customers'
                    },
                    subtitle: {
                        text: ''
                    },
                    xAxis: {
                        categories: [
                            'Jan',
                            'Feb',
                            'Mar',
                            'Apr',
                            'May',
                            'Jun',
                            'Jul',
                            'Aug',
                            'Sep',
                            'Oct',
                            'Nov',
                            'Dec'
                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Orders'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [{
                        name: 'New Customer',
                        data: [49, 91, 34, 56, 45, 67, 89, 34, 12, 23, 45, 67]

                    }, {
                        name: 'Repeat Customers',
                        data: [83, 34, 24, 56, 79, 12, 32, 45, 67, 34, 23, 12]

                    }]
                });
            </script>
        </div>
    </div>
</div>