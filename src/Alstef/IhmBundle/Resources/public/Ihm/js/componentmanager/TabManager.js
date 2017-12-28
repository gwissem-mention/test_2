class TabManager {
  constructor() {
    this.nbrTabMax = 5;
  }

  createTab(nameTab, idTab, screenObject) {
    $("#tabcontainer li").each(function(n) {
      if (($(this).attr("class") === "active") && ($(this).children().attr("aria-controls") !== idTab)) {
        $(this).removeAttr("class");
      }
    });

    //console.log("create : "+nameTab);
    var htmlTab = "<li role=\"presentation\" class=\"active\"><a href=\"#\" aria-controls=\"" + idTab + "\" role=\"tab\" data-toggle=\"tab\"><i id=\"closetabicon\" class=\"fa fa-times-circle\" style=\"float: right; margin-left: 15px; color: #373E87;\"></i>" + nameTab + "</a></li>";
    $("#tabcontainer").append(htmlTab);

    $("a[aria-controls='" + idTab + "']").children("i").mouseenter(function() {
        $(this).css("color", "#6A70B0");
        $(this).css('cursor', 'pointer');
    });

    $("a[aria-controls='" + idTab + "']").children("i").mouseleave(function() {
        $(this).css("color", "#373E87");
    });

    this.setListenerSwitchTab(idTab, screenObject);
    this.setListenerCloseTab(idTab, screenObject);
  }; 

  setListenerSwitchTab(idTab, screenObject) {
    $("a[aria-controls='" + idTab + "']").click(function() {
      if ($(this).parent().attr("class") !== "active") {
        screenObject.callBackChangeTab(idTab);
      }
    });
  };

  switchOrCreateTab(idTab, checkIfExist, nameStat, screenObject) {
    if (checkIfExist) {
      var refTabManager = this;
      var nbrTabcontainerLiChild = $("#tabcontainer li").length;
      var tabExist = false;

      if (nbrTabcontainerLiChild !== 0) {
        $("#tabcontainer li").each(function(n) {

          if ($(this).children().attr("aria-controls") === idTab) {
            tabExist = true;
          }

          if (nbrTabcontainerLiChild === n + 1 && !tabExist) {
            //screenObject.saveAndClearCurrentScreen(idTab);
            refTabManager.createTab(nameStat, idTab, screenObject);
          } else {
            refTabManager.switchTab(idTab);
          }
        });

      } else {
        //screenObject.saveAndClearCurrentScreen(idTab);
        this.createTab(nameStat, idTab, screenObject);
      }
    }
  };


  switchTab(idTab) {
    $("#tabcontainer li").each(function(n) {
      if (($(this).attr("class") === "active") && ($(this).children().attr("aria-controls") !== idTab)) {
        $(this).removeAttr("class");
      }
    });

    $("#tabcontainer li").each(function(n) {
      if ($(this).children().attr("aria-controls") === idTab) {
        $(this).attr("class", "active");
      }
    });
  };

  setListenerCloseTab(idTab, screenObject) {
    var refTabManager = this;

    $("a[aria-controls='" + idTab + "']").children("i").click(function() {
      if ($("a[aria-controls='" + idTab + "']").parent().attr("class") === "active") {
        $("#tabcontainer li").each(function(n) {
          if ((($(this).attr("class") === undefined) || ($(this).attr("class") === "")) && ($("#tabcontainer li").length > 1)) {
            $("a[aria-controls='" + idTab + "']").parent().remove();
            $(this).attr("class", "active");
            $("#content").empty();
            refTabManager.removeScreenOfContainer(idTab, screenObject);
            refTabManager.appendScreen($(this), screenObject);
            screenObject.callBackCloseTab(idTab);
            return false;
          } else if ($(this).attr("class") === "active" && ($("#tabcontainer li").length === 1)) {

            $("#content").empty();
            $("a[aria-controls='" + idTab + "']").parent().remove();
            refTabManager.removeScreenOfContainer(idTab, screenObject);
            screenObject.callBackCloseTab(idTab);
            //Add center logo
            $("#content").append("<img class=\"centerlogo\" type=\"image/png\" src=\"/bundles/alstefihm/Ihm/pictures/ALSTEF_fond_ecran_BAGWARE.jpg\">");
          }
        });

      } else if ($("a[aria-controls='" + idTab + "']").parent().attr("class") !== "active") {

        $("a[aria-controls='" + idTab + "']").parent().remove();
        refTabManager.removeScreenOfContainer(idTab, screenObject);
        screenObject.callBackCloseTab(idTab);
      }
    });
  };

  appendScreen(amarkup, screenObject) {
    for (var i=0; i<screenObject.viewContainer.length; i++) {
      if(amarkup.children("a").attr("aria-controls") === screenObject.viewContainer[i].name) {
        $("#content").append(screenObject.viewContainer[i].html);
      }
    }
  };

  removeScreenOfContainer(idTab, screenObject) {
    for (var i=0; i<screenObject.viewContainer.length; i++) {
      if(idTab === screenObject.viewContainer[i].name) {
        screenObject.viewContainer[i].html = null;
      }
    }
  };

  removeOldSelectedScreen() {
    $("#content").children(".tab-pane.active").remove();
  };
}
