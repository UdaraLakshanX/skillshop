function createAccount() {

    var fname = document.getElementById("signup-firstname").value;
    var lname = document.getElementById("signup-lastname").value;
    var email = document.getElementById("signup-email").value;
    var password = document.getElementById("signup-password").value;
    var cpassword = document.getElementById("signup-confirm").value;
    var accountTypeSeller = document.getElementById("account_type_seller");
    var accountTypeBuyer = document.getElementById("account_type_buyer");
    var termsConditions = document.getElementById("terms_conditions");
    var signupErrorMessage = document.getElementById("signup-message");
    signupErrorMessage.classList.remove("hidden");

    if (!fname || !lname || !email || !password || !cpassword) {
        signupErrorMessage.innerHTML = "All fields are required!";
    } else if (password.length < 8) {
        signupErrorMessage.innerHTML = "Password must be at least 8 characters";
    } else if (password != cpassword) {
        signupErrorMessage.innerHTML = "Password do not match";
    } else if (!accountTypeSeller.checked && !accountTypeBuyer.checked) {
        signupErrorMessage.innerHTML = "Please select account type";
    } else if (!termsConditions.checked) {
        signupErrorMessage.innerHTML = "Please read and check I agree to the Terms & Conditions";
    } else {

        signupErrorMessage.classList.add("hidden");

        var form = new FormData();
        form.append("fname", fname);
        form.append("lname", lname);
        form.append("email", email);
        form.append("password", password);
        form.append("pass_confirm", cpassword);
        form.append("account_type", accountTypeSeller.checked ? accountTypeSeller.value : accountTypeBuyer.value);
        form.append("termsConditions", termsConditions.checked);

        var r = new XMLHttpRequest();

        r.onreadystatechange = function () {
            if (r.readyState == 4) {
                signupErrorMessage.classList.remove("hidden");
                if (r.status == 200) {

                    if (r.responseText == "success") {
                        signupErrorMessage.classList.remove("text-red-500");
                        signupErrorMessage.classList.add("text-green-500");
                        signupErrorMessage.innerHTML = "Registration Successfull!";
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        signupErrorMessage.innerHTML = r.responseText;
                    }

                } else {
                    signupErrorMessage.innerHTML = "Request failed! : " + r.responseText;
                }
            } 
        }
        r.open("POST", "process/createAccountProcess.php", true);
        r.send(form);
    }
}

function signIn(){
    
    var email = document.getElementById("signin-email").value;
    var password = document.getElementById("signin-password").value;
    var rememberMe = document.getElementById("remember");
    var signinErrorMessage = document.getElementById("signin-message");
    signinErrorMessage.classList.remove("hidden");

    if (!email || !password) {
        signinErrorMessage.innerHTML = "All fields are required!";
    }  else if (!validateEmail(email)) {
        signinErrorMessage.innerHTML = "Invalid email format!";
    } else {
        signinErrorMessage.classList.add("hidden");

        var form = new FormData();
        form.append("email",email);
        form.append("password",password);
        form.append("rememberMe", rememberMe.checked ? "true" : "false");

        var r = new XMLHttpRequest();

        r.onreadystatechange =  function (){
            if(r.readyState == 4){
                signinErrorMessage.classList.remove("hidden");
                if(r.status == 200){
                    if(r.responseText == "success"){
                        signinErrorMessage.classList.remove("text-red-500");
                        signinErrorMessage.classList.add("text-green-500");
                        signinErrorMessage.innerHTML = "Login successfull! Redirecting..";
                        setTimeout(()=>{
                            window.location.href = "home.php";
                        },2000);
                    } else {
                        signinErrorMessage.innerHTML = r.responseText;
                    }
                } else {
                    signinErrorMessage.innerHTML = "Request failed! : " + r.responseText;
                }
            }
        }
        r.open("POST","process/loginProcess.php",true);
        r.send(form);
    }
}

function validateEmail(email){
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function forgotPassword(){
    var email = document.getElementById("forgot-email").value;
    var msg = document.getElementById("forgot-message");
    msg.classList.remove("hidden");
    var sendBtn = document.getElementById("forgot-password-send-code-btn");

    if(!email || !validateEmail(email)){
        msg.className = "text-red-500 text-sm rounded-lg mb-2 p-2";
        msg.innerHTML = "Invalid email";
    } else {

        var form = new FormData();
        form.append("email",email);

        msg.className = "text-blue-500 text-sm rounded-lg mb-2 p-2";
        msg.innerHTML = "Sending.. <span class='inline-block animate-spin'>⏳</span>";
        sendBtn.disabled = true;
        sendBtn.style.opacity = "0.6";

        var r = new XMLHttpRequest();

        r.open("POST","process/forgotPasswordProcess.php",true);

        r.onload = ()=>{
            sendBtn.disabled = false;
            sendBtn.style.opacity = "1";
            var response = r.responseText;
            msg.classList.remove("hidden");
            msg.className = "text-sm rounded-lg mb-2 p-2 " + (response == "success" ? "text-green-500" : "text-red-500");
            msg.innerHTML = response == "success" ? "✓ Code sent to your email!" : response;
            if(response == "success") setTimeout(()=>{
                document.getElementById("forgot-step-1").classList.add("hidden");
                document.getElementById("forgot-step-2").classList.remove("hidden");
                document.getElementById("verify-message").classList.add("hidden");
            },1500);
        };

        r.onerror = ()=>{
            sendBtn.disabled = false;
            sendBtn.style.opacity = "1";
            msg.classList.remove("hidden");
            msg.className = "text-red-500 text-sm rounded-lg mb-2 p-2";
            msg.innerHTML = "Network error. Please try again.";
        };
        r.send(form);
    }
}

function verifyCode() {
    var code = document.getElementById("verify-code").value;
    var email = document.getElementById("forgot-email").value;
    var msg = document.getElementById("verify-message");
    var verifyBtn = document.getElementById("forgot-password-verify-code-btn");
    
    msg.classList.remove("hidden");

    var regex = /^\d+$/;
    
    if (code.length !== 6 || !regex.test(code)) {
        msg.className = "mb-4 p-3 rounded-lg text-sm text-red-500";
        msg.innerHTML = "Enter exactly 6 digits!";
    } else {
        msg.className = "mb-4 p-3 rounded-lg text-sm text-blue-500";
        msg.innerHTML = "Verifying... <span class='inline-block animate-spin'>⏳</span>";
        verifyBtn.disabled = true;
        verifyBtn.style.opacity = "0.6";
        
        var form = new FormData();
        form.append("email", email);
        form.append("code", code);
        form.append("action", "verify");
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "process/resetPasswordProcess.php", true);
        xhr.onload = () => {
            verifyBtn.disabled = false;
            verifyBtn.style.opacity = "1";
            var response = xhr.responseText.trim();
            msg.classList.remove("hidden");
            msg.className = "mb-4 p-3 rounded-lg text-sm " + (response == "success" ? "text-green-500" : "text-red-500");
            msg.innerHTML = response == "success" ? "✓ Code verified!" : response;
            if (response == "success") setTimeout(() => {
                document.getElementById("forgot-step-2").classList.add("hidden");
                document.getElementById("forgot-step-3").classList.remove("hidden");
                document.getElementById("reset-message").classList.add("hidden");
            }, 1500);
        };
        xhr.onerror = () => {
            verifyBtn.disabled = false;
            verifyBtn.style.opacity = "1";
            msg.classList.remove("hidden");
            msg.className = "mb-4 p-3 rounded-lg text-sm text-red-500";
            msg.innerHTML = "Network error. Please try again.";
        };
        xhr.send(form);
    }
}

function resetPassword() {
    var pwd = document.getElementById("reset-password").value;
    var confirm = document.getElementById("reset-password-confirm").value;
    var email = document.getElementById("forgot-email").value;
    var msg = document.getElementById("reset-message");
    var resetBtn = document.getElementById("forgot-password-reset-password-btn");
    
    msg.classList.remove("hidden");
    
    if (!pwd || !confirm) {
        msg.className = "mb-4 p-3 rounded-lg text-sm text-red-500";
        msg.innerHTML = "All fields required!";
    } else if (pwd.length < 8) {
        msg.className = "mb-4 p-3 rounded-lg text-sm text-red-500";
        msg.innerHTML = "Password must be 8+ characters!";
    } else if (pwd !== confirm) {
        msg.className = "mb-4 p-3 rounded-lg text-sm text-red-500";
        msg.innerHTML = "Passwords don't match!";
    } else {
        msg.className = "mb-4 p-3 rounded-lg text-sm text-blue-500";
        msg.innerHTML = "Resetting... <span class='inline-block animate-spin'>⏳</span>";
        resetBtn.disabled = true;
        resetBtn.style.opacity = "0.6";
        
        var form = new FormData();
        form.append("email", email);
        form.append("password", pwd);
        form.append("cpassword", confirm);
        form.append("action", "reset");
        
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "process/resetPasswordProcess.php", true);
        xhr.onload = () => {
            resetBtn.disabled = false;
            resetBtn.style.opacity = "1";
            var response = xhr.responseText.trim();
            msg.classList.remove("hidden");
            msg.className = "mb-4 p-3 rounded-lg text-sm " + (response == "success" ? "text-green-500" : "text-red-500");
            msg.innerHTML = response == "success" ? "✓ Password reset successfully!" : response;
            if (response == "success") setTimeout(() => {
                closeForgotPasswordModal();
                document.getElementById("reset-password").value = "";
                document.getElementById("reset-password-confirm").value = "";
            }, 2000);
        };
        xhr.onerror = () => {
            resetBtn.disabled = false;
            resetBtn.style.opacity = "1";
            msg.classList.remove("hidden");
            msg.className = "mb-4 p-3 rounded-lg text-sm text-red-500";
            msg.innerHTML = "Network error. Please try again.";
        };
        xhr.send(form);
    }
}

function openForgotPasswordModal(){
    document.getElementById("forgot-password-modal").classList.remove("hidden");
    document.getElementById("forgot-step-1").classList.remove("hidden");
    document.getElementById("forgot-step-2").classList.add("hidden");
    document.getElementById("forgot-step-3").classList.add("hidden");
    document.getElementById("forgot-email").focus();
}

function closeForgotPasswordModal(){
    document.getElementById("forgot-password-modal").classList.add("hidden");
    
    document.getElementById("forgot-email").value = "";
    document.getElementById("verify-code").value = "";
    document.getElementById("reset-password").value = "";
    document.getElementById("reset-password-confirm").value = "";

    document.getElementById("forgot-message").classList.add("hidden");
    document.getElementById("verify-message").classList.add("hidden");
    document.getElementById("reset-message").classList.add("hidden");
}

function backToEmail(){
    document.getElementById("forgot-step-2").classList.add("hidden");
    document.getElementById("forgot-step-1").classList.remove("hidden");
    document.getElementById("verify-code").value = "";
    document.getElementById("verify-message").classList.add("hidden");
}