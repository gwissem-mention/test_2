var ScreenVolManager = function(pTabManager, pViewContainer) {
    this.tabManager = pTabManager;
    this.viewContainer = pViewContainer;
    this.dataTableManagerList = new DatatableManager();
    this.idMenuVolManager = "menu_LISVOL";
    this.idTabLisVol = "LISVOL";
};

ScreenVolManager.prototype.setVolScreenClickListner = function() {
    var refScreenVolManager = this;
    $('#'+this.idMenuVolManager).click(function() {
        refScreenVolManager.getVolScreen('LISVOL', "LISVOL",{});
    });
};

ScreenVolManager.prototype.getVolScreen = function(path, typeScreen, paramObject) {
    var refScreenVolManager = this;
    $.post(path,paramObject)
        .done(function(data) {
            refScreenVolManager.displayNewVolScreen(data,typeScreen);
        });
};

//fonction dédiée à l'affichage de l'écran
ScreenVolManager.prototype.displayNewVolScreen = function(data,typeScreen)
{
    $(".centerlogo").remove();

    nameVolScreen = this.getNameScreen(typeScreen);

    this.tabManager.switchOrCreateTab(typeScreen, true, nameVolScreen, this);

    this.prependScreen(nameVolScreen,data);
    this.setEventListenerScreen();
    this.getListVol('LISVOL_afficher',{ lisvol: { sdd_vol: "", vol_du_jour: "0", vol_affecte: "0", vol_nonaffecte: "0", en_warning: "0", en_erreur: "0" }});
};

ScreenVolManager.prototype.getNameScreen = function(nameScreen)
{
    resultTitleScreen = "";

    switch (nameScreen) {
        case "LISVOL":
            resultTitleScreen = "Liste des vols";
        break;
    }

    return resultTitleScreen;
};

ScreenVolManager.prototype.prependScreen = function(nameScreen,data)
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

ScreenVolManager.prototype.saveCurrentScreen = function(idScreen) {

    html = $("#content").html();
    switch (idScreen) {
        case "LISVOL":
            this.viewContainer[0].html  = html;
        break;
    }

};

// ScreenVolManager.prototype.callBackChangeTab = function(idTab) {
//     console.log("Fire event callback screen : " + idTab);
// };

ScreenVolManager.prototype.callBackCloseTab = function(idTab) {
    console.log("Fire event callback screen : " + idTab);
};

ScreenVolManager.prototype.callBackChangeTab = function(idTab) {
    this.displayVolScreen(idTab);
    this.getListVol('LISVOL_afficher',{ lisvol: { sdd_vol: "", vol_du_jour: "0", vol_affecte: "0", vol_nonaffecte: "0", en_warning: "0", en_erreur: "0" }});
};

ScreenVolManager.prototype.displayVolScreen = function(idTab) {

    switch (idTab) {
        case "LISVOL":
            $("#content").empty();
            $("#content").append(this.viewContainer[0].html );
        break;
    }
};

ScreenVolManager.prototype.setEventListenerScreen = function() {
    var refScreenVolManager = this;
    $( "#form-screen-vol" ).submit(function( event ) {
        event.preventDefault();

        var data = {};

        $("form#form-screen-vol :input").each(function(){
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

                refScreenVolManager.dataTableManagerList.dataTableObject.destroy();
                $("#datatabledefaultbody").empty();
                refScreenVolManager.displayVol(data);

                $("#loader").css("display","none");
                $("#datatable-content").css("display","block");
            }
        });
    });
};

//!! important quand gestion de plusieurs screen
ScreenVolManager.prototype.createComponent = function(nameScreen) {
    switch (nameScreen) {
                case "LISVOL":
                        this.dataTableManagerList.createDataTable('datatable');
                break;
            }
};

ScreenVolManager.prototype.setListenerDataTable = function(nameScreen) {
    switch (nameScreen) {
                case "LISVOL":
                        this.dataTableManagerList.eventSelectRow('datatable');
                break;
            }
};

ScreenVolManager.prototype.getListVol = function (url, param) {
    var refScreenVolManager = this;
    ajaxCall(url, 'post', param, refScreenVolManager, [function (data) {
        //Success callback
        refScreenVolManager.displayVol(data);
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

ScreenVolManager.prototype.displayVol = function(data)
{
    for(i = 0; i < data.data.length; i++)
    {
        var dataListVol = data.data[i];
        var rowDataTable = '<tr id="rowlisvol'+i+'">'+
            '<td>'+dataListVol.ANO_VOL+'</td>'+
            '<td>'+dataListVol.CATEGORIE_VOL_RED+'</td>'+
            '<td>'+dataListVol.DST_VOL+'</td>'+
            '<td>'+dataListVol.D_DEBTRI_VOL+'</td>'+
            '<td>'+dataListVol.D_DEBTRI_VOL_HHMI+'</td>'+
            '<td>'+dataListVol.D_FINTRI_VOL+'</td>'+
            '<td>'+dataListVol.ESCALES+'</td>'+
            '<td>'+dataListVol.ETD_VOL+'</td>'+
            '<td>'+dataListVol.EVOL+'</td>'+
            '<td>'+dataListVol.FCMODE+'</td>'+
            '<td>'+dataListVol.D_DEBTRI_VOL_HHMI+'</td>'+
            '<td>'+dataListVol.HIGH_RISK+'</td>'+
            '<td>'+dataListVol.HIGH_RISK_RED+'</td>'+
            '<td>'+dataListVol.IATA_CIE+'</td>'+
            '<td>'+dataListVol.ID_FIMS+'</td>'+
            '<td>'+dataListVol.ID_VOL+'</td>'+
            '<td>'+dataListVol.LIG+'</td>'+
            '<td>'+dataListVol.MVTNUM+'</td>'+
            '<td>'+dataListVol.NB_CHUTES+'</td>'+
            '<td>'+dataListVol.NB_VOL_COM+'</td>'+
            '<td>'+dataListVol.ORGDEM+'</td>'+
            '<td>'+dataListVol.PARKING+'</td>'+
            '<td>'+dataListVol.SDD_VOL+'</td>'+
            '<td>'+dataListVol.STD_VOL+'</td>'+
            '<td>'+dataListVol.TERMINAL+'</td>'+
            '<td>'+dataListVol.TYPE_AVION+'</td>'+
            '<td>'+dataListVol.TYPE_VOL_RED+'</td>'+
            '<td>'+dataListVol.VOLREF_AFF+'</td>'+
            '<td>'+dataListVol.VOLREF_OACI+'</td>'+
        '</tr>';

        $("#datatabledefaultbody").append(rowDataTable);
    }
    this.createComponent(this.idTabLisVol);
};
