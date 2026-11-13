<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ConnectSpace')</title>

    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 & Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Custom Modern Stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')
</head>
<body>
    @auth
        @include('components.navbar')
    @endauth

    <main class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div> 
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <div>{{ $errors->first() }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Comment image preview
        function previewCommentImage(input, postId) {
            const previewContainer = document.getElementById('comment-preview-' + postId);
            const previewImg = document.getElementById('comment-preview-img-' + postId);
            const textArea = document.getElementById('comment-text-area-' + postId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    if (textArea) {
                        textArea.classList.add('d-none');
                        textArea.querySelector('textarea').value = '';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeCommentImage(postId) {
            const fileInput = document.getElementById('comment-image-' + postId);
            const previewContainer = document.getElementById('comment-preview-' + postId);
            const previewImg = document.getElementById('comment-preview-img-' + postId);
            const captionInput = document.getElementById('comment-caption-' + postId);
            const textArea = document.getElementById('comment-text-area-' + postId);

            fileInput.value = '';
            previewImg.src = '';
            if (captionInput) captionInput.value = '';
            previewContainer.classList.add('d-none');
            if (textArea) {
                textArea.classList.remove('d-none');
            }
        }

        // Post image preview
        function previewPostImage(input) {
            const previewContainer = document.getElementById('post-image-preview');
            const previewImg = document.getElementById('post-image-preview-img');
            const textAreaContainer = document.getElementById('post-text-area');
            const textarea = document.getElementById('post-content-textarea');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    if (textAreaContainer) {
                        textAreaContainer.classList.add('d-none');
                    }
                    if (textarea) {
                        textarea.value = '';
                    }
                    if (typeof updatePostCharCount === 'function') {
                        updatePostCharCount();
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removePostImage() {
            const fileInput = document.getElementById('post-image');
            const previewContainer = document.getElementById('post-image-preview');
            const previewImg = document.getElementById('post-image-preview-img');
            const captionInput = document.getElementById('post-caption-input');
            const fileName = document.getElementById('post-image-name');
            const textAreaContainer = document.getElementById('post-text-area');

            fileInput.value = '';
            previewImg.src = '';
            if (captionInput) captionInput.value = '';
            previewContainer.classList.add('d-none');
            if (fileName) fileName.textContent = '';
            if (textAreaContainer) {
                textAreaContainer.classList.remove('d-none');
            }
            if (typeof updatePostCharCount === 'function') {
                updatePostCharCount();
            }
        }

        // ==========================================
        // Fitur Lokasi Otomatis via Geolocation API
        // ==========================================
        const GEO_OPTIONS = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 60000 // Cache posisi 1 menit
        };

        // Cache helper ke sessionStorage agar instan antar halaman
        function getStoredLocation() {
            try {
                const raw = sessionStorage.getItem('user_location');
                if (raw) {
                    const parsed = JSON.parse(raw);
                    if (parsed && typeof parsed.latitude !== 'undefined' && typeof parsed.longitude !== 'undefined') {
                        // Validasi: pastikan data cache berada di wilayah Indonesia (menolak data IP desktop palsu luar negeri)
                        if (parsed.latitude >= -11.0 && parsed.latitude <= 6.0 && parsed.longitude >= 95.0 && parsed.longitude <= 141.0) {
                            // Ringkas nama jika cache sebelumnya menyimpan alamat terlalu detail
                            if (parsed.location_name && (parsed.location_name.includes('Kota Jakarta') || parsed.location_name.includes('Gambir') || parsed.location_name.split(',').length > 2)) {
                                parsed.location_name = 'Jakarta, Indonesia';
                                sessionStorage.setItem('user_location', JSON.stringify(parsed));
                            }
                            return parsed;
                        }
                    }
                }
            } catch (e) {}
            return null;
        }

        // Reverse geocode via OpenStreetMap Nominatim: Lat, Lng -> Tingkat Provinsi (contoh: "Jakarta, Indonesia")
        async function reverseGeocode(lat, lng) {
            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
                    headers: { 'Accept-Language': 'id,en' }
                });
                const data = await res.json();
                const address = data.address || {};

                // Ambil data provinsi atau wilayah utama
                let province = address.state || address.province || address.region || address.city || '';

                // Bersihkan dan format nama provinsi secara ringkas
                if (province.toLowerCase().includes('jakarta')) {
                    province = 'Jakarta';
                } else if (province.toLowerCase().includes('yogyakarta')) {
                    province = 'Yogyakarta';
                } else if (province) {
                    // Hilangkan prefix berlebih seperti "Provinsi" atau "Daerah Khusus"
                    province = province.replace(/^provinsi\s+/i, '').replace(/^daerah\s+khusus\s+/i, '').trim();
                }

                const country = address.country || 'Indonesia';

                if (province) {
                    return `${province}, ${country}`;
                }
                return `Jakarta, ${country}`;
            } catch (e) {
                return 'Jakarta, Indonesia';
            }
        }

        // Terapkan data lokasi otomatis ke seluruh form komentar & balasan di halaman
        function applyLocationToForms(loc) {
            if (!loc) return;

            // 1. Indikator Lokasi di Navbar
            const navText = document.getElementById('navbar-session-loc-text');
            const navItem = document.getElementById('navbar-loc-item');
            if (navText) {
                navText.textContent = loc.location_name;
            }
            if (navItem) {
                navItem.classList.remove('d-none');
            }

            // 2. Form komentar utama di setiap post-card
            document.querySelectorAll('input[id^="comment-lat-"]').forEach(latInput => {
                const postId = latInput.id.replace('comment-lat-', '');
                const lngInput = document.getElementById('comment-lng-' + postId);
                const nameInput = document.getElementById('comment-location-name-' + postId);
                const badge = document.getElementById('comment-active-loc-badge-' + postId);
                const badgeName = document.getElementById('comment-active-loc-name-' + postId);

                if (latInput) latInput.value = loc.latitude;
                if (lngInput) lngInput.value = loc.longitude;
                if (nameInput) nameInput.value = loc.location_name;

                if (badge && badgeName) {
                    badgeName.textContent = loc.location_name;
                    badge.classList.remove('d-none');
                    badge.classList.add('d-inline-flex');
                }
            });

            // 3. Form balasan (reply)
            document.querySelectorAll('input[id^="reply-lat-"]').forEach(latInput => {
                const commentId = latInput.id.replace('reply-lat-', '');
                const lngInput = document.getElementById('reply-lng-' + commentId);
                const nameInput = document.getElementById('reply-loc-name-' + commentId);
                const indicator = document.getElementById('reply-loc-indicator-' + commentId);
                const textEl = document.getElementById('reply-loc-text-' + commentId);

                if (latInput) latInput.value = loc.latitude;
                if (lngInput) lngInput.value = loc.longitude;
                if (nameInput) nameInput.value = loc.location_name;

                if (indicator && textEl) {
                    textEl.textContent = loc.location_name;
                    indicator.classList.remove('d-none');
                }
            });
        }

        // Deteksi lokasi murni otomatis melalui Geolocation API
        function autoDetectGeolocation() {
            if (!navigator.geolocation) {
                console.warn('Browser tidak mendukung Geolocation API.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    let lat = Number(position.coords.latitude.toFixed(7));
                    let lng = Number(position.coords.longitude.toFixed(7));

                    // Filter validasi wilayah Indonesia (Latitude: -11.0 s/d 6.0, Longitude: 95.0 s/d 141.0)
                    // Mengatasi bug estimasi IP browser desktop yang salah memetakan gateway ke Kampala, Uganda (Lat: 0.33, Lng: 32.61)
                    const isWithinIndonesia = (lat >= -11.0 && lat <= 6.0 && lng >= 95.0 && lng <= 141.0);
                    if (!isWithinIndonesia) {
                        console.warn(`Koordinat GPS browser (${lat}, ${lng}) berada di luar Indonesia (estimasi IP desktop). Mengalihkan otomatis ke Jakarta.`);
                        lat = -6.2107635;
                        lng = 106.8132321;
                    }

                    let place = await reverseGeocode(lat, lng);
                    if (!place) {
                        place = 'Jakarta, Indonesia';
                    }

                    const locData = {
                        latitude: lat,
                        longitude: lng,
                        location_name: place
                    };

                    // Simpan di sessionStorage sebagai cache sesi
                    try {
                        sessionStorage.setItem('user_location', JSON.stringify(locData));
                    } catch (e) {}

                    // Terapkan ke form komentar dan navbar
                    applyLocationToForms(locData);
                },
                function(error) {
                    console.warn('Geolocation API notification:', error.message);
                    // Fallback jika GPS belum diizinkan atau timeout: gunakan cache atau default Jakarta
                    const cached = getStoredLocation();
                    if (cached) {
                        applyLocationToForms(cached);
                    } else {
                        const defaultLoc = {
                            latitude: -6.2107635,
                            longitude: 106.8132321,
                            location_name: 'Jakarta, Indonesia'
                        };
                        try {
                            sessionStorage.setItem('user_location', JSON.stringify(defaultLoc));
                        } catch (e) {}
                        applyLocationToForms(defaultLoc);
                    }
                },
                GEO_OPTIONS
            );
        }

        // Hapus lokasi dari 1 form komentar tertentu (opsional jika user ingin kirim tanpa lokasi)
        function clearSingleCommentLocation(postId) {
            const latInput = document.getElementById('comment-lat-' + postId);
            const lngInput = document.getElementById('comment-lng-' + postId);
            const nameInput = document.getElementById('comment-location-name-' + postId);
            const badge = document.getElementById('comment-active-loc-badge-' + postId);

            if (latInput) latInput.value = '';
            if (lngInput) lngInput.value = '';
            if (nameInput) nameInput.value = '';
            if (badge) {
                badge.classList.add('d-none');
                badge.classList.remove('d-inline-flex');
            }
        }

        function clearReplyLocation(commentId) {
            const latInput = document.getElementById('reply-lat-' + commentId);
            const lngInput = document.getElementById('reply-lng-' + commentId);
            const nameInput = document.getElementById('reply-loc-name-' + commentId);
            const indicator = document.getElementById('reply-loc-indicator-' + commentId);

            if (latInput) latInput.value = '';
            if (lngInput) lngInput.value = '';
            if (nameInput) nameInput.value = '';
            if (indicator) indicator.classList.add('d-none');
        }

        // Toggle reply comment form
        function toggleReplyForm(commentId, mention) {
            const box = document.getElementById('reply-form-box-' + commentId);
            if (!box) return;

            const input = document.getElementById('reply-input-' + commentId);
            if (box.classList.contains('d-none')) {
                box.classList.remove('d-none');

                // Otomatis pastikan lokasi terisi dari Geolocation cache
                const latInput = document.getElementById('reply-lat-' + commentId);
                const lngInput = document.getElementById('reply-lng-' + commentId);
                const nameInput = document.getElementById('reply-loc-name-' + commentId);
                const indicator = document.getElementById('reply-loc-indicator-' + commentId);
                const textEl = document.getElementById('reply-loc-text-' + commentId);

                const loc = getStoredLocation();
                if (latInput && !latInput.value && loc) {
                    latInput.value = loc.latitude;
                    if (lngInput) lngInput.value = loc.longitude;
                    if (nameInput) nameInput.value = loc.location_name;
                    if (indicator && textEl) {
                        textEl.textContent = loc.location_name;
                        indicator.classList.remove('d-none');
                    }
                }

                if (input) {
                    if (mention) {
                        input.value = mention;
                    }
                    input.focus();
                }
            } else {
                if (mention && input) {
                    input.value = mention;
                    input.focus();
                } else {
                    box.classList.add('d-none');
                }
            }
        }

        // Inisialisasi otomatis Geolocation API saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Jika ada cache lokasi sesi sebelumnya, langsung terapkan instan
            const cached = getStoredLocation();
            if (cached) {
                applyLocationToForms(cached);
            }

            // Jalankan deteksi posisi teranyar via Geolocation API secara otomatis di background
            autoDetectGeolocation();
        });

        /* Edit page image preview & remove helpers (dinonaktifkan sementara)
        function previewEditImage(input, prefix) {
            const previewContainer = document.getElementById(prefix + '-image-preview');
            const previewImg = document.getElementById(prefix + '-image-preview-img');
            const currentBox = document.getElementById('current-' + prefix + '-image-box');
            const removeCheckbox = document.getElementById('remove_image');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                    if (currentBox) {
                        currentBox.classList.add('d-none');
                    }
                    if (removeCheckbox) {
                        removeCheckbox.checked = false;
                        toggleRemoveExistingImage(removeCheckbox, 'current-' + prefix + '-image-img');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeEditImage(prefix) {
            const fileInput = document.getElementById(prefix + '-image');
            const previewContainer = document.getElementById(prefix + '-image-preview');
            const previewImg = document.getElementById(prefix + '-image-preview-img');
            const currentBox = document.getElementById('current-' + prefix + '-image-box');

            fileInput.value = '';
            previewImg.src = '';
            previewContainer.classList.add('d-none');
            if (currentBox) {
                currentBox.classList.remove('d-none');
            }
        }

        function toggleRemoveExistingImage(checkbox, imgId) {
            const currentImg = document.getElementById(imgId);
            if (currentImg) {
                currentImg.style.opacity = checkbox.checked ? '0.35' : '1';
            }
        }
        */
    </script>
    @stack('scripts')
</body>
</html>
