/*jslint
    browser, long, white, this
*/

function process(json) {
    "use strict";
    
    var bestellungen = JSON.parse(json);
    
    var tbody = document.getElementById("bestellungen");
    tbody.innerHTML = "";
    
    bestellungen.forEach(function(bestellung) {
        var row = document.createElement("tr");
        
        var numberCell = document.createElement("td");
        var nameCell = document.createElement("td");
        var pizzaCell = document.createElement("td");
        
        var fertigCell = document.createElement("td");
        var unterwegsCell = document.createElement("td");
        var geliefertCell = document.createElement("td");
        
        fertigCell.className = "center-align";
        unterwegsCell.className = "center-align";
        geliefertCell.className = "center-align";
        
        numberCell.innerText = bestellung.BestellungID;
        nameCell.innerText = bestellung.Adresse;
        
        bestellung.Pizzen.forEach(function(p) {
            var paragraph = document.createElement("p");
            paragraph.innerText = p.PizzaName;
            pizzaCell.append(paragraph);
        });
        
        var status = "fertig";
        
        if (bestellung.Pizzen.length > 0) {
            status = bestellung.Pizzen[0].Status;
        }
        
        var sp;
        if (status === "fertig") {
            sp = document.createElement("span");
            sp.className = "green fa fa-square";
            fertigCell.append(sp);
        } else {
            sp = document.createElement("span");
            sp.className = "green status-select fa fa-square-o";
            fertigCell.addEventListener("click", function() {
                window.setOrderStatus(bestellung.BestellungID, "fertig");
                window.delayedRequest(100);
            });
            fertigCell.append(sp);
        }
        
        if (status === "unterwegs") {
            sp = document.createElement("span");
            sp.className = "green fa fa-square";
            unterwegsCell.append(sp);
        } else {
            sp = document.createElement("span");
            sp.className = "green status-select fa fa-square-o";
            unterwegsCell.addEventListener("click", function() {
                window.setOrderStatus(bestellung.BestellungID, "unterwegs");
                window.delayedRequest(100);
            });
            unterwegsCell.append(sp);
        }
        
        if (status === "geliefert") {
            sp = document.createElement("span");
            sp.className = "green fa fa-square";
            geliefertCell.append(sp);
        } else {
            sp = document.createElement("span");
            sp.className = "green status-select fa fa-square-o";
            geliefertCell.addEventListener("click", function() {
                window.setOrderStatus(bestellung.BestellungID, "geliefert");
                window.delayedRequest(100);
            });
            geliefertCell.append(sp);
        }
        
        row.append(numberCell);
        row.append(nameCell);
        row.append(pizzaCell);
        row.append(fertigCell);
        row.append(unterwegsCell);
        row.append(geliefertCell);
        
        tbody.append(row);
    });
}

window.onload = function() {
    window.request();
    window.setInterval(window.request, 5000);
};

function request() {
    "use strict";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            window.process(this.responseText);
        }
    };
    xhttp.open("GET", "fahrerstatus.php", true);
    xhttp.send();
}

function delayedRequest(delay) {
    "use strict";
    
    setTimeout(window.request, delay);
}

function setOrderStatus(bestellungid, status) {
    "use strict";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        window.console.log("Status set");
    };
    xhttp.open("GET", "setorderstatus.php?bestellungid=" + bestellungid + "&status=" + status, true);
    xhttp.send();
}