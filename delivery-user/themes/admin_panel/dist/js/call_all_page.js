
function displayOverlay(t) {
    $("<table id='admin_overlay'><tbody><tr><td>" + t + "</td></tr></tbody></table>").css({
        position: "fixed",
        top: "0px",
        left: "auto",
        width: "100%",
        height: "100%",
        "background-color": "rgba(0,0,0,.6)",
        "z-index": "10000",
        "vertical-align": "middle",
        "text-align": "center",
        color: "red",
        "font-size": "20px",
        "font-weight": "bold",
        cursor: "wait"
    }).appendTo("body")
}
function hideOverlay() {
    $("#admin_overlay").remove();
}
function getActivityInDetails(section_id,popup_title)
{
  $("#section_counter").hide();
  $("#section_unread_message").hide();
  $("#section_recent_read_message").hide();
  $("#section_mytask").hide();
  $("#section_job").hide();
  $("#section_order").hide();
  $("#"+section_id).show();
  $('#general_popup_title').html(popup_title);
}
function backToNumber()
{
  $('#general_popup_title').html('My Numbers');
  $("#section_counter").show();
  $("#section_unread_message").hide();
  $("#section_recent_read_message").hide();
  $("#section_mytask").hide();
  $("#section_job").hide();
  $("#section_order").hide();
}