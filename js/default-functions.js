function openNav() {
  document.getElementById("mySidepanel").style.width = "250px";
}

/* Set the width of the sidebar to 0 (hide it) */
function closeNav() {
  document.getElementById("mySidepanel").style.width = "0";
}

function tableFilter() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("searchTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
      console.log(td);
    }
  }
}


function onChangeCity()
{
    data = new FormData(document.getElementById("rentAddForm"));

  $.ajax({
    url  : "index.php?action=rentAdd",  //your page
    type: "POST",                   // Type of request to be send, called as method
    data:  data,     // Data sent to server, a set of key/value pairs representing form fields and values
    contentType: false,             // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
    cache: false,                   // To unable request pages to be cached
    processData:false,              // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
    success: function(data)         // A function to be called if request succeeds
    {
      $("html").html(data);
    }
});
}


//---Old-Project---//
function onChangeModel()
{
    data = new FormData(document.getElementById("addDel"));

  $.ajax({
    url  : "index.php?action=addDelivery",  //your page
    type: "POST",                   // Type of request to be send, called as method
    data:  data,     // Data sent to server, a set of key/value pairs representing form fields and values
    contentType: false,             // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
    cache: false,                   // To unable request pages to be cached
    processData:false,              // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
    success: function(data)         // A function to be called if request succeeds
    {
      $("html").html(data);
    }
});
}

function onChangeList()
{
    data = new FormData(document.getElementById("filter"));

  $.ajax({
    url  : "index.php?action=home",  //your page
    type: "POST",                   // Type of request to be send, called as method
    data:  data,     // Data sent to server, a set of key/value pairs representing form fields and values
    contentType: false,             // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
    cache: false,                   // To unable request pages to be cached
    processData:false,              // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
    success: function(data)         // A function to be called if request succeeds
    {
      $("html").html(data);
    }
});
}

function onSubmit(event)
{
  event.preventDefault();
  data = new FormData(event.target);

  $.ajax({
    url  : "index.php?action=home",  //your page
    type: "POST",                   // Type of request to be send, called as method
    data:  data,     // Data sent to server, a set of key/value pairs representing form fields and values
    contentType: false,             // The content type used when sending data to the server. Default is: "application/x-www-form-urlencoded"
    cache: false,                   // To unable request pages to be cached
    processData:false,              // To send DOMDocument or non processed data file it is set to false (i.e. data should not be in the form of string)
    success: function(data)         // A function to be called if request succeeds
    {
      $("html").html(data);
    }
  });
}

function changeFast(element)
{
    document.location.href = element.value
}


/*
$(document).ready(function () {
    $("#typ").change(function () {
        var val = $(this).val();
        if (val == "stereo") {
            $("#rodzaj").html("<option value=''>--Wybierz--</option><option value='wzmiacniacz'>wzmacniacz</option><option value='amplituner'>amplituner</option><option value='słuchawki'>słuchawki</option>");
        } else if (val == "kolumny") {
            $("#rodzaj").html("<option value=''>--Wybierz--</option><option value='podłogowe'>podłogowe</option><option value='monitory'>monitory</option><option value='subwoofer'>subwoofer</option>");
        } else if (val == "odtwarzacz") {
            $("#rodzaj").html("<option value=''>--Wybierz--</option><option value='CD'>CD</option><option value='kasetowy'>kasetowy</option><option value='sieciowy'>sieciowy</option><option value='gramofon'>gramofon</option>");
        } else if (val == "akcesoria") {
            $("#rodzaj").html("<option value=''>--Wybierz--</option><option value='przewody'>przewody</option><option value='podstawki'>podstawki</option>");
        }
        else {
            $("#rodzaj").html("<option value=''>--Wybierz--</option>");
        }

    });
});
*/