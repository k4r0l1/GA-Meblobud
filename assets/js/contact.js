// KB 2025

$(document).ready(function() {
    console.log("contact.js loaded and running");

    // Function to center popup dynamically
    function centerPopup() {
        const $popup = $('#popup');
        const windowHeight = $(window).height();
        const windowWidth = $(window).width();
        const popupHeight = $popup.outerHeight();
        const popupWidth = $popup.outerWidth();
        const topPos = (windowHeight - popupHeight) / 2;
        const leftPos = (windowWidth - popupWidth) / 2;

        $popup.css({
            'top': topPos + 'px',
            'left': leftPos + 'px',
            'transform': 'none'
        });
    }

    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        console.log("Form submission triggered");

        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const number = $('#number').val().trim(); 
        const message = $('#message').val().trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        console.log("Values:", { name, email, number, message });

        // Count blank fields
        const blankFields = [];
        if (!name) blankFields.push('name');
        if (!email) blankFields.push('email');
        if (!number) blankFields.push('number'); 
        if (!message) blankFields.push('message');

        console.log("Blank fields:", blankFields);

        // If more than one field is blank
        if (blankFields.length > 1) {
            console.log("Multiple fields blank");
            $('#popup-message').text('Proszę wypełnić formularz');
            $('#popup-overlay, #popup').fadeIn(300);
            centerPopup();
            $('body').css('overflow', 'hidden');
            return;
        }

        // If exactly one field is blank or email is invalid
        if (!name) {
            console.log("Validation failed: No name");
            $('#popup-message').text('Proszę podać imię');
            $('#popup-overlay, #popup').fadeIn(300);
            centerPopup();
            $('body').css('overflow', 'hidden');
            return;
        }
        if (!email) {
            console.log("Validation failed: No email");
            $('#popup-message').text('Proszę podać adres mailowy');
            $('#popup-overlay, #popup').fadeIn(300);
            centerPopup();
            $('body').css('overflow', 'hidden');
            return;
        }
        if (!emailRegex.test(email)) {
            console.log("Validation failed: Invalid email");
            $('#popup-message').text('Proszę podać prawidłowy adres mailowy');
            $('#popup-overlay, #popup').fadeIn(300);
            centerPopup();
            $('body').css('overflow', 'hidden');
            return;
        }
        if (!number) {
            console.log("Validation failed: No number");
            $('#popup-message').text('Proszę podać numer telefonu');
            $('#popup-overlay, #popup').fadeIn(300);
            centerPopup();
            $('body').css('overflow', 'hidden');
            return;
        }
        if (!message) {
            console.log("Validation failed: No message");
            $('#popup-message').text('Proszę podać treść wiadomości');
            $('#popup-overlay, #popup').fadeIn(300);
            centerPopup();
            $('body').css('overflow', 'hidden');
            return;
        }

        console.log("Validation passed, sending AJAX");

        // AJAX submission
        $.ajax({
            url: '/assets/php/send_email.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                console.log("AJAX success:", response);
                $('#popup-message').text(response.message);
                $('#popup-overlay, #popup').fadeIn(300);
                centerPopup();
                $('body').css('overflow', 'hidden');
                if (response.status === 'success') {
                    $('#contact-form')[0].reset();
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX error:", status, error);
                $('#popup-message').text('Błąd wysyłania. Spróbuj ponownie.');
                $('#popup-overlay, #popup').fadeIn(300);
                centerPopup();
                $('body').css('overflow', 'hidden');
            }
        });
    });

    $('#popup-close').on('click', function() {
        console.log("Popup closed");
        $('#popup-overlay, #popup').fadeOut(300);
        $('body').css('overflow', 'auto');
    });

    $(window).on('resize', function() {
        if ($('#popup').is(':visible')) {
            centerPopup();
        }
    });
});