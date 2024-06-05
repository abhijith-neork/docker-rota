$(function () {

  $(document).ready(function () {
    var table = $('#myTable').DataTable({
      fnDrawCallback: function() {
        $('[data-toggle="popover"]').popover();
      },
      "ordering": true,
      "order": [
        [2, 'desc']
      ],
      columnDefs: [
        { orderable: false, targets: [6,10] }, // Disable sorting for the 3rd column (index 2)
      ],
      "displayLength": 25,
      "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
      processing: true,
      language: {
        processing: '<div class="spinner-border text-primary" role="status"></div>'
      },
      serverSide: true,
      ajax: {
        url: baseURL + 'admin/training/report',
        dataType: 'json',
        type: "POST",
      },
      columns: [
        { "data": "title" },
        { "data": "description" },
        { "data": "date_from" },
        { "data": "date_to" },
        { "data": "training_hour" },
        { "data": "place" },
        { "data": "unit" },
        { "data": "point_of_person" },
        { "data": "creation_date" },
        { "data": "created_userid" },
        { "data": "action" },
      ]
    });
    // Order by the grouping
    $('#myTable tbody').on('click', 'tr.unit', function () {
      var currentOrder = table.order()[0];
      if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
        table.order([2, 'desc']).draw();
      } else {
        table.order([2, 'asc']).draw();
      }
    });
  });
});


function deleteFunction(id, title) {
  event.preventDefault(); // prevent form submit
  //var form = event.target.form; // storing the form
  swal({
    title: 'Are you sure you want to delete the training "' + title + '"?',
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel please!",
    closeOnConfirm: true,
    closeOnCancel: true
  },
    function (isConfirm) {
      if (isConfirm) {
        // form.submit();          // submitting the form when user press yes
        window.location = baseURL + "admin/training/deletetraining/" + id
      } else {
        return false;
      }
    });
}

function edit(training_id, training_hour) {


  var html = '';

  if (training_hour == '') {
    html += "<textarea class='form-control' rows='1' cols='40' maxlength='30' Placeholder='Enter training hours(hh:mm)' id='trainig_hours' style='padding-top=2px;margin:2px;'>00:00</textarea><br>";
  }
  else {
    html += '<textarea class="form-control" rows="1" cols="40" maxlength="30" Placeholder="Enter additional hours(hh:mm)" id="trainig_hours" style="padding-top:2px;;margin:2px;">' + training_hour + '</textarea><br>';
  }


  swal({
    title: "Enter Training Hour",
    text: html,
    // --------------^-- define html element with id
    html: true,
    showCancelButton: true,
    closeOnConfirm: false,
    inputPlaceholder: "Training Hour"
  }, function (isConfirm) {

    if (isConfirm) {

      var hours = $('#trainig_hours').val();  //console.log(comment);

      if (hours) {

        var regex = /^\d{2}:\d{2}$/;
        $s = hours.split(':');
        if (regex.test(hours) == false) {
          swal.showInputError("Please correct hours format");
        }
        else if ($s[1] >= 60) {
          swal.showInputError("Minutes should be smaller than 60");
        }
        else {

          $.ajax({
            url: baseURL + "admin/Training/inserttraininghour",
            type: "POST",
            data: {

              hours: hours,
              training_id: training_id

            },
            success: function (data) {
              console.log(data);
              // if(data.status=='true')
              // {

              swal("Updated successfully");
              //console.log($('#hours').text("hours"));
              // }
              // else
              // {

              // }
              window.location.reload();
            }
          });
        }
      }
      else {

      }
    }
    else {

    }


  });
}

