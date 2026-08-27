<style>
    .page-loading-overlay {
        background: rgba(255, 255, 255, 0.88);
        backdrop-filter: blur(2px);
    }

    .four-square-loader {
        display: grid;
        grid-template-columns: repeat(2, 18px);
        grid-template-rows: repeat(2, 18px);
        gap: 4px;
        transform: rotate(45deg);
    }

    .four-square-loader span {
        width: 18px;
        height: 18px;
        border-radius: 3px;
        background-color: #0ea5e9;
        animation: four-square-pulse 1.2s ease-in-out infinite;
    }

    .four-square-loader span:nth-child(2) {
        animation-delay: 0.15s;
    }

    .four-square-loader span:nth-child(4) {
        animation-delay: 0.3s;
    }

    .four-square-loader span:nth-child(3) {
        animation-delay: 0.45s;
    }

    @keyframes four-square-pulse {
        0%, 100% {
            opacity: 0.35;
            transform: scale(0.72);
        }
        50% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .four-square-loader span {
            animation: none;
            opacity: 1;
        }
    }
</style>

<div
    class="fixed inset-0 z-50 hidden flex flex-col items-center justify-center page-loading-overlay fullloader"
    role="status"
    aria-live="polite"
    aria-label="Page loading"
>
    <div class="four-square-loader" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <p class="mt-5 text-sm font-medium" style="color: #0ea5e9;">Loading...</p>
</div>

<script>
    (function () {
        const loader = document.querySelector('.fullloader');

        if (!loader) {
            return;
        }

        const showPageLoader = function () {
            loader.classList.remove('hidden');
        };

        const hidePageLoader = function () {
            loader.classList.add('hidden');
        };

        document.addEventListener('click', function (event) {
            const link = event.target.closest('a[href]');

            if (!link || event.defaultPrevented || event.button !== 0 ||
                event.ctrlKey || event.metaKey || event.shiftKey || event.altKey ||
                link.target === '_blank' || link.hasAttribute('download')) {
                return;
            }

            const href = link.getAttribute('href');
            if (!href || href.startsWith('#') || href.startsWith('javascript:')) {
                return;
            }

            showPageLoader();
        });

        document.addEventListener('submit', function (event) {
            if (!event.defaultPrevented) {
                showPageLoader();
            }
        });

        window.addEventListener('beforeunload', showPageLoader);
        window.addEventListener('pageshow', hidePageLoader);

        window.showPageLoader = showPageLoader;
        window.hidePageLoader = hidePageLoader;
    })();
</script>
