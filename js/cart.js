/*jslint
    browser, long, white, this
*/

var cart = [];
var cartTotal = 0;
var orderID = 0;

function orderItem(name, price) {
    "use strict";
    var obj = {};
    
    obj.name = name;
    obj.price = price;
    obj.orderID = orderID;
    orderID+=1;
    
    return obj;
}

function renderCart() {
    "use strict";
    document.getElementById("total").innerText = cartTotal.toFixed(2).replace(".", ",");
    
    var tableBody = document.getElementById("cart");
    
    tableBody.innerHTML = "";
    
    cart.forEach(function(orderItem) {
      var tableRow = document.createElement("tr");

      var nameCell = document.createElement("td");
      var priceCell = document.createElement("td");
      var buttonCell = document.createElement("td");
      
      tableBody.append(tableRow);
      
      tableRow.append(nameCell);
      tableRow.append(priceCell);
      tableRow.append(buttonCell);
      
      nameCell.innerText = orderItem.name;
      //Price is formatted to two decimals, the . replaced with ,
      priceCell.innerText = orderItem.price.toFixed(2).replace(".", ",") + " €";
      
      var button = document.createElement("button");
      button.className = "btn btn-danger";
      button.innerHTML = "<span class='fa fa-trash'></span> Entfernen";
      
      button.addEventListener("click", function() {
        window.console.log("Removing order item " + orderItem.orderID);
        window.removeFromCart(orderItem.orderID);
      });
      
      buttonCell.append(button);
    });
}

function addToCart(pizzaName, price) {
    "use strict";
    cart.push(orderItem(pizzaName, price));
    cartTotal += price;
    
    renderCart();
}

function removeFromCart(id) {
    "use strict";
    cart.forEach(function(item,i) {
        if (item.orderID === id) {
            cartTotal -= item.price;
            cart.splice(i,1);
        }
    });
    
    renderCart();
}

function sendOrder() {
    "use strict";
    var error = document.getElementById("error-message");
    var addr = document.getElementById("address-input").value;
    var hidden = document.getElementById("order-input");

    if (cart.length === 0) {
        error.innerText = "Ihr Warenkorb ist leer. Bitte fügen Sie etwas zu Ihrem Warenkorb hinzu, bevor Sie die Bestellung aufgeben.";
        return false;
    }
    
    if (addr.length === 0) {
        error.innerText = "Bitte geben Sie die Adresse an, zu der Ihre Bestellung geliefert werden soll";
        return false;
    }
    
    var orderNames = [];
    cart.forEach(function(orderItem) {
      orderNames.push(orderItem.name);
    });
    
    hidden.value = orderNames.join(",");
    
    return true;
}