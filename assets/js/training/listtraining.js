$(function() {
	           
    $(document).ready(function() {
    	var table = $('#myTable').DataTable({
			"bFilter": false,
			"bInfo": false,
			"bLengthChange": false,	   
	    	"order": [
	    		[3, 'desc']
	    	],
	    	"displayLength": 25,
            "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
	    });
	    // Order by the grouping
	    $('#myTable tbody').on('click', 'tr.unit', function() {
	    var currentOrder = table.order()[0];
	    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
	    	table.order([2, 'desc']).draw();
	    } else {
	    	table.order([2, 'asc']).draw();
	    }
	    });
    });
});

function editFeedback(id) {
        event.preventDefault(); // prevent form submit
         var dekstop="dekstop";
          window.location = baseURL+"staffs/training/editFeedback/"+id+'/'+dekstop;
     
    }