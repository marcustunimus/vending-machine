function fetchRequest(requestMethod, requestURL) {
    return fetch(requestURL, {
        method: requestMethod,
        headers: {
            'Content-Type': 'application/json',
            "X-CSRF-Token": document.head.querySelector("[name~=csrf-token][content]").content
        }
    }).then(function (response) {
        return response.json();
    });
}



function insertCoin(id, value, currencyCode) {
    let key = document.getElementById("vendingMachine-key");
    let displayMessage = document.getElementById("display-message");
    let balanceAmount = document.getElementById("balance-amount");

    fetchRequest(
        "POST", "/api/vending-machines/" + id + "/balance/coins" + "?key=" + key.innerHTML + "&value=" + value + "&currencyCode=" + currencyCode
    ).then(function (data) {
        console.log(data);

        displayMessage.innerHTML = data["extraInfo"];
        balanceAmount.innerHTML = data["formattedBalance"];
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to get vending machine.");
    });
}

function purchaseItem(vendingMachineId, itemId) {
    let key = document.getElementById("vendingMachine-key");
    let displayMessage = document.getElementById("display-message");
    let balanceAmount = document.getElementById("balance-amount");

    fetchRequest(
        "POST", "/api/vending-machines/" + vendingMachineId + "/balance/purchaseItems/" + itemId + "?key=" + key.innerHTML
    ).then(function (data) {
        console.log(data);

        displayMessage.innerHTML = data["extraInfo"];
        balanceAmount.innerHTML = data["formattedBalance"];
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to get vending machine.");
    });
}

function returnBalance(id) {
    let key = document.getElementById("vendingMachine-key");
    let displayMessage = document.getElementById("display-message");
    let balanceAmount = document.getElementById("balance-amount");

    fetchRequest(
        "POST", "/api/vending-machines/" + id + "/balance/return" + "?key=" + key.innerHTML
    ).then(function (data) {
        console.log(data);

        displayMessage.innerHTML = data["extraInfo"];
        balanceAmount.innerHTML = data["formattedBalance"];
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to get vending machine.");
    });
}



function redirectToVendingMachinePage(id) {
    window.location.href = "/vending-machines/" + id;
}


function addCurrency() {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let code = document.getElementById("add-currency-code");
    let euroRate = document.getElementById("add-currency-euro_rate");
    let format = document.getElementById("add-currency-format");

    fetchRequest(
        "POST", "/api/currencies" + "?ADMIN_API_KEY=" + adminApiKey + "&code=" + code.value + "&euro_rate=" + euroRate.value + "&format=" + format.value
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to add currency. Error: " + data["error"]);
        }
        else {
            location.reload(true);
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to add currency.");
    });
}

function updateCurrency(code) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let newEuroRate = document.getElementById("currency-" + code + "-euro_rate");
    let newFormat = document.getElementById("currency-" + code + "-format");

    fetchRequest(
        "PATCH", "/api/currencies/" + code + "?ADMIN_API_KEY=" + adminApiKey + "&euro_rate=" + newEuroRate.value + "&format=" + newFormat.value
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to update currency with id: " + id);
        }
        else {
            alert("Successfully updated currency with id: " + id);
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to update currency with id: " + id);
    });
}

function deleteCurrency(code) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let currencyContainer = document.getElementById("currency-" + code);

    fetchRequest(
        "DELETE", "/api/currencies/" + code + "?ADMIN_API_KEY=" + adminApiKey
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to delete currency with id: " + id + ". Error: " + data["error"]);
        }
        else if ("success" in data) {
            currencyContainer.remove();
        }
        else {
            alert("Unknown error.");
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to delete currency with id: " + id);
    });
}



function addVendingMachine() {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let currencyCode = document.getElementById("add-vendingMachine-currency_code");
    let key = document.getElementById("add-vendingMachine-key");
    let location = document.getElementById("add-vendingMachine-location");
    let balanceAmount = document.getElementById("add-vendingMachine-balance_amount");

    fetchRequest(
        "POST", "/api/vending-machines" + "?ADMIN_API_KEY=" + adminApiKey + "&currency_code=" + currencyCode.value + "&key=" + key.value + "&location=" + location.value + "&balance_amount=" + balanceAmount.value
    ).then(function (data) {
        console.log(data);

        location.reload(true);
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to add vending machine.");
    });
}

function updateVendingMachine(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let newCurrencyCode = document.getElementById("vendingMachine-" + id + "-currency_code");
    let newKey = document.getElementById("vendingMachine-" + id + "-key");
    let newLocation = document.getElementById("vendingMachine-" + id + "-location");
    let newBalanceAmount = document.getElementById("vendingMachine-" + id + "-balance_amount");

    fetchRequest(
        "PATCH", "/api/vending-machines/" + id + "?ADMIN_API_KEY=" + adminApiKey + "&currency_code=" + newCurrencyCode.value + "&key=" + newKey.value + "&location=" + newLocation.value + "&balance_amount=" + newBalanceAmount.value,
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to update vending machine with id: " + id);
        }
        else {
            alert("Successfully updated vending machine with id: " + id);
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to update vending machine with id: " + id);
    });
}

function deleteVendingMachine(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let vendingMachineContainer = document.getElementById("vendingMachine-" + id);

    fetchRequest(
        "DELETE", "/api/vending-machines/" + id + "?ADMIN_API_KEY=" + adminApiKey
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to delete vending machine with id: " + id + ". Error: " + data["error"]);
        }
        else {
            vendingMachineContainer.remove();
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to delete vending machine with id: " + id);
    });
}



function addCoinStack() {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let vendingMachineId = document.getElementById("add-coinStack-vending_machine_id");
    let type = document.getElementById("add-coinStack-type");
    let remainingAmount = document.getElementById("add-coinStack-remaining_amount");

    fetchRequest(
        "POST", "/api/coin-stacks" + "?ADMIN_API_KEY=" + adminApiKey + "&vending_machine_id=" + vendingMachineId.value + "&type=" + type.value + "&remaining_amount=" + remainingAmount.value
    ).then(function (data) {
        console.log(data);

        location.reload(true);
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to add coin stack.");
    });
}

function updateCoinStack(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let newVendingMachineId = document.getElementById("coinStack-" + id + "-vending_machine_id");
    let newType = document.getElementById("coinStack-" + id + "-type");
    let newRemainingAmount = document.getElementById("coinStack-" + id + "-remaining_amount");

    fetchRequest(
        "PATCH", "/api/coin-stacks/" + id + "?ADMIN_API_KEY=" + adminApiKey + "&vending_machine_id=" + newVendingMachineId.value + "&type=" + newType.value + "&remaining_amount=" + newRemainingAmount.value
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to update coin stack with id: " + id);
        }
        else {
            alert("Successfully updated coin stack with id: " + id);
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to update coin stack with id: " + id);
    });
}

function deleteCoinStack(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let coinStackContainer = document.getElementById("coinStack-" + id);

    fetchRequest(
        "DELETE", "/api/coin-stacks/" + id + "?ADMIN_API_KEY=" + adminApiKey
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to delete coin stack with id: " + id + ". Error: " + data["error"]);
        }
        else {
            coinStackContainer.remove();
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to delete coin stack with id: " + id);
    });
}



function addItem() {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let vendingMachineId = document.getElementById("add-item-vending_machine_id");
    let name = document.getElementById("add-item-name");
    let price = document.getElementById("add-item-price");
    let remainingQuantity = document.getElementById("add-item-remaining_quantity");

    fetchRequest(
        "POST", "/api/items" + "?ADMIN_API_KEY=" + adminApiKey + "&vending_machine_id=" + vendingMachineId.value + "&name=" + name.value + "&price=" + price.value + "&remaining_quantity=" + remainingQuantity.value
    ).then(function (data) {
        console.log(data);

        location.reload(true);
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to add item.");
    });
}

function updateItem(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let newVendingMachineId = document.getElementById("item-" + id + "-vending_machine_id");
    let newName = document.getElementById("item-" + id + "-name");
    let newPrice = document.getElementById("item-" + id + "-price");
    let newRemainingQuantity = document.getElementById("item-" + id + "-remaining_quantity");
    
    fetchRequest(
        "PATCH", "/api/items/" + id + "?ADMIN_API_KEY=" + adminApiKey + "&vending_machine_id=" + newVendingMachineId.value + "&name=" + newName.value + "&price=" + newPrice.value + "&remaining_quantity=" + newRemainingQuantity.value
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to update item with id: " + id);
        }
        else {
            alert("Successfully updated item with id: " + id);
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to update item with id: " + id);
    });
}

function deleteItem(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let itemContainer = document.getElementById("item-" + id);

    fetchRequest(
        "DELETE", "/api/items/" + id + "?ADMIN_API_KEY=" + adminApiKey
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to delete item with id: " + id + ". Error: " + data["error"]);
        }
        else {
            itemContainer.remove();
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to delete item with id: " + id);
    });
}



function addLog() {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let vendingMachineId = document.getElementById("add-log-vending_machine_id");
    let type = document.getElementById("add-log-type");
    let text = document.getElementById("add-log-text");

    fetchRequest(
        "POST", "/api/logs" + "?ADMIN_API_KEY=" + adminApiKey + "&vending_machine_id=" + vendingMachineId.value + "&type=" + type.value + "&text=" + text.value
    ).then(function (data) {
        console.log(data);

        location.reload(true);
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to add log.");
    });
}

function updateLog(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let newVendingMachineId = document.getElementById("log-" + id + "-vending_machine_id");
    let newType = document.getElementById("log-" + id + "-type");
    let newText = document.getElementById("log-" + id + "-text");

    fetchRequest(
        "PATCH", "/api/logs/" + id + "?ADMIN_API_KEY=" + adminApiKey + "&vending_machine_id=" + newVendingMachineId.value + "&type=" + newType.value + "&text=" + newText.value
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to update log with id: " + id);
        }
        else {
            alert("Successfully updated log with id: " + id);
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to update log with id: " + id);
    });
}

function deleteLog(id) {
    let adminApiKey = new URLSearchParams(window.location.search).get("ADMIN_API_KEY");
    let logContainer = document.getElementById("log-" + id);

    fetchRequest(
        "DELETE", "/api/logs/" + id + "?ADMIN_API_KEY=" + adminApiKey
    ).then(function (data) {
        console.log(data);

        if ("error" in data) {
            alert("Failed to delete log with id: " + id + ". Error: " + data["error"]);
        }
        else {
            logContainer.remove();
        }
    }).catch(function (error) {
        console.log(error);

        alert("An error occurred trying to delete log with id: " + id);
    });
}
