class ScreenManager {
  constructor(idScreen, pTabManager, pViewContainer) {
    console.log("ScreenManager constructor, idScreen = " + idScreen);
    this.tabManager = pTabManager;
    this.viewContainer = pViewContainer;
    this.dataTableManagerList = new DatatableManager();
    this.idScreen = idScreen;

    var screenManager = this;
    $('#menu_' + idScreen).click(function () {
      $.post(idScreen, {})
        .done(function (data) {
           screenManager.displayNewScreen(data);
        });
    });
  };

  //fonction dédiée à l'affichage de l'écran
  displayNewScreen(data)
  {
    $(".centerlogo").remove();

    this.tabManager.switchOrCreateTab(this.idScreen, true, this.idScreen, this);

    this.prependScreen("tab_" + this.idScreen, data);
    if ($("#form-screen-ecr").length > 0)
    {
        this.setEventListenerScreen();
        this.submitFunction($("#form-screen-ecr"));
    }
    else
    {        
        this.getListEcr(this.idScreen + '_afficher');
    }    
  };

  prependScreen(tabName, data)
  {
    if ($("div[id='" + tabName + "']").length === 0) {
      if ($("#content div").length !== 0) {
        this.tabManager.removeOldSelectedScreen();
      }
      $("#content").prepend(data);
      this.saveCurrentScreen($("#content div").attr("id"));
    }
  };

  saveCurrentScreen(idTab)
  {
    this.viewContainer[idTab] = $("#content").html();
  };

  // callBackChangeTab(idTab) {
  //   console.log("Fire event callback screen : " + idTab);
  // };

  callBackCloseTab(idTab) {
    console.log("Fire event callback screen : " + idTab);
  };

  callBackChangeTab(idTab) {
    this.displayContent(idTab);
    this.tabManager.switchTab(idTab);
    if ($("#form-screen-ecr").length > 0)
    {
        this.submitFunction($("#form-screen-ecr"));
    }
    else
    {
        this.getListEcr(this.idScreen + '_afficher');
    }
  };

  displayContent(idTab) {
    $("#content").empty().append(this.viewContainer[idTab]);
  };

  setEventListenerScreen() {
    var refScreenManager = this;
    $("#form-screen-ecr").submit(function (event) {
      event.preventDefault();
      submitFunction(this);

    });
  };

  submitFunction(form)
  {
    var data = {};
    var refScreenManager = this;
    
    $("form#form-screen-ecr :input").each(function () {
      var input = $(this); // This is the jquery object of the input, do what you will

      if (input.attr('name') !== undefined)
      {
        if (input.attr("type") === 'checkbox')
        {
          if (input.is(":checked")) {
            data[input.attr('name')] = 1;
          } else {
            data[input.attr('name')] = 0;
          }
        } else {
          data[input.attr('name')] = input.val();
        }
      }
    });

    $.ajax({
      url: $(form).attr('action'),
      type: $(form).attr('method'),
      data: data,
      success: function (data) {
        $("#loader").css("display", "block");
        $("#datatable-content").css("display", "none");

        if (refScreenManager.dataTableManagerList.dataTableObject !== null)
        {
            refScreenManager.dataTableManagerList.dataTableObject.destroy();
        }
        $("#datatabledefaultbody").empty();
        refScreenManager.displayScreen(data);

        $("#loader").css("display", "none");
        $("#datatable-content").css("display", "block");

      }
    });
  }
  setListenerDataTable(nameScreen) {
    this.dataTableManagerList.eventSelectRow('datatable');
  };

  getListEcr(url, param) {
    var refScreenManager = this;
    ajaxCall(url, 'post', param, refScreenManager, [function (data) {
        //Success callback
        refScreenManager.displayScreen(data);
      }, function (jqXHR, textStatus, errorThrown) {
        //Error callback
        console.log(textStatus);
      }, function (jqXHR, textStatus, errorThrown) {
        //Complete callback
        $("#loader").css("display", "none");
        $("#datatable-content").css("display", "block");
      }, function (jqXHR, textStatus, errorThrown) {
        //BeforeSend callback
        $("#loader").css("display", "block");
        $("#datatable-content").css("display", "none");
      }]
    );
  };

  displayScreen(data) {
    this.dataTableManagerList.createDataTable('datatable', data.data, data.columns);
  };
}
