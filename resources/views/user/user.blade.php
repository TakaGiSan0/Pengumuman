<!DOCTYPE html>
<html>

<head>
    <title>Uploaded Files</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            overflow: hidden;
        }

        .swiper-container {
            width: 100vw;
            height: 100vh;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide img,
        .swiper-slide iframe,
        .swiper-slide video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .swiper-pagination {
            bottom: 10px !important;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #000;
        }

        .fullscreen-button {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            cursor: pointer;
            z-index: 10;
            display: none;
        }

        .fullscreen-button.visible {
            display: block;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <button class="fullscreen-button visible" onclick="toggleFullScreen()">Fullscreen</button>

    <div class="swiper-container">
        <div class="swiper-wrapper">
            @foreach ($news as $file)
                @if (in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->file }}">
                    </div>
                @elseif (pathinfo($file->file_path, PATHINFO_EXTENSION) == 'mp4')
                    <div class="swiper-slide">
                        <video src="{{ asset('storage/' . $file->file_path) }}" controls></video>
                    </div>
                @elseif (pathinfo($file->file_path, PATHINFO_EXTENSION) == 'pdf')
                    <div class="swiper-slide">
                        <iframe src="{{ asset('storage/' . $file->file_path) }}"></iframe>
                    </div>
                @else
                    <div class="swiper-slide">
                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">{{ $file->file }}</a>
                    </div>
                @endif
            @endforeach
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 30000, // Default delay for photo slides
                disableOnInteraction: false,
            },
            on: {
                slideChangeTransitionEnd: function() {
                    const activeSlide = swiper.slides[swiper.activeIndex];
                    const video = activeSlide.querySelector('video');

                    // Reset all videos to start from the beginning
                    const allVideos = document.querySelectorAll('video');
                    allVideos.forEach(v => {
                        v.pause();
                        v.currentTime = 0;
                    });

                    if (video) {
                        swiper.autoplay.stop();
                        video.play();
                        video.onended = function() {
                            setTimeout(() => {
                                swiper.slideNext();
                                swiper.autoplay.start();
                            }, 3000); // Delay 3 seconds after video ends
                        };
                    } else {
                        swiper.params.autoplay.delay = 30000; // 30 seconds delay for photo slides
                        swiper.autoplay.start();
                    }
                }
            }
        });

        function toggleFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        function enterFullScreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
            }
        }

        document.addEventListener('fullscreenchange', () => {
            const fullscreenButton = document.querySelector('.fullscreen-button');
            const navigationButtons = document.querySelectorAll('.swiper-button-next, .swiper-button-prev');

            if (document.fullscreenElement) {
                fullscreenButton.classList.remove('visible');
                navigationButtons.forEach(button => button.classList.add('hidden'));
            } else {
                fullscreenButton.classList.add('visible');
                navigationButtons.forEach(button => button.classList.remove('hidden'));
            }
        });

        window.addEventListener('load', () => {
            if (window.location.pathname === '/user') {
                enterFullScreen();
            }
        });

        let lastUpdated = new Date().toISOString();

        function checkForUpdates() {
            fetch('/api/last-updated')
                .then(response => response.json())
                .then(data => {
                    if (new Date(data.last_updated) > new Date(lastUpdated)) {
                        window.location.reload(); // Reload if file was updated
                        lastUpdated = data.last_updated;
                    }
                })
                .catch(error => console.error('Error fetching last update:', error));
        }

        // Check every 3 seconds (3000 milliseconds)
        setInterval(checkForUpdates, 3000);
    </script>
</body>

</html>
