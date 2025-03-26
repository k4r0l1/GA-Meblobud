$(document).ready(function() {
    const galleries = ["domki", "kuchnie", "sciany", "stolarka", "sufity", "tarasy"];
    function loadImages() {
        let deleteHtml = "";
        const requests = galleries.map(gallery => {
            return $.getJSON(`/assets/php/get-images.php?gallery=${gallery}`)
                .then(images => {
                    console.log(`Images for ${gallery}:`, images);
                    if (images.length > 0) {
                        deleteHtml += `<h4 class="mt-4">${gallery}</h4>`;
                        deleteHtml += `<ul class="list-group">`;
                        images.forEach(image => {
                            const imagePath = `/gallery images/${gallery}/${image}`;
                            const caption = window.captions && window.captions[gallery] && window.captions[gallery][image] ? window.captions[gallery][image] : '';
                            deleteHtml += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <img src="${imagePath}" alt="${image}" class="img-thumbnail me-2" style="max-width: 100px; max-height: 100px;">
                                        <span>${image}</span>
                                    </div>
                                    <div class="ms-2 flex-grow-1">
                                        <input type="text" class="form-control caption-input" data-gallery="${gallery}" data-file="${image}" value="${caption}" placeholder="Wpisz podpis">
                                        <button class="btn btn-primary btn-sm save-caption mt-1" data-gallery="${gallery}" data-file="${image}">Zapisz</button>
                                    </div>
                                    <form class="delete-form d-inline ms-2" data-gallery="${gallery}" data-file="${image}">
                                        <input type="hidden" name="gallery" value="${gallery}">
                                        <input type="hidden" name="file" value="${image}">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Usuń</button>
                                    </form>
                                </li>`;
                        });
                        deleteHtml += `</ul>`;
                    }
                })
                .fail((jqXHR, textStatus, errorThrown) => {
                    console.error(`Failed to load ${gallery}: ${textStatus} - ${errorThrown}`);
                    deleteHtml += `<p class="text-danger">Błąd ładowania ${gallery}: ${textStatus}</p>`;
                });
        });

        Promise.all(requests).then(() => {
            $("#delete-section").html(deleteHtml || "<p>Brak zdjęć do wyświetlenia.</p>");
        });
    }

    $.getJSON('/assets/data/captions.json', function(data) {
        window.captions = data;
        loadImages();
    }).fail(function() {
        window.captions = {};
        loadImages();
    });

    $("#upload-form").on("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: "/assets/php/upload-backend.php",
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#messageText").text(response.message);
                $("#messageModal").modal("show");
                if (response.success) {
                    loadImages();
                    $("#upload-form")[0].reset();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#messageText").text("Błąd połączenia: " + textStatus);
                $("#messageModal").modal("show");
            }
        });
    });

    $(document).on("submit", ".delete-form", function(e) {
        e.preventDefault();
        const $form = $(this);
        const gallery = $form.data("gallery");
        const file = $form.data("file");

        $("#deleteFileName").text(file);
        $("#deleteConfirmModal").modal("show");

        $("#confirmDeleteBtn").off("click").on("click", function() {
            $("#deleteConfirmModal").modal("hide");
            $.ajax({
                url: "/assets/php/delete-backend.php",
                method: "POST",
                data: {
                    gallery: gallery,
                    file: file,
                    delete: true
                },
                dataType: "json",
                success: function(response) {
                    $("#messageText").text(response.message);
                    $("#messageModal").modal("show");
                    if (response.success) {
                        loadImages();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#messageText").text("Błąd połączenia: " + textStatus);
                    $("#messageModal").modal("show");
                }
            });
        });
    });

    $(document).on("click", ".save-caption", function(e) {
        e.preventDefault();
        const $btn = $(this);
        const gallery = $btn.data("gallery");
        const file = $btn.data("file");
        const caption = $btn.prev(".caption-input").val();

        $.ajax({
            url: "/assets/php/update-caption.php",
            method: "POST",
            data: {
                gallery: gallery,
                file: file,
                caption: caption
            },
            dataType: "json",
            success: function(response) {
                $("#messageText").text(response.message);
                $("#messageModal").modal("show");
                if (response.success) {
                    window.captions[gallery] = window.captions[gallery] || {};
                    if (caption) {
                        window.captions[gallery][file] = caption;
                    } else {
                        delete window.captions[gallery][file];
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#messageText").text("Błąd połączenia: " + textStatus);
                $("#messageModal").modal("show");
            }
        });
    });
});