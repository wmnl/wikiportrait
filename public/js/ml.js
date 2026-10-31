// helper functions for the Google Vision Analysis screen
function showGVResults(e)
{
    var element = e.parentElement.childNodes[3];
    var all = document.querySelectorAll('.toon');
    if (element.style.display == "") {
        all.forEach(function (a) {
            a.parentElement.childNodes[3].style.display = "";
            a.innerText = i18n("show");
        });
        element.style.display = "block";
        e.innerText = i18n("hide");
    } else {
        element.style.display = "";
        e.innerText = i18n("show");
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
    req.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').content);
    req.send(JSON.stringify(data));
}
