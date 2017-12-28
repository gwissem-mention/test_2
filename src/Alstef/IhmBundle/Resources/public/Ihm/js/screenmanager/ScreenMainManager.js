$( document ).ready(function() {
  initListenerMainScreen();
  statusRefresh();
  setInterval(function() { statusRefresh(); }, 5000); // Rafraichissement des boutons d'état
});

// Rafraichissement des boutons de la barre d'etat
function statusRefresh() {
  $.getJSON('statusRefresh', function(data){
      $.each(data, function(button, content) {
        classDiv = "indic-" + content.state;
        classI = "";
        if (content.state === "err") {
          classI = "fa-ban";
        } else if (content.state === "warn") {
          classI = "fa-exclamation";
        } else if (content.state === "ok") {
          classI = "fa-check";
        }
        $("#" + button).attr("class", "col-xs-1 col-sm-1 col-md-1 " + classDiv).html('<i class="fa ' + classI + '"></i>' + content.value);
      });
    },
  );
}

function initListenerMainScreen() {
  $ ( "#logout" ).hover(
    function() {
      $( this ).css("color","#6A70B0");
    }
  );

  $ ( "#logout" ).click(
    function() {
      logout();
    }
  );

  //Event click on navBar
  $( "#logout" ).click(
    function() {
      logout();
    }
  );
    
}

function logout()
{
  param = {};
  ajaxCall("logout",'post',param,null,[callBackLogoutSuccess,callBackLogoutError,null,callBackLogoutComplete]);
}

function callBackLogoutSuccess(data)
{
  console.log(data.message);
}

function callBackLogoutError(jqXHR,textStatus,errorThrown)
{
  console.log(jqXHR.responseText);
}

function callBackLogoutComplete()
{
  window.location.href = getHost()+":8000/";
}