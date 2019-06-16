/* Autoresize textarea */
// autosize($('textarea')); // narcode

/* Delete confirmation */
function confirmDelete(id) {
    if (confirm("Weet u zeker dat u dit bericht wilt verwijderen?")) {
	location.href = "deletemessage.php?id=" + id;
    }
}

/* Add :active to iOS */
document.addEventListener("touchstart", function () {}, true);

/* Do not open links in Safari when in web-app mode */
if (("standalone" in window.navigator) && window.navigator.standalone) {

    var noddy, remotes = false;

    document.addEventListener('click', function (event) {

	noddy = event.target;

	while (noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
	    noddy = noddy.parentNode;
	}

	if ('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)) {
	    event.preventDefault();
	    document.location.href = noddy.href;
	}

    }, false);
}

/* Prevent more than 1 click on upload button */
function disableButton(e) {
  f = document.querySelector('form');
    if (!f.checkValidity()) {
    f.reportValidity();
  } else {
    e.disabled = true;
    p = e.parentElement;
    e.remove();
    img = document.createElement('img');
    img.style.display = 'inline';
    img.style.height = '42px';
    img.src = 'js/loading.gif';
    p.append(img);
    f.submit();
  };
}
