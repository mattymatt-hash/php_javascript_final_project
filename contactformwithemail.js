<script>
    document.addEventListener("DOMContentLoaded", () => {
        const dateInput = document.getElementById("date");
        const timeInput = document.getElementById("time");
        const email = document.getElementById('email');

        // Set current date
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        dateInput.value = `${year}-${month}-${day}`;

        // Set current time in HH:MM format
        const hours = String(currentDate.getHours()).padStart(2, '0');
        const minutes = String(currentDate.getMinutes()).padStart(2, '0');
        timeInput.value = `${hours}:${minutes}`;

        // Function to validate email
        function validateEmail() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email.value.trim());
        }

        // Show error message
        function showError(input, message) {
            const error = document.createElement('div');
            error.className = 'error-message';
            error.style.color = 'red';
            error.innerText = message;
            input.parentElement.appendChild(error);
        }

        // Manage contact method fields
        const phoneRadio = document.querySelector('input[name="contact_method"][value="phone"]');
        const emailRadio = document.querySelector('input[name="contact_method"][value="email"]');
        const otherRadio = document.querySelector('input[name="contact_method"][value="other"]');
        const phoneNumberDiv = document.getElementById('phoneNumberDiv');
        const otherMethodDiv = document.getElementById('otherMethodDiv');

        phoneRadio.addEventListener('change', function() {
            phoneNumberDiv.style.display = this.checked ? 'block' : 'none';
            otherMethodDiv.style.display = 'none';
        });

        emailRadio.addEventListener('change', function() {
            phoneNumberDiv.style.display = 'none';
            otherMethodDiv.style.display = 'none';
        });

        otherRadio.addEventListener('change', function() {
            phoneNumberDiv.style.display = 'none';
            otherMethodDiv.style.display = this.checked ? 'block' : 'none';
        });

        // Manage "How Did You Hear About Us?" section
        const hearAboutSelect = document.getElementById('hear_about_us');
        const otherHearAboutDiv = document.getElementById('otherHearAboutDiv');

        hearAboutSelect.addEventListener('change', function() {
            otherHearAboutDiv.style.display = this.value === 'Other' ? 'block' : 'none';
        });

        // Form submission validation
        const form = document.getElementById('myForm');
        form.addEventListener('submit', function(event) {
            // Clear previous error messages
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(msg => msg.remove());

            let isValid = true; // Assume the form is valid initially

            // Validate Email
            if (!validateEmail()) {
                isValid = false;
                showError(email, 'Please enter a valid email address.');
            }

            // If form is invalid, prevent submission
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>
