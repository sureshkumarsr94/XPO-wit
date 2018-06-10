$(document).ready(function(){
   $('.datepicker').datepicker({
	format:'yyyy-mm-dd',
	});
});

 function searchFunction() {
	// Declare variables 
	var input, filter, table, tr, td, i;
	input = document.getElementById("myInput");
	filter = input.value.toUpperCase();
	table = document.getElementById("dataTable");
	tr = table.getElementsByTagName("tr");

	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[2];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
			} else {
				tr[i].style.display = "none";
			}
		}
	}
}

function initiateCall(phoneno, order_number) {
    $.post("call.php", { phone_number : phoneno, order_number : order_number }, null, "json")
        .fail(
            function(data) {
                
            })
        .done(
            function(data) {
                
            })
    ;
 }



