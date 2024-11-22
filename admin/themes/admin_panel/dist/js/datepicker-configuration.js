$.fn.datepicker.language['en'] = {
    days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
    months: ['January','February','March','April','May','June', 'July','August','September','October','November','December'],
    monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    today: 'Today',
    clear: 'Clear',
    dateFormat: 'dd-mm-yyyy',
    timeFormat: 'hh:ii',
    firstDay: 0
};
/* TIME PICKER */
$('.date_time_picker_bottom_left').datepicker({timepicker: true,language: 'en',maxHours: 24,position: 'bottom left',});
$('.date_time_picker_bottom_right').datepicker({timepicker: true,language: 'en',maxHours: 24,position: 'bottom right',});
$('.date_time_picker_top_left').datepicker({timepicker: true,language: 'en',maxHours: 24,position: 'top left',});
$('.date_time_picker_top_right').datepicker({timepicker: true,language: 'en',maxHours: 24,position: 'top right',});
$('.date_time_picker_bottom_center').datepicker({timepicker: true,language: 'en',maxHours: 24,position: 'bottom center',});
$('.date_time_picker_top_center').datepicker({timepicker: true,language: 'en',maxHours: 24,position: 'top center',});
/* DATE PICKER */
$('.date_picker_bottom_left').datepicker({language: 'en',position: 'bottom left'});
$('.date_picker_bottom_right').datepicker({language: 'en',position: 'bottom right'});
$('.date_picker_top_left').datepicker({language: 'en',position: 'top left'});
$('.date_picker_top_right').datepicker({language: 'en',position: 'top right'});
$('.date_picker_bottom_center').datepicker({language: 'en',position: 'bottom center'});
$('.date_picker_top_center').datepicker({language: 'en',position: 'top center'});
function ajax_page_date_picker() {
    $('.date_picker_bottom_center').datepicker({language: 'en',position: 'bottom center'});
}