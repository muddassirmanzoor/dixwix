jQuery(document).ready(function ($) {
    $("#alert-success").hide();
    window.onload = function () {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const paramName = "email";
        const paramValue = urlParams.get(paramName);
        if (paramValue === "verify") {
            $("#alert-success")
                .show()
                .append("Please verify your email address");
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    };

    $("#signup_password").on("input", function () {
        var password = $(this).val();
        var passwordMessage = $("#password-message");
        $(".strength_level").removeClass("active");
        chkArray = checkStrength(password);
        for (let i = 0; i <= chkArray["count"]; i++) {
            $(".strength-" + i).addClass("active");
        }
        if (chkArray["message"] !== "") {
            passwordMessage.html(
                '<span style="color:purple;">' + chkArray["message"] + "</span>"
            );
        }
    });

    $("#login_btn").on("click", function (event) {
        event.preventDefault();

        let email_check = $("#signin_email").val().length > 0;
        let pass_val = $("#signin_password").val().trim();
        let pass_check = pass_val.length > 0;
        let captchaResponse = grecaptcha.getResponse();

        if (email_check && pass_check && captchaResponse) {
            $("#login-form").submit();
        } else {
            let errorMessage = "";

            if (!email_check || !pass_check) {
                errorMessage += "*Email and Password should not be empty";
            }

            if (!captchaResponse) {
                errorMessage +=
                    (errorMessage ? "<br>" : "") +
                    "*Please complete the captcha.";
            }

            $("#client_error_message").html(errorMessage);
        }
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});

function checkStrength(pass) {
    let retMsg = [];
    if (pass.length == 0) {
        retMsg["count"] = -1;
        retMsg["message"] = "";
    }
    if (pass.length < 6) {
        retMsg["count"] = 0;
        retMsg["message"] = "Very Weak";
    } else {
        let regex =
            /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!^%*?&]{6,25}$/;
        if (regex.test(pass)) {
            retMsg["count"] = 5;
            retMsg["message"] = "Very Strong";
        } else {
            let count = 0;
            let regex1 = /[a-z]/;
            if (regex1.test(pass)) count++;
            let regex2 = /[A-Z]/;
            if (regex2.test(pass)) count++;
            let regex3 = /[\d]/;
            if (regex3.test(pass)) count++;
            let regex4 = /[!@#$%^&*.?]/;
            if (regex4.test(pass)) count++;
            retMsg["count"] = count;
            switch (count) {
                case 1:
                    retMsg["message"] = "Weak";
                    break;
                case 2:
                    retMsg["message"] = "Medium";
                    break;
                case 3:
                    retMsg["message"] = "Strong";
                    break;
                case 4:
                    retMsg["message"] = "Very Strong";
                    break;
            }
        }
        return retMsg;
    }
    return retMsg;
}

function enableLoginBtn() {
    document.getElementById("login_btn").disabled = false;
}
var recaptcha1;
var renderCaptchaFn = function () {
    recaptcha1 = grecaptcha.render("recaptcha1", {
        sitekey: RECAPTCHA_SITE_KEY,
        theme: "light",
        callback: "enableLoginBtn",
    });
};
function recaptchaExpired() {
    document.getElementById("login_btn").disabled = true;
}

function showSwalMessage(title, message, type) {
    swal.fire(title, message, type);
}

function showSwalMessageWithCallback(title, message, type, callbackFn) {
    Swal.fire({
        title: message,
        icon: type,
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: "Ok",
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Processing...",
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
            callbackFn();
        }
    });
}

function printDiv(titleDivId, bodyDivId) {
    var titlePrint = document.getElementById(titleDivId);
    var divToPrint = document.getElementById(bodyDivId);
    var newWin = window.open("", "Print-Window");
    newWin.document.open();
    newWin.document.write(
        '<html><body onload="window.print()">' +
            "<h3>" +
            titlePrint.innerHTML +
            "</h3>" +
            divToPrint.innerHTML +
            "</body></html>"
    );
    newWin.document.close();
}
