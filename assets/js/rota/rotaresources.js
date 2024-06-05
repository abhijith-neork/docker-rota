$(document).ready(function() {

    var date_last_clicked = null; 
    //console.log(rotabc);
     //var rotabc='[{"id":"a","title":"Swaraj"},{"id":"b","title":"Manu"}]';
    $('#calendar').fullCalendar({
 
  header: {
    left: 'prev,next today',
    center: 'title',
    right: 'month,agendaWeek,agendaDay'
  },
  defaultDate: '2019-08-01',
  editable: true,
    eventRender: function(event, element, view) {
        return $('<div>'+event.title+'</div>');
    },

  events: [
    {
    resourceId:'3',
    title: '<select id="drp"><option>Volvo</option><option value="saab">Saab</option><option value="opel">Opel</option><option value="audi">Audi</option></select>',  
    start: '2019-07-29'
    },
       {
    resourceId:'3',
    title: '<select id="drp"><option>Volvo</option><option value="saab">Saab</option><option value="opel">Opel</option><option value="audi">Audi</option></select>',  
    start: '2019-07-30'
    },
       {
    resourceId:'3',
    title: '<select id="drp"><option>Volvo</option><option value="saab">Saab</option><option value="opel">Opel</option><option value="audi">Audi</option></select>',  
    start: '2019-07-31'
    }
    ],
   
    resourceAreaWidth: 200,  
    editable: false,
    aspectRatio: 3,
    scrollTime: '00:00',
    header: {
      left: 'promptResource today prev,next',
      center: 'title',
      right: ' '
    },  

    customButtons: {
      promptResource: {
        text: '+ Employee',
        click: function() {
          var title = prompt('Staff name');
          if (title) {
            $('#calendar').fullCalendar(
              'addResource',
              { title: title },
              true 
            );
          }
        }
      }
    },
    firstDay: 0,
    defaultView: 'customWeek',
    views: {
        customWeek: {
            type: 'timeline',
            duration: { weeks: 1 },
            slotDuration: {days: 1},
            buttonText: 'Custom Week'
        }
    },
    resourceLabelText: 'Employee',
    resources:rotabc
   });
  
});
