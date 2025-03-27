<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: /assets/php/login.php");
    exit();
}

$categories = ["kuchnie", "sufity", "sciany", "tarasy", "stolarka", "domki"];
$categoryTitles = [
    "kuchnie" => "Kuchnie",
    "sufity" => "Podwieszane sufity",
    "sciany" => "Ściany działowe",
    "tarasy" => "Tarasy ogrodowe",
    "stolarka" => "Meble i zabudowa wnęk",
    "domki" => "Domki letniskowe"
];

// Load captions
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
    <title>Admin - GA Meblobud</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white" href="#">GA Meblobud Admin</a>
        <ul class="navbar-nav flex-row px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link text-white" href="assets/php/logout.php">Wyloguj</a>
            </li>
        </ul>
    </header>
    <div class="container-fluid">
        <div class="row">
            <div class="sidebar border-end col-md-3 col-lg-2 p-0">
                <div class="offcanvas-md offcanvas-end border-end" tabindex="-1" id="sidebarMenu">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">GA Meblobud Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
                    </div>
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column">
                            <?php foreach ($categories as $category): ?>
                                <li class="nav-item">
                                    <a class="nav-link link-body-emphasis d-flex align-items-center gap-2" href="#<?php echo $category; ?>">
                                        <svg class="bi" width="16" height="16"><use xlink:href="#image"/></svg>
                                        <?php echo $categoryTitles[$category]; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Zarządzaj Galerią</h1>
                </div>
                <form id="upload-form" class="upload-form mb-4" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="category" class="form-label">Wybierz galerię</label>
                        <select class="form-select" id="category" name="category" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category; ?>"><?php echo $categoryTitles[$category]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="images" class="form-label">Wybierz zdjęcia (max 5MB)</label>
                        <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple required>
                    </div>
                    <button type="submit" class="btn btn-primary">Prześlij</button>
                </form>
                <?php foreach ($categories as $category): ?>
                    <div id="<?php echo $category; ?>" class="category-section mb-5">
                        <h3><?php echo $categoryTitles[$category]; ?></h3>
                        <div id="image-list-<?php echo $category; ?>" class="image-list"></div>
                    </div>
                <?php endforeach; ?>
            </main>
        </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
        <symbol id="image" viewBox="0 0 16 16">
            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
        </symbol>
    </svg>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const categories = <?php echo json_encode($categories); ?>;
            const captions = <?php echo json_encode($captions); ?>;
            
            categories.forEach(category => {
                const $imageList = $(`#image-list-${category}`);
                
                // Fetch images
                $.get(`assets/php/get-images.php?gallery=${category}`, function(images) {
                    images.forEach(image => {
                        const caption = captions[category]?.[image] || '';
                        const $imageItem = $(`
                            <div class="d-flex align-items-center mb-3">
                                <img src="gallery images/${category}/${image}" alt="${image}" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-0 caption-text">${caption || 'Brak podpisu'}</p>
                                    <input type="text" class="form-control caption-input d-none" value="${caption}">
                                    <button type="button" class="btn btn-sm btn-outline-secondary edit-caption me-2 mt-2">Edytuj podpis</button>
                                    <div class="btn-group edit-buttons d-none me-2 mt-2">
                                        <button type="button" class="btn btn-sm btn-primary save-caption">Zapisz</button>
                                        <button type="button" class="btn btn-sm btn-secondary cancel-caption">Anuluj</button>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger delete-image mt-2">Usuń zdjęcie</button>
                                    <input type="hidden" name="image" value="${image}">
                                </div>
                            </div>
                        `);
                        $imageList.append($imageItem);
                    });

                    // Edit caption
                    $imageList.on('click', '.edit-caption', function() {
                        const $item = $(this).closest('.d-flex');
                        $item.find('.caption-text').addClass('d-none');
                        $item.find('.caption-input').removeClass('d-none').focus();
                        $item.find('.edit-caption').addClass('d-none');
                        $item.find('.edit-buttons').removeClass('d-none');
                    });

                    // Cancel edit
                    $imageList.on('click', '.cancel-caption', function() {
                        const $item = $(this).closest('.d-flex');
                        const originalCaption = captions[category]?.[$item.find('input[name="image"]').val()] || '';
                        $item.find('.caption-input').val(originalCaption).addClass('d-none');
                        $item.find('.caption-text').text(originalCaption || 'Brak podpisu').removeClass('d-none');
                        $item.find('.edit-caption').removeClass('d-none');
                        $item.find('.edit-buttons').addClass('d-none');
                    });

                    // Save caption
                    $imageList.on('click', '.save-caption', function() {
                        const $item = $(this).closest('.d-flex');
                        const image = $item.find('input[name="image"]').val();
                        const newCaption = $item.find('.caption-input').val();
                        
                        $.post('assets/php/update-caption.php', {
                            category: category,
                            captions: { [image]: newCaption }
                        }, function(response) {
                            if (response.success) {
                                $item.find('.caption-text').text(newCaption || 'Brak podpisu').removeClass('d-none');
                                $item.find('.caption-input').addClass('d-none');
                                $item.find('.edit-caption').removeClass('d-none');
                                $item.find('.edit-buttons').addClass('d-none');
                                captions[category] = captions[category] || {};
                                captions[category][image] = newCaption;
                            } else {
                                alert('Błąd podczas zapisywania podpisu: ' + (response.error || 'Nieznany błąd'));
                            }
                        }, 'json').fail(function() {
                            alert('Błąd podczas komunikacji z serwerem.');
                        });
                    });

                    // Delete image
                    $imageList.on('click', '.delete-image', function() {
                        const $item = $(this).closest('.d-flex');
                        const image = $item.find('input[name="image"]').val();

                        // Show the confirmation modal
                        $("#deleteFileName").text(image);
                        $("#deleteConfirmModal").modal("show");

                        // Handle the confirm button click
                        $("#confirmDeleteBtn").off('click').on('click', function() {
                            $("#deleteConfirmModal").modal("hide"); // Hide the modal

                            // Proceed with the delete AJAX call
                            $.post('assets/php/delete-backend.php', { category: category, image: image }, function(response) {
                                $("#messageText").text(response.success ? "Zdjęcie zostało usunięte." : ('Błąd podczas usuwania zdjęcia: ' + (response.message || 'Nieznany błąd')));
                                $("#messageModal").modal("show");
                                if (response.success) {
                                    $item.remove();
                                }
                            }, 'json').fail(function(jqXHR, status, error) {
                                $("#messageText").text('Błąd podczas komunikacji z serwerem: ' + status);
                                $("#messageModal").modal("show");
                            });
                        });
                    });
                });
            });

            $('#upload-form').on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                $.ajax({
                    url: 'assets/php/upload-backend.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $("#messageText").text(response.success ? "Zdjęcie zostało przesłane pomyślnie." : ('Błąd podczas przesyłania: ' + (response.message || 'Nieznany błąd')));
                        $("#messageModal").modal("show");
                    },
                    error: function(jqXHR, status, error) {
                        $("#messageText").text('Błąd podczas komunikacji z serwerem: ' + status);
                        $("#messageModal").modal("show");
                    }
                });
            });
        });
    </script>
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Wiadomość</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="messageText"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Czy na pewno chcesz usunąć to zdjęcie: <span id="deleteFileName"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Usuń</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>