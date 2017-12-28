var ScreenBagManager = function(pTabManager, pViewContainer) {
    this.tabManager = pTabManager;
    this.viewContainer = pViewContainer;
    this.dataTableManagerList = new DatatableManager();
    this.idMenuBagManager = "menu_LISBAG";
    this.idTabLisBag = "LISBAG";
};

ScreenBagManager.prototype.setBagScreenClickListner = function() {
    var refScreenBagManager = this;
    $('#'+this.idMenuBagManager).click(function() {
        refScreenBagManager.getBagScreen('LISBAG', "LISBAG",{});
    });
};

ScreenBagManager.prototype.getBagScreen = function(path, typeScreen, paramObject) {
    var refScreenBagManager = this;
    $.post(path,paramObject)
        .done(function(data) {
            refScreenBagManager.displayNewBagScreen(data,typeScreen);
            $('#criteria > div.x_title').click(function() {
              if ($('#criteria > div.x_content').css('display') == 'block') {
                $('#criteria > div.x_content').css('display', 'none');
              }
              else {
                $('#criteria > div.x_content').css('display', 'block');
              }
            });
        });
};

//fonction dédiée à l'affiche de l'écrans
ScreenBagManager.prototype.displayNewBagScreen = function(data,typeScreen)
{
    $(".centerlogo").remove();

    nameBagScreen = this.getNameScreen(typeScreen);

    this.tabManager.switchOrCreateTab(typeScreen, true, nameBagScreen, this);

    this.prependScreen(nameBagScreen,data);
    this.setEventListenerScreen();
    this.getListBag('LISBAG_afficher',{ lisbag: { date_debut: "2017/12/20-00:00:00", date_fin: "2017/12/25-00:00:00", bsm_ok: "1", bsm_double: "0", bsm_delete: "0"}});
};

ScreenBagManager.prototype.getNameScreen = function(nameScreen)
{
    resultTitleScreen = "";

    switch (nameScreen) {
                case "LISBAG":
                    resultTitleScreen = "Liste des bagages";
                break;
            }

    return resultTitleScreen;
};

ScreenBagManager.prototype.prependScreen = function(nameScreen,data)
{
            if (($("div[id='" + nameScreen + "']").length === 0) && ($("#content div").length !== 0)) {
                //$( "div[id='"+typeStat+"']" ).remove();
                this.tabManager.removeOldSelectedScreen();
                $("#content").prepend(data);
                this.saveCurrentScreen($("#content div").attr("id"));

            } else if (($("div[id='" + nameScreen + "']").length === 0) && ($("#content div").length === 0)) {
                $("#content").prepend(data);
                this.saveCurrentScreen($("#content div").attr("id"));
            }
};

ScreenBagManager.prototype.saveCurrentScreen = function(idScreen) {

    html = $("#content").html();
    switch (idScreen) {
        case "LISBAG":
            this.viewContainer[1].html  = html;
        break;
    }

};

// ScreenBagManager.prototype.callBackChangeTab = function(idTab) {
//     console.log("Fire event callback screen : " + idTab);
// };

ScreenBagManager.prototype.callBackCloseTab = function(idTab) {
    console.log("Fire event callback screen : " + idTab);
};

ScreenBagManager.prototype.callBackChangeTab = function(idTab) {
    this.displayBagScreen(idTab);
    this.tabManager.switchTab(idTab);
    this.getListBag('LISBAG_afficher',{ lisbag: {  date_debut: "2017/12/20-00:00:00", date_fin: "2017/12/25-00:00:00", bsm_ok: "1", bsm_double: "0", bsm_delete: "0"}});
};
ScreenBagManager.prototype.displayBagScreen = function(idTab) {

    switch (idTab) {
        case "LISBAG":
            $("#content").empty();
            $("#content").append(this.viewContainer[1].html );
        break;
    }
};

ScreenBagManager.prototype.setEventListenerScreen = function() {
    var refScreenBagManager = this;
    $( "#form-screen-bag" ).submit(function( event ) {
        event.preventDefault();

        var data = {};

        $("form#form-screen-bag :input").each(function(){
            var input = $(this); // This is the jquery object of the input, do what you will

            if(input.attr('name') !== undefined)
            {
                if(input.attr("type") == 'checkbox')
                {
                    if (input.is(":checked"))
                    {
                        data[input.attr('name')] = 1;
                    }else{
                        data[input.attr('name')] = 0;
                    }
                }else{
                    data[input.attr('name')] = input.val();
                }
            }
        });

        $.ajax({
            url : $(this).attr('action'),
            type: $(this).attr('method'),
            data : data,
            success: function(data) {
                $("#loader").css("display","block");
                $("#datatable-content").css("display","none");

                refScreenBagManager.dataTableManagerList.dataTableObject.destroy();
                $("#datatabledefaultbody").empty();
                refScreenBagManager.displayBag(data);

                $("#loader").css("display","none");
                $("#datatable-content").css("display","block");

            }
        });
    });
};

//!! important quand gestion de plusieurs screen
ScreenBagManager.prototype.createComponent = function(nameScreen, data, columns) {
    switch (nameScreen) {
                case "LISBAG":
                        this.dataTableManagerList.createDataTable('datatable', data, columns);
                break;
            }
};

ScreenBagManager.prototype.setListenerDataTable = function(nameScreen) {
    switch (nameScreen) {
                case "LISBAG":
                        this.dataTableManagerList.eventSelectRow('datatable');
                break;
            }
};

ScreenBagManager.prototype.getListBag = function (url, param) {
    var refScreenBagManager = this;
    ajaxCall(url, 'post', param, refScreenBagManager, [function (data) {
        //Success callback
        refScreenBagManager.displayBag(data);
    }, function (jqXHR, textStatus, errorThrown) {
        //Error callback
        console.log(textStatus);
    }, function (jqXHR, textStatus, errorThrown) {
        //Complete callback
        $("#loader").css("display","none");
        $("#datatable-content").css("display","block");
    }, function (jqXHR, textStatus, errorThrown) {
        //BeforeSend callback
        $("#loader").css("display","block");
        $("#datatable-content").css("display","none");
    }]);
};

ScreenBagManager.prototype.displayBag = function(data)
{
    this.createComponent(this.idTabLisBag, data.data, data.columns);
};
