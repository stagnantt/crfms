document.addEventListener('DOMContentLoaded', function() {
    // Password requirements stored in a single object
    const passwordRequirements = {
        regex: /^(?=.*\d).{8,}$/, // At least 8 characters long and contains at least 1 number
        message: "Password must be at least 8 characters long and include at least 1 number."
    };

    const forms = ['registrationForm', 'adminRegistrationForm'];

    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(event) {
                const passwordField = form.querySelector('input[type="password"]');
                const password = passwordField.value;

                if (!passwordRequirements.regex.test(password)) {
                    alert(passwordRequirements.message);
                    event.preventDefault(); // Prevent form submission if password is invalid
                }
            });
        }
    });
});