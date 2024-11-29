function openForgotPassword() {
    document.getElementById("forgot-password-popup").style.display = "block";
}

function closeForgotPassword() {
    document.getElementById("forgot-password-popup").style.display = "none";
}

function handleForgotPassword(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    const messageBox = document.getElementById("forgot-password-message");

    fetch("user/forgot_password.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "email=" + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(data => {
        messageBox.textContent = data;
    })
    .catch(error => {
        messageBox.textContent = "Error: Please try again.";
    });
}