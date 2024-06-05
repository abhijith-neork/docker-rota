$(function() {
  $(document).ready(function() {
    var year = (new Date).getFullYear();
    $('#start_date').datepicker({
      minDate: 0,
      dateFormat: 'dd/mm/yy',
      // minDate: new Date(year, 0, 1),
      maxDate: new Date(year, 11, 31)
    });
    var table = $('#myTable').DataTable({
      "order": [
        [2, 'asc']
      ],
      "displayLength": 15,
      "lengthMenu": [[15, 25, 50, 75, 100, -1], [15, 25, 50, 75, 100, "All"]],
    });
  });
});
function convert(str) {
    var date = new Date(str),
        mnth = ("0" + (date.getMonth()+1)).slice(-2),
        day  = ("0" + date.getDate()).slice(-2);
    return [ date.getFullYear(), mnth, day ].join("-");
}
function findDatesBetnDates(startDate,endDate){
  var dates = [];
  dates.push(startDate);
  var currDate = moment(startDate).startOf('day');
  var lastDate = moment(endDate).startOf('day');
  while(currDate.add(1, 'days').diff(lastDate) < 0) {
    var cur_date = currDate.clone().toDate();
    date = moment(cur_date).format('YYYY-MM-DD');
    dates.push(date);
  }
  if(startDate != endDate){
    dates.push(endDate);
  }    
  return dates;
}
$(document).on('click', '.btn-accept,.btn-reject', function(event) {
  event.preventDefault();
  var users = [];
  var url = '';
  var unit_id = $( "#unit_id" ).val();
  var shift_id = $( "#shift_id" ).val();
  var date = $("#date").val();
  var user_id = $( "#user_id" ).val();
  var user_unit_id = $( "#user_unit_id" ).val();
  var status = parseInt(event.currentTarget.id);
  if(status == 1){
    url = baseURL+'manager/rota/acceptRequest';
  }else{
    url = baseURL+'manager/rota/rejectRequest';
  }

  var curr = new Date(date); // get current date
  var first = curr.getDate() - curr.getDay(); // First day is the day of the month - the day of the week
  var last = first + 6; // last day is the first day + 6

  var firstday = new Date(curr.setDate(first));
  var lastday = new Date(curr.setDate(last));

  var start_date = convert(firstday);
  var end_date = convert(lastday);
  
  var date_array = findDatesBetnDates(start_date,end_date);
  $.ajax({
    url:url,
    type:'post',
    dataType:'json',
    delay: 250,
    data: {
      unit_id : unit_id,
      date : date,
      shift_id: shift_id,
      user_id : user_id,
      date_array : date_array,
      user_unit_id : user_unit_id
    },
    success: function (data) {
      swal({
        title : "",
        text: data.message,
        icon: "warning",
        button: "ok",
      });
    }
  });
});