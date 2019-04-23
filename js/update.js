function process(json) {
    "use strict";
    
    var pizzen = JSON.parse(json);
    var tbody = document.getElementById("pizzen");
    tbody.innerHTML = "";
    
    pizzen.forEach(function(pizza) {
        var row = document.createElement("tr");
        
        var nameCell = document.createElement("td");
        var statusCell = document.createElement("td");
        
        nameCell.innerText = pizza.PizzaName;
        statusCell.innerText = pizza.Status;
        
        row.append(nameCell);
        row.append(statusCell);
        
        tbody.append(row);
    });
}

window.onload = function() {
    window.request();
    setInterval(window.request, 2000);
};

function request() {
    "use strict";
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            process(this.responseText);
        }
    };
    xhttp.open("GET", "kundenstatus.php", true);
    xhttp.send();
}