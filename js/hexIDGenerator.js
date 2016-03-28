
function generateHexID() {
    var hexID = "";
    var possible = "ABCDEF0123456789";

    for( var i=1; i < 13; i++ ){
        hexID += possible.charAt(Math.floor(Math.random() * possible.length));
        /*if ((i % 4 == 0) && (i != 12)){
            hexID += '-';
        }*/
    }
    return hexID;
}

    window.setInterval(function(){
      userHexID = generateHexID();
      document.getElementById('hexid').innerHTML = userHexID;
          
    }, 500);

    
        
        
