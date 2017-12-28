function initLoginScreen() {
  objectScreenLoginManager = new ScreenLoginManager();
}

function initAlstefIHM() {
    var screens = [
    "LISVOL",
    "LISBAG",
    "LISECR",
    "LISPFL"
  ];
  //Appel Traduction
  
  var viewContainer = {};
  objectTabManager = new TabManager();
  
  
//  objectScreenVolManager = new ScreenVolManager(objectTabManager, viewContainer);
//  objectScreenVolManager.setVolScreenClickListner();
//  objectScreenBagManager = new ScreenBagManager(objectTabManager, viewContainer);
//  objectScreenBagManager.setBagScreenClickListner();
//  
  for (var i = 0; i < screens.length; i++) {
    new ScreenManager(screens[i], objectTabManager, viewContainer);
  }
}
