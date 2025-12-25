@include("changelogs::layouts.tailwindcentral.header")
    <div id="app">

        <div class="container mx-auto px-4 py-12 max-w-5xl">
            @yield('content')

              <!-- Back button -->
            <div class="mb-6">
                <a href="javascript:void(0)" type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-800 transition-colors"
                onclick="window.history.back()">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>
@include("changelogs::layouts.tailwindcentral.footer")
