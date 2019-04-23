/*jslint
    browser, long, white, this
*/

function process(json) {
    "use strict";
    
    var pizzen = JSON.parse(json);
    
    var tbody = document.getElementById("pizzen");
    tbody.innerHTML = "";
    
    pizzen.forEach(function(pizza) {
        var row = document.createElement("tr");
        
        var numberCell = document.createElement("td");
        var nameCell = document.createElement("td");
        
        var bestelltCell = document.createElement("td");
        var imofenCell = document.createElement("td");
        var fertigCell = document.createElement("td");
        
        bestelltCell.className = "center-align";
        imofenCell.className = "center-align";
        fertigCell.className = "center-align";
        
        numberCell.innerText = pizza.BestellungID;
        nameCell.innerText = pizza.PizzaName;
        
        
        var bestelltSpan;
        if (pizza.Status === "bestellt") {
            bestelltSpan = document.createElement("span");
            bestelltSpan.className = "green fa fa-square";
            bestelltCell.append(bestelltSpan);
        } else {
            bestelltSpan = document.createElement("span");
            bestelltSpan.className = "green status-select fa fa-square-o";
            bestelltCell.addEventListener("click", function() {
                window.setStatus(pizza.PizzaID, "bestellt");
                window.delayedRequest(100);
            });
            bestelltCell.append(bestelltSpan);
        }
        
        
        var imofenSpan;
        if (pizza.Status === "Im Ofen") {
            imofenSpan = document.createElement("span");
            imofenSpan.className = "green fa fa-square";
            imofenCell.append(imofenSpan);
        } else {
            imofenSpan = document.createElement("span");
            imofenSpan.className = "green status-select fa fa-square-o";
            imofenCell.addEventListener("click", function() {
                window.setStatus(pizza.PizzaID, "Im Ofen");
                window.delayedRequest(100);
            });
            imofenCell.append(imofenSpan);
        }
        
        var fertigSpan;
        if (pizza.Status === "fertig") {
            fertigSpan = document.createElement("span");
            fertigSpan.className = "green fa fa-square";
            fertigCell.append(fertigSpan);
        } else {
            fertigSpan = document.createElement("span");
            fertigSpan.className = "green status-select fa fa-square-o";
            fertigCell.addEventListener("click", function() {
                window.setStatus(pizza.PizzaID, "fertig");
                window.delayedRequest(100);
            });
            fertigCell.append(fertigSpan);
        }
        
        row.append(numberCell);
        row.append(nameCell);
        row.append(bestelltCell);
        row.append(imofenCell);
        row.append(fertigCell);
        
        tbody.append(row);
    });
}

window.onload = function() {
    window.request();
    setInterval(window.request, 1000);
};

function request() {
    "use strict";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            process(this.responseText);
        }
    };
    xhttp.open("GET", "baeckerstatus.php", true);
    xhttp.send();
}

function delayedRequest(delay) {
    "use strict";
    
    setTimeout(request, 100);
}

function setStatus(pizzaid, status) {
    "use strict";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        window.console.log("Status set");
    };
    xhttp.open("GET", "setstatus.php?pizzaid=" + pizzaid + "&status=" + status, true);
    xhttp.send();
}