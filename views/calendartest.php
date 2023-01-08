<h2 class='form-outline mx-5 my-2'>Kalendarz</h2>

<script src="js/fullcalendar.index.global.min.js"></script>

<div class='form-outline mx-5 my-2'>

    <div id="calendar"></div>

</div>

<script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'pl',
            initialView: 'dayGridMonth',
            firstDay: 1,
            events: <?= json_encode($calendarEvents) ?>,
        });
        calendar.render();
      });

</script>
