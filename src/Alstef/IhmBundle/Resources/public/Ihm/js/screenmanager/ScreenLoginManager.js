var ScreenLoginManager = function() {
    this.loaderView = $('#loaderView');
    this.formLogin = $('#login-form');
    this.divForms = $('#div-forms');
    this.modalAnimateTime = 300;
    this.msgAnimateTime = 150;
    this.msgShowTime = 3000;

    this.initRumble();

    var refScreenLoginManager = this;

};

ScreenLoginManager.prototype.modalAnimate = function(oldForm, newForm) {
    var oldH = oldForm.height();
    var newH = newForm.height();
    var refScreenLoginManager = this;
    this.divForms.css("height", oldH);
    oldForm.fadeToggle(this.modalAnimateTime, function() {
        refScreenLoginManager.divForms.animate({
            height: newH
        }, refScreenLoginManager.modalAnimateTime, function() {
            newForm.fadeToggle(refScreenLoginManager.modalAnimateTime);
        });
    });
};

ScreenLoginManager.prototype.msgFade = function(msgId, msgText) {
    var refScreenLoginManager = this;
    msgId.fadeOut(this.msgAnimateTime, function() {
        $(this).text(msgText).fadeIn(refScreenLoginManager.msgAnimateTime);
    });
};

ScreenLoginManager.prototype.msgChange = function(divTag, iconTag, textTag, divClass, iconClass, msgText) {
    var msgOld = divTag.text();
    var refScreenLoginManager = this;
    this.msgFade(textTag, msgText);
    divTag.addClass(divClass);
    iconTag.removeClass("glyphicon-chevron-right");
    iconTag.addClass(iconClass + " " + divClass);
    setTimeout(function() {
        refScreenLoginManager.msgFade(textTag, msgOld);
        divTag.removeClass(divClass);
        iconTag.addClass("glyphicon-chevron-right");
        iconTag.removeClass(iconClass + " " + divClass);
    }, this.msgShowTime);
};

ScreenLoginManager.prototype.initRumble = function() {
    $('#login-modal').jrumble({
        x: 2,
        y: 0,
        rotation: 0
    });
};

ScreenLoginManager.prototype.triggerRumble = function() {
    var demoTimeout;
    clearTimeout(demoTimeout);
    $('#login-modal').trigger('startRumble');
    demoTimeout = setTimeout(function() {
        $('#login-modal').trigger('stopRumble');
    }, 500);
};


$(function() {
  $('#form_language').change(function(){
    $.ajax({
      url : 'login',
      type : 'GET',
      data : '_locale='+$(this).val(),
      dataType : 'json',
      success : function(data, statut){
        $("label[for='form_language']").html(data.language);
        $('#form_name').attr("placeholder", data.name);
        $('#form_password').attr("placeholder", data.password);
        $('#btnLogin').html(data.submit);
      },
      error : function(resultat, statut, erreur){
        alert ("ERR. resultat = " + resultat + ", statut = " + statut + ", erreur = " + erreur);
      },
      complete : function(resultat, statut){

      }
    });
});
});
