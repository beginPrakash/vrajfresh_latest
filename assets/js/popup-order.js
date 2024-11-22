// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
$("document").ready(function(){
    $(document).on('click','#myBtn',function(){
        
        $("#myModal").css("display","block");
        $("iframe").attr("src", $(this).attr('href'));
        return false;
    });
    $(document).on('click','span',function(){
        
        $("#myModal").css("display","none");
    });
});


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }

}
