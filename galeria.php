<?php
$categories = ["kuchnie", "sufity", "sciany", "tarasy", "stolarka", "domki"];
$categoryTitles = [
    "kuchnie" => "Kuchnie na wymiar",
    "sufity" => "Podwieszane sufity",
    "sciany" => "Ściany działowe",
    "tarasy" => "Tarasy drewniane",
    "stolarka" => "Meble i zabudowa wnęk",
    "domki" => "Domki letniskowe"
];

$captionsFile = $_SERVER['DOCUMENT_ROOT'] . "/assets/data/captions.json";
$captions = file_exists($captionsFile) ? json_decode(file_get_contents($captionsFile), true) : [];
if ($captions === null) {
    $captions = [];
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="GA Meblobud – meble na wymiar, kuchnie na zamówienie i profesjonalne remonty w Starogardzie Gdańskim, Województwo Pomorskie. Sprawdź naszą galerię projektów!">
    <title>Galeria GA Meblobud – Meble na Wymiar i Remonty - Starogard Gdański</title>
    <link rel="canonical" href="https://ga-meblobud.pl/galeria">
    <link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/favicon.png" sizes="48x48">
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="images/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="images/android-chrome-512x512.png" sizes="512x512">
    <link rel="manifest" href="images/site.webmanifest">
    <meta property="og:image" content="https://ga-meblobud.pl/images/og-preview-new.jpg">
    <meta property="og:url" content="https://ga-meblobud.pl/galeria">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Galeria GA Meblobud – Meble na Wymiar i Remonty - Starogard Gdański">
    <meta property="og:description" content="Galeria GA Meblobud: kuchnie i meble na wymiar, remonty i usługi budowlane w Województwie Pomorskim – Starogard Gdański. Obejrzyj nasze projekty!">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fancybox.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,600,600italic,700" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
    <script async src="assets/js/gtag.js" data-cookieconsent="statistics"></script>
    <script data-cookieconsent="statistics">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-YJJRJRW6RC');
    </script>
    <script type="application/ld+json">
        [
            {
                "@context": "https://schema.org",
                "@type": "LocalBusiness",
                "name": "GA Meblobud",
                "telephone": "(+48) 505 705 338",
                "email": "info@ga-meblobud.pl",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "Pinczyn 83-251",
                    "addressLocality": "Starogard Gdański",
                    "addressRegion": "Województwo Pomorskie",
                    "addressCountry": "PL"
                },
                "url": "https://ga-meblobud.pl",
                "logo": "https://ga-meblobud.pl/images/logo.png",
                "description": "Firma oferująca meble na wymiar, kuchnie i remonty w województwie pomorskim."
            },
            {
                "@context": "https://schema.org",
                "@type": "WebPage",
                "name": "Galeria - GA Meblobud",
                "url": "https://ga-meblobud.pl/galeria",
                "description": "Przeglądaj nasze realizacje mebli na wymiar, kuchni i remontów w województwie pomorskim.",
                "isPartOf": {
                    "@type": "WebSite",
                    "url": "https://ga-meblobud.pl",
                    "name": "GA Meblobud"
                },
                "publisher": {
                    "@type": "LocalBusiness",
                    "name": "GA Meblobud",
                    "url": "https://ga-meblobud.pl"
                }
            }
        ]
    </script>
</head>
<body>
    <header data-bs-theme="dark">
        <div class="collapse text-bg-dark" id="navbarHeader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-md-7 py-4">
                        <h4>O firmie GA Meblobud</h4>
                        <p class="text-body-secondary">Specjalizujemy się w meblach na wymiar, kuchniach na zamówienie oraz kompleksowych remontach w Starogardzie Gdańskim i Województwie Pomorskim. Poznaj nasze portfolio i przekonaj się o doświadczeniu jakie wnosimy do każdego projektu.</p>
                        <a href="/" class="btn btn-light">Strona Główna</a>
                    </div>
                    <div class="col-sm-4 offset-md-1 py-4">
                        <h4>Dane kontaktowe</h4>
                        <ul class="list-unstyled">
                            <li>✆ (+48) 505 705 338</li>
                            <li>✉ info@ga-meblobud.pl</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24">
                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                        <circle cx="12" cy="13" r="4"/>
                    </svg>
                    <strong>Galeria GA Meblobud</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>
    <main>
        <section class="py-3 text-center container">
            <div class="row py-lg-5">
                <div class="col-lg-10 col-md-12 mx-auto">
                    <h1 class="fw-light">Meble na wymiar i remonty w Starogardzie Gdańskim</h1>
                    <p class="lead text-body-secondary">Odkryj nasze realizacje: nowoczesne meble na wymiar, kuchnie na zamówienie, tarasy drewniane i domki letniskowe w Województwie Pomorskim.</p>
                </div>
            </div>
        </section>
        <div class="album py-5 bg-body-tertiary">
            <div class="container">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php foreach ($categories as $category): ?>
                        <?php
                        // Direct filesystem access (since cURL failed)
                        $dir = $_SERVER['DOCUMENT_ROOT'] . "/gallery images/" . $category . "/";
                        $files = is_dir($dir) ? scandir($dir) : [];
                        $images = $files ? array_filter(array_diff($files, [".", ".."]), function($file) use ($dir) {
                            $mime = mime_content_type($dir . $file);
                            return in_array($mime, ["image/jpeg", "image/png", "image/gif", "image/jfif"]);
                        }) : [];
                        $images = array_values($images);

                        if (!empty($images)):
                            $firstImage = $images[0];
                        ?>
                            <div class="col">
                                <div class="card shadow-sm">
                                    <a href="gallery images/<?php echo $category; ?>/<?php echo $firstImage; ?>" data-fancybox="<?php echo $category; ?>" data-caption="<?php echo htmlspecialchars($captions[$category][$firstImage] ?? $categoryTitles[$category]); ?>">
                                        <img src="images/card-img-top/<?php echo $category; ?>.jpg" class="card-img-top" alt="Projekt <?php echo $categoryTitles[$category]; ?>">
                                    </a>
                                    <?php foreach (array_slice($images, 1) as $image): ?>
                                        <a href="gallery images/<?php echo $category; ?>/<?php echo $image; ?>" data-fancybox="<?php echo $category; ?>" data-caption="<?php echo htmlspecialchars($captions[$category][$image] ?? $categoryTitles[$category]); ?>" class="d-none"></a>
                                    <?php endforeach; ?>
                                    <div class="card-body">
                                        <p class="card-text"><?php echo $categoryTitles[$category]; ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a data-fancybox-trigger="<?php echo $category; ?>" class="btn btn-sm btn-outline-secondary">Zobacz</a>
                                                <button type="button" class="btn btn-sm btn-outline-secondary share-btn" 
                                                    data-share-url="https://ga-meblobud.pl/galeria#<?php echo $category; ?>-1" 
                                                    data-share-title="<?php echo $categoryTitles[$category]; ?> – GA Meblobud" 
                                                    data-share-text="Sprawdź nasze <?php echo strtolower($categoryTitles[$category]); ?> w Starogardzie Gdańskim!">Udostępnij</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <a href="/" class="btn btn-outline-light btn-sm">Strona główna</a>
                        <a href="/#contact" class="btn btn-outline-light btn-sm">Kontakt</a>
                        <a href="#top" class="btn btn-outline-light btn-sm">Powrót na górę</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <p class="small mb-0">GA Meblobud © 2025 | Meble na wymiar i remonty – Starogard Gdański | <a href="#" id="cookie-policy">Ustawienia Cookies</a></p>
                </div>
            </div>
        </div>
    </footer>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]");
    </script>
    <script src="assets/js/share-btn.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/cookieconsent.min.js"></script>
    <script src="assets/js/cookie-consent.js"></script>
</body>
</html>