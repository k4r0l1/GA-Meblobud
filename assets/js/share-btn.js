document.querySelectorAll('.share-btn').forEach(button => {
    button.addEventListener('click', () => {
        const url = button.getAttribute('data-share-url');
        const title = button.getAttribute('data-share-title');
        const text = button.getAttribute('data-share-text');
        const shareMessage = `${text} ${url}`;

        if (navigator.share) {
            console.log('Attempting Web Share API...');
            navigator.share({
                title: title,
                text: text,
                url: url
            })
            .then(() => console.log('Web Share API succeeded'))
            .catch(error => {
                console.error('Web Share API failed:', error);
                const shareWindow = window.open('', '_blank', 'width=400,height=500');
                if (shareWindow) {
                    openFallbackPopup(shareWindow, url, shareMessage);
                } else {
                    console.log('Popup blocked by browser');
                    alert('Please allow popups for this site to use the share feature.');
                }
            });
        } else {
            console.log('Web Share API not supported, using fallback');
            const shareWindow = window.open('', '_blank', 'width=400,height=500');
            if (shareWindow) {
                openFallbackPopup(shareWindow, url, shareMessage);
            } else {
                console.log('Popup blocked by browser');
                alert('Please allow popups for this site to use the share feature.');
            }
        }
    });
});

function openFallbackPopup(shareWindow, url, shareMessage) {
    console.log('Opening fallback popup');
    shareWindow.document.write(`
        <html>
        <head>
            <title>Udostępnij</title>
            <meta charset="UTF-8">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                    background: url("/assets/css/images/bg.png") repeat;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    padding: 25px;
                }
                .button-container {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    width: 100%;
                    max-width: 280px;
                    position: relative;
                    z-index: 1;
                }
                button, a {
                    display: block;
                    width: 100%;
                    padding: 10px;
                    margin: 0;
                    font-size: 14px;
                    font-weight: 500;
                    text-decoration: none;
                    color: #ffffff;
                    background: #000;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    text-align: center;
                }
                .icon {
                    margin-right: 8px;
                    font-size: 18px;
                    vertical-align: middle;
                }
                #copyBtn { background: #4a5568; }
                #whatsappBtn { background: #22c55e; }
                #facebookBtn { background: #3b82f6; }
                #closeBtn { background: #718096; margin-top: 40px; }
            </style>
        </head>
        <body>
            <div class="overlay"></div>
            <div class="button-container">
                <button id="copyBtn"><span class="icon">📋</span>Kopiuj Link</button>
                <a id="whatsappBtn" href="https://wa.me/?text=${encodeURIComponent(shareMessage)}" target="_blank">
                    <span class="icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.52 3.48A11.96 11.96 0 0012 0C5.37 0 0 5.37 0 12c0 2.13.56 4.14 1.54 5.9L0 24l6.23-1.63A11.96 11.96 0 0012 24c6.63 0 12-5.37 12-12 0-3.14-1.2-6-3.48-8.52zM12 21.6a9.6 9.6 0 01-4.88-1.33l-.35-.2-3.7.97.98-3.6-.2-.34A9.6 9.6 0 012.4 12c0-5.3 4.3-9.6 9.6-9.6s9.6 4.3 9.6 9.6-4.3 9.6-9.6 9.6zm5.27-7.06c-.3-.15-1.8-.9-2.08-.98-.28-.1-.48-.15-.68.15-.2.3-.78.98-.95 1.18-.17.2-.35.23-.65.08-.3-.15-1.27-.47-2.42-1.5-1.15-1.03-1.92-2.3-2.15-2.6-.23-.3-.02-.47.13-.62.13-.13.3-.35.45-.53.15-.17.2-.3.3-.5.1-.2.05-.38-.02-.53-.08-.15-.68-1.65-.93-2.25-.25-.6-.5-.5-.68-.5-.17 0-.38 0-.58.02-.2.02-.53.08-.8.38-.28.3-1.05 1.03-1.05 2.5s1.08 2.9 1.23 3.1c.15.2 2.12 3.23 5.15 4.53 3.03 1.3 3.03.87 3.58.82.55-.05 1.8-.73 2.05-1.43.25-.7.25-1.3.18-1.43-.08-.13-.3-.2-.58-.35z" fill="#fff"/>
                        </svg>
                    </span>WhatsApp
                </a>
                <a id="facebookBtn" href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}" target="_blank">
                    <span class="icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.99 3.66 9.13 8.44 9.88v-6.98h-2.54v-2.9h2.54V9.84c0-2.51 1.49-3.89 3.78-3.89 1.09 0 2.23.19 2.23.19v2.46h-1.26c-1.24 0-1.62.77-1.62 1.56v1.88h2.76l-.44 2.9h-2.32v6.98C18.34 21.13 22 16.99 22 12z" fill="#fff"/>
                        </svg>
                    </span>Facebook
                </a>
                <button id="closeBtn"><span class="icon">✖</span>Zamknij</button>
            </div>
            <script>
                document.getElementById('copyBtn').addEventListener('click', () => {
                    navigator.clipboard.writeText('${url}');
                    alert('Link skopiowano do schowka!');
                });
                document.getElementById('closeBtn').addEventListener('click', () => {
                    window.close();
                });
            </script>
        </body>
        </html>
    `);
    shareWindow.document.close();
}