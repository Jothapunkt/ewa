/*jslint
    browser, long, white, this
*/

function request() {
    "use strict";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            window.console.log("Reset all data");
        }
    };
    xhttp.open("GET", "resetorders.php", true);
    xhttp.send();
}