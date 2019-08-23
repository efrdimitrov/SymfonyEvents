
// SELECTING ALL TEXT ELEMENTS
var username = document.forms['vform']['user[username]'];
var email = document.forms['vform']['user[email]'];
var password = document.forms['vform']['user[password][first]'];
var password_confirm = document.forms['vform']['user[password][second]'];
// SELECTING ALL ERROR DISPLAY ELEMENTS
var name_error = document.getElementById('name_error');
var email_error = document.getElementById('email_error');
var password_error = document.getElementById('password_error');
var password_confirm_error = document.getElementById('password_confirm_error');
// SETTING ALL EVENT LISTENERS
username.addEventListener('blur', nameVerify, true);
email.addEventListener('blur', emailVerify, true);
password.addEventListener('blur', passwordVerify, true);

// validation function
function Validate() {

    checkError = false;

    // validate username
    if (username.value == "") {
        username.style.border = "2px solid red";
        document.getElementById('username_div').style.color = "red";
        name_error.textContent = "Username is required";
        username.focus();
        checkError = false;
    }
    else {
        // validate username
        if (username.value.length < 4) {
            username.style.border = "2px solid red";
            document.getElementById('username_div').style.color = "red";
            name_error.textContent = "Username \"" + username.value + "\" must be at least 4 characters";
            username.focus();
            checkError = false;
        }
        var usernameValidator = /^[A-Za-z0-9]+$/;
        if (!username.value.match(usernameValidator) || username.value.length < 4) {
            username.style.border = "2px solid red";
            document.getElementById('username_div').style.color = "red";
            if (username.value.length < 4) {
                name_error.textContent = "Username must be at least 4 characters";
            } else {
                name_error.textContent = "Username \"" + username.value + "\" is not a valid. Only letters and digits.";
            }
            username.focus();
            checkError = false;
        } else {
            username.style.border = "2px solid green";
            document.getElementById('username_div').style.color = "green";
            name_error.textContent = "";
            checkError = true;
        }
    }

    // validate email
    if (email.value == "") {
        email.style.border = "2px solid red";
        document.getElementById('email_div').style.color = "red";
        email_error.textContent = "Email is required";
        email.focus();
        checkError = false;
    }
    // validate email
    var emailValidator = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!email.value.match(emailValidator) && email.value.length > 0) {
        email.style.border = "2px solid red";
        document.getElementById('email_div').style.color = "red";
        email_error.textContent = "Email \"" + email.value + "\" is not a valid address";
        email.focus();
        checkError = false;
    }
    else if(email.value != "") {
        email.style.border = "2px solid green";
        document.getElementById('email_div').style.color = "green";
        email_error.textContent = "";
    }


    // validate password
    if (password.value == "") {
        password.style.border = "2px solid red";
        document.getElementById('password_div').style.color = "red";
        password_error.textContent = "Password is required";
        password.focus();
        checkError = false;
    }

    // validate password
    if (password.value.length < 3) {
        password.style.border = "2px solid red";
        document.getElementById('password_div').style.color = "red";
        password_error.textContent = "Enter more " + (3 - password.value.length) + " characters(Password must be at least 3 characters)";
        password.focus();
        checkError = false;
    }
    else {
        password.style.border = "2px solid green";
        document.getElementById('password_div').style.color = "green";
        password_confirm.style.border = "2px solid green";
        password_error.textContent = "";

        if (password.value != password_confirm.value) {
            document.getElementById('pass_confirm_div').style.color = "red";
            password_confirm.style.border = "2px solid red";
            password_confirm_error.innerHTML = "The two passwords do not match";
            checkError = false;
        }
        else{
            document.getElementById('pass_confirm_div').style.color = "green";
            password_confirm.style.border = "2px solid green";
            password_confirm_error.innerHTML = "";
        }
    }
    return checkError;

}