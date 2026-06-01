document.addEventListener('DOMContentLoaded', function() {
    // --- Logic for the Inquiry Form ---
    const inquiryForm = document.getElementById('inquiryForm');
    if (inquiryForm) {
        inquiryForm.addEventListener('submit', function(event) {
            // First, always check if the user is logged in
            if (inquiryForm.getAttribute('data-logged-in') === 'false') {
                event.preventDefault();
                event.stopPropagation();
                document.getElementById('feedbackModalBody').innerText = 'You must be logged in to send an inquiry.';
                $('#feedbackModal').modal('show');
                return; // Stop everything if not logged in
            }

            // If logged in, then perform standard validation
            if (inquiryForm.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            inquiryForm.classList.add('was-validated');
        }, false);
    }

    // --- Generic Form Validation for all OTHER forms ---
    const otherForms = document.querySelectorAll('.needs-validation:not(#inquiryForm)');
    Array.prototype.filter.call(otherForms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // --- Modal Popup for Feedback ---
    const feedbackModal = document.getElementById('feedbackModal');
    if (feedbackModal) {
        const feedbackMessage = feedbackModal.getAttribute('data-message');
        if (feedbackMessage) {
            document.getElementById('feedbackModalBody').innerText = feedbackMessage;
            $(feedbackModal).modal('show');
        }
    }

    // --- Active Nav Link ---
    const currentLocation = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentLocation)) {
            link.parentElement.classList.add('active');
        }
    });

    // --- Active Dashboard Sidebar Link ---
    const dashboardLinks = document.querySelectorAll('.dashboard-sidebar a');
    dashboardLinks.forEach(link => {
        if (link.getAttribute('href').includes(currentLocation)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
});