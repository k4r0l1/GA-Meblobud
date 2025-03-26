// assets/js/cookie-consent.js
$(document).ready(function() {
    console.log("cookie-consent.js loaded");

    // Check if CookieConsent library is loaded
    if (typeof window.cookieconsent === 'undefined') {
        console.error('CookieConsent library not loaded! Check assets/js/cookieconsent.min.js');
        alert('Błąd: Nie znaleziono biblioteki CookieConsent. Sprawdź plik assets/js/cookieconsent.min.js.');
        return;
    }
    console.log("cookieconsent loaded:", window.cookieconsent);

    // Initialize CookieConsent
    try {
        window.cookieconsent.initialise({
            "palette": {
                "popup": { "background": "#252525" },
                "button": { "background": "#43B3E0" }
            },
            "position": "bottom-right",
            "type": "opt-out",
            "revokable": false,
            "content": {
                "message": "Używamy cookies (Google Analytics) do zbierania anonimowych danych analitycznych, aby ulepszać naszą stronę i Twoje doświadczenie. Nie zbieramy żadnych danych osobowych. Możesz je wyłączyć, klikając 'Odrzuć'.",
                "allow": "Akceptuję",
                "deny": "Odrzuć",
                "link": "Dowiedz się więcej",
                "href": "/polityka-prywatnosci"
            },
            "onInitialise": function(status) {
                console.log('CookieConsent onInitialise called with status:', status);
                window.ccInstance = this;
                if (!status || status === 'pending') {
                    console.log('No prior consent detected, opening popup');
                    this.open();
                }
                // Analytics enabled by default
                window['ga-disable-G-YJJRJRW6RC'] = false;
                gtag('consent', 'update', { 'analytics_storage': 'granted' });
            },
            "onStatusChange": function(status) {
                console.log('CookieConsent status changed:', status);
                if (this.hasConsented()) {
                    console.log('User accepted, analytics remain enabled');
                    window['ga-disable-G-YJJRJRW6RC'] = false;
                    gtag('consent', 'update', { 'analytics_storage': 'granted' });
                } else {
                    console.log('User denied, disabling analytics');
                    window['ga-disable-G-YJJRJRW6RC'] = true;
                    gtag('consent', 'update', { 'analytics_storage': 'denied' });
                }
                window.ccInstance = this;
            },
            "onPopupOpen": function() {
                console.log('CookieConsent popup opened');
            },
            "onPopupClose": function() {
                console.log('CookieConsent popup closed');
            }
        });
        console.log('CookieConsent initialized');
    } catch (e) {
        console.error('CookieConsent initialization error:', e);
        alert('Błąd: Inicjalizacja CookieConsent nie powiodła się. Sprawdź konsolę.');
    }

    // Hide default revoke button
    $('.cc-revoke').hide();

    // Custom revoke link in footer
    $('#cookie-policy').on('click', function(e) {
        e.preventDefault();
        if (window.ccInstance && typeof window.ccInstance.open === 'function') {
            console.log('Opening CookieConsent popup');
            window.ccInstance.open();
        } else {
            console.error('CookieConsent instance not initialized or invalid!');
            alert('Błąd: Funkcja cookies nie jest dostępna. Sprawdź konfigurację.');
        }
    });
});