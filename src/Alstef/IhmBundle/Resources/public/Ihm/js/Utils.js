// callbackArray => functionResult,functionError,fonctionComplete,fonctionBeforeSend
function ajaxCall(url, httpMethod, param, callerObject, ajaxCallbackArray) {
    $.ajax({
        type: httpMethod,
        url: url,
        dataType: 'json',
        async: true,
        data: param,
        success: function(data) {
            if (ajaxCallbackArray[0] != null || ajaxCallbackArray[0] != undefined)
                ajaxCallbackArray[0](data, callerObject);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (ajaxCallbackArray[1] != null || ajaxCallbackArray[1] != undefined)
                ajaxCallbackArray[1](jqXHR, textStatus, errorThrown, callerObject);
        },
        complete: function(jqXHR, textStatus) {
            if (ajaxCallbackArray[2] != null || ajaxCallbackArray[2] != undefined)
                ajaxCallbackArray[2](jqXHR, textStatus, callerObject);
        },
        beforeSend: function(jqXHR, settings) {
            if (ajaxCallbackArray[3] != null || ajaxCallbackArray[3] != undefined)
                ajaxCallbackArray[3](jqXHR, settings, callerObject);
        }
    });

};

function createRandomRGBAValue(opacity) {
    var back = ["#22A7F0", "#8E44AD", "#AEA8D3", "#F62459", "#DB0A5B", "#D64541", "#D2527F", "#2C3E50", "#1E8BC3", "#87D37C", "#4ECDC4", "#3FC380", "#E87E04", "#F9690E", "#F9BF3B"];
    rand = back[Math.floor(Math.random() * back.length)];
    hex = rand.replace('#', '');
    r = parseInt(hex.substring(0, 2), 16);
    g = parseInt(hex.substring(2, 4), 16);
    b = parseInt(hex.substring(4, 6), 16);

    // Add Opacity to RGB to obtain RGBA
    var result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
    return result;
}

function resolutionChecker() {
    $(window).resize(function() {
        widthWindows = window.innerWidth;
        heightWindows = window.innerHeight;
        console.log(widthWindows);
        console.log(heightWindows);
    });
}

function getHost() {
    //var port = ':8000';
    var port = '';
    var complement = '';
    var l = document.createElement("a");
    l.href = window.location.href;
    return 'http://' + l.hostname + port + complement;
}

function daydiff(firstdate, seconddate) {

    mdyfirst = firstdate.split('/');
    first = new Date(mdyfirst[2], mdyfirst[1]-1, mdyfirst[0]);
    
    mdysecond = seconddate.split('/');
    second = new Date(mdysecond[2], mdysecond[1]-1, mdysecond[0]);
    
    return Math.round((second-first)/(1000*60*60*24));
};

function incrementMonth(startPeriod) {
    splitStartPeriod = startPeriod.split('/');
    dateStartPeriod = new Date(splitStartPeriod[2], splitStartPeriod[1]-1, splitStartPeriod[0]);
    resultDate = new Date(new Date(dateStartPeriod).setMonth(dateStartPeriod.getMonth()+1));
    return resultDate.format("dd/mm/yyyy");
}

function incrementDay(startPeriod) {
    splitStartPeriod = startPeriod.split('/');
    dateStartPeriod = new Date(splitStartPeriod[2], splitStartPeriod[1]-1, splitStartPeriod[0]);
    resultDate = new Date(new Date(dateStartPeriod).setDate(dateStartPeriod.getDate()+1));
    return resultDate.format("dd/mm/yyyy");
}

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
