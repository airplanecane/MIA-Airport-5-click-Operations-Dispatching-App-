function ajaxOnLoad() {
    errorDiv = document.getElementById('errorModal');
    if (errorDiv != null) {
        console.log("error div found");
        $('#errorModal').modal('show');
    }

    window.setInterval(checkUpdate, 1000);
}



var currentUpdate;
var lastUpdate;

// Function to update the messages in real time
function checkUpdate() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", './counter.json', true);
    xhr.onload = function(e) {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                currentUpdate = JSON.parse(xhr.response.toString());
                currentUpdate = currentUpdate['time'];

                if (lastUpdate < currentUpdate) {
                    location.reload();
                }
                lastUpdate = currentUpdate;

            } else {
                console.error(xhr.statusText);
            }
        }
    };
    xhr.onerror = function(e) {
        console.error(xhr.statusText);
    };
    xhr.send(null);



}