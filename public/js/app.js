/* Autoresize textarea */
autosize($('textarea'));

/* Delete confirmation */
function confirmDelete(id) {
    if (confirm("Weet u zeker dat u dit bericht wilt verwijderen?")) {
        location.href = "deletemessage.php?id=" + id;
    }
}

/* Add :active to iOS */
document.addEventListener("touchstart", function () { }, true);

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
        e.style.display = 'none';
        img = document.createElement('img');
        img.style.display = 'inline';
        img.style.height = '42px';
        img.src = 'js/loading.gif';
        img.style.zindex = 2;
        p.appendChild(img);
        setTimeout(function () {
            f.submit();
        }, 100);
    }
}
$('body').on('change', '#bot', function () {
    if ($('#bot').is(":checked")) {
        $('#key').parent('.input-container').removeClass('d-none');
        $('#key').val(generateRandomPasswordSelection(16));
    } else {
        $('#key').parent('.input-container').addClass('d-none');
    }
});
const generateRandomPasswordSelection = (length) => {
    const uppercase = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
    const lowercase = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    const special = ['~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '-', '=', '{', '}', '[', ']', ':', ';', '?', ', ', '.', '|', '\\'];
    const numbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    const nonSpecial = [...uppercase, ...lowercase, ...numbers];

    let password = '';

    for (let i = 0; i < length; i++) {
        // Previous character is a special character
        if (i !== 0 && special.includes(password[i - 1])) {
            password += getRandomElement(nonSpecial);
        } else password += getRandomElement([...nonSpecial, ...special]);
    }

    return password;
}
const getRandomElement = arr => {
    const rand = Math.floor(Math.random() * arr.length);
    return arr[rand];
}