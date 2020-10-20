// helper functions for the Google Vision Analysis screen
function showGVResults(e)
{
    var element = e.parentElement.childNodes[3];
    var all = document.querySelectorAll('.toon');
    if (element.style.display == "") {
        all.forEach(function (a) {
            a.parentElement.childNodes[3].style.display = "";
            a.innerText = "tonen";
        });
        element.style.display = "block";
        e.innerText = "verbergen";
    } else {
        element.style.display = "";
        e.innerText = "tonen";
    }
}

function performGVResults(foto_id)
{
    var resp = document.querySelector('#ml_resp');
    var req = new XMLHttpRequest();
    var data = {};

    data['id'] = foto_id;

    console.log(foto_id);
    resp.innerHTML = "<img src='../js/loading.gif' height='42px' style='width: auto'\>";

    req.onreadystatechange = function () {
        if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
            resp.innerHTML = this.responseText;
        }
    }

    req.open('POST', '../ml/gvRequest.php', true);
    req.setRequestHeader("Content-Type", 'application/json; charset=UTF-8');
    req.send(JSON.stringify(data));
}
