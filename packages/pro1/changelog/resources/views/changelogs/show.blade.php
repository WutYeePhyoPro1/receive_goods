@extends("changelogs::layouts.tailwindcentral.index")

<div class="notification-header">
    <div class="notification-container">
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="pendingCount">{{ Pro1\Changelog\Models\WhatsNew::getAuthUserWhatNews()->count() }}</span>
            </div>
            <div class="notification-text">
                <h4>What is News?</h4>
                <p>သင်မဖတ်ရှုရသေးသောစနစ်၏ထပ်တိုးပြင်ဆင်မှု ယခုစာစောင်အပါအဝင်<strong><span id="pendingText"><span id="whatsnewscount">{{ Pro1\Changelog\Models\WhatsNew::getAuthUserWhatNews()->count() }}</span> ခုရှိပါသည်</span></strong></p>
            </div>
            <a href="{{ route("whatsnews.index") }}" class="view-all-btn" onclick="toggleUpdatesList()">
                <i class="fas fa-list mr-2"></i>
                <span id="toggleText">View My News</span>
            </a>
        </div>

        <!-- Expandable Updates List -->
        {{-- <div class="updates-list" id="updatesList">
            <div class="updates-grid">
                <div class="update-card current">
                    <div class="update-status">
                        <i class="fas fa-eye text-blue-500"></i>
                        <span>Currently Viewing</span>
                    </div>
                    <div class="update-info">
                        <h5>Version 2.1.0 - Enhanced Analytics &amp; Dark Mode</h5>
                        <p>March 15, 2024 • 35 changes</p>
                    </div>
                    <div class="update-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" id="currentProgress" style="width: 0%;"></div>
                        </div>
                        <span class="progress-text" id="progressText">0% read</span>
                    </div>
                </div>

                <div class="update-card pending">
                    <div class="update-status">
                        <i class="fas fa-clock text-orange-500"></i>
                        <span>Pending Review</span>
                    </div>
                    <div class="update-info">
                        <h5>Version 2.0.8 - Security &amp; Performance</h5>
                        <p>March 8, 2024 • 12 changes</p>
                    </div>
                    <button class="review-btn" onclick="viewUpdate('2.0.8')">
                        <i class="fas fa-arrow-right mr-1"></i>
                        Review
                    </button>
                </div>

                <div class="update-card pending">
                    <div class="update-status">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        <span>Critical Update</span>
                    </div>
                    <div class="update-info">
                        <h5>Version 2.0.7 - Emergency Security Patch</h5>
                        <p>March 1, 2024 • 5 changes</p>
                    </div>
                    <button class="review-btn urgent" onclick="viewUpdate('2.0.7')">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Review Now
                    </button>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@section('content')
  <!-- Back button -->
    {{-- <div class="mb-6">
        <a href="#" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            <span>Back to Updates</span>
        </a>
    </div> --}}


    <!-- Header section -->
    <header class="relative mb-12 overflow-hidden rounded-3xl shadow-lg">
        <div class="gradient-bg text-white p-8 md:p-12">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -ml-20 -mb-20"></div>

            <div class="relative">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="version-badge">
                                <i class="fas fa-tag mr-2"></i>
                                {{-- Version 2.1.0 • March 15, 2024 --}}
                                Released on {{ \Carbon\Carbon::parse($changelog->release_date)->format('M d, Y') }}
                            </div>
                             <span class="badge-new text-white text-xs font-semibold px-3 py-1.5 rounded-full inline-flex items-center mb-2">
                                <i class="fas fa-star mr-1"></i> {{ $changelog->releasetype->name }}
                            </span>
                        </div>


                        <h1 class="text-4xl md:text-5xl font-bold my-2">{{ $changelog->title }}</h1>
                        {{-- <p class="text-lg text-indigo-100 mb-4">Released on May 15, 2023</p> --}}

                        <p class="text-indigo-100 max-w-2xl">{{ $changelog->description }}</p>
                    </div>

                    <div class="hidden md:block">
                        <div class="w-32 h-32 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-4xl font-bold">{{ $changelog->version_number ?? "" }}</div>
                                <div class="text-sm mt-1">RELEASE</div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="hero-stats">
                    {{-- <div class="stat-item">
                        <span  id="totalChanges"  class="stat-number">0</span>
                        <div class="stat-label">Total</div>
                    </div> --}}
                    <div class="stat-item">
                        <span  id="newFeatures"  class="stat-number">0</span>
                        <div class="stat-label">New Features</div>
                    </div>
                    <div class="stat-item">
                        <span id="improvements"  class="stat-number">0</span>
                        <div class="stat-label">Improvements</div>
                    </div>
                    <div class="stat-item">
                        <span id="bugFixes"  class="stat-number">0</span>
                        <div class="stat-label">Bug Fixes</div>
                    </div>
                    <div class="stat-item">
                        <span id="mediaCount"  class="stat-number">0</span>
                        <div class="stat-label">Media Updates</div>
                    </div>
                </div>

                <a href="#changesList">
                    <div class="mt-4 flex items-center">
                        <div class="scroll-indicator text-white opacity-80">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="ml-4 text-sm text-indigo-100">📢  Systemပြင်ဆင်မှုများရှိပါသဖြင့် ပြင်ဆင်ချက်များကို သေချာစွာဖတ်ရှုပြီး သဘောတူပါက "သဘောတူပါသည်"ကိုနှိပ်၍ ဆက်လက်အသုံးပြုနိုင်ပါသည်"</div>
                    </div>
                </a>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <div class="space-y-12">


        <section>
            {{-- <h2 class="text-2xl font-bold text-gray-800 mb-6">What's New</h2> --}}

            <div class="changes-containers">
                <div id="changesList">

                </div>
                <div class="text-center">
                    <form id="agreeForm" action="" method="">
                        <input type="hidden" name="user_id" id="user_id" class="user_id" value="{{Auth::id()}}"/>
                        <input type="hidden" name="change_log_id" id="change_log_id" class="change_log_id" value="{{ $changelog->id }}"/>

                        @if(!$changelog->isAgreed())
                        <button type="button" id="agree-btn" class="px-6 py-3 text-center bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 my-2">
                            <i class="far fa-heart mr-2"></i>သဘောတူပါသည်
                        </button>
                        @endif
                    </form>

                </div>

            </div>

        </section>
    </div>

    <!-- Footer -->
    <footer class="mt-12 text-center text-gray-500 text-sm">
        <p>Version 2.1.0 • Released May 15, 2023</p>
        <p class="mt-2">© 2023 Pro1 Global Home Center. All rights reserved.</p>
    </footer>

@endsection


@section('css')

<style type="text/css">
.gradient-bg {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}
.scroll-indicator {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}


.version-badge {
    display: inline-flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50px;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    margin: 10px 0px;
    animation: fadeInUp 0.6s ease;
}


.feature-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-card.new-feature {
    border-left-color: #8b5cf6;
}

.feature-card.improvements {
    border-left-color: #3b82f6;
}

.feature-card.bug-fixes {
    border-left-color: #ef4444;
}

.badge-new-feature {
    background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
}

.badge-improvements {
    background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
}

.badge-bug-fixes {
    background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
}
.image-card {
    overflow: hidden;
    transition: all 0.3s ease;
}

.image-card:hover {
    transform: scale(1.02);
}

.image-card img {
    transition: all 0.5s ease;
}

.image-card:hover img {
    transform: scale(1.05);
}
.changes-container {
    /* background: white; */
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
    margin-bottom: 40px;
}
 .changes-header {
    /* background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); */
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 40%);
    padding: 40px;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}
.changes-title {
    font-size: 2rem;
    font-weight: 700;
    /* color: #1e293b; */
    margin-bottom: 12px;
}

.changes-subtitle {
    /* color: #64748b; */
    font-size: 1.125rem;
}



.notification-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    width: 100%;
}

.notification-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 16px 20px;
}

.notification-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.notification-icon {
    position: relative;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    animation: pulse 2s infinite;
}

.notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    border: 2px solid white;
}

.notification-text {
    flex: 1;
}

.notification-text h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 4px;
}

.notification-text p {
    color: #64748b;
    font-size: 0.875rem;
}
.view-all-btn {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 12px 20px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.view-all-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
}

.badge-new {
    background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%);
}


.hero-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 24px;
    margin-top: 10px;
    animation: fadeInUp 1.2s ease;
}

.stat-item {
    text-align: center;
    padding: 10px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #fbbf24;
    display: block;
}

.stat-label {
    font-size: 0.875rem;
    color: #e0e7ff;
    margin-top: 4px;
}
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<style type="text/css">
h1, h2, h3, h4, h5, h6 {
    font-weight: 500;
    margin: 0.67em 0;
}
 ul {
    list-style: disc inside !important;
    margin-bottom: 1rem !important;
    padding-left: 1.5rem ;
    list-style-position: outside !important;
}
 ol {
    list-style: decimal inside !important;
    margin-bottom: 1rem !important;
    padding-left: 1.5rem;
    list-style-position: outside !important;
}
 table {
    border-collapse: collapse;
    width: 100%;
}
 th,  td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    vertical-align: top;
}

</style>
@endsection

@section('scripts')
{{-- sweetalert js1 --}}
<script src="{{ asset('vendor/pro1/changelog/assets/libs/sweetalert2/sweetalert2@11.js') }}" type="text/javascript"></script>


<script type="text/javascript">
    $(document).ready(function(){
        // Start Passing Header Token
        $.ajaxSetup({
                headers:{
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                }
        });
        // End Passing Header Token



        // Global Variables
        let changes = [];

        function fetchchanges(){
            $.ajax({
                url:'{{url("/api/changelogs/$changelog->id/changes")}}',
                meethod:"GET",
                dataType:"json",
                success:function(response){
                    {{-- console.log(response); --}}
                    const datas = response;
                    {{-- console.log(datas); --}}

                    changes = datas;

                    updateChangesList();
                    updateStats();
                    {{-- console.log(mediafiles); --}}
                }
            });

        }
        fetchchanges();



        function updateChangesList(){
            $('#changesList').html('');

            let html = '';
            changes.forEach((change,idx)=>{
                html += `
                <div class="feature-card ${change.changetype.slug} bg-white rounded-xl shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 mb-6 md:mb-0 md:pr-6">
                        <div class="flex items-start mb-4">
                            <span class="${change.change_type_id == 1 ? 'badge-bugfix' : (change.change_type_id == 2 ? 'badge-improvement' : ( change.change_type_id == 3 ? 'badge-feature' : '' )) } text-white text-xs font-semibold px-3 py-1.5 rounded-lg mr-3 flex items-center flex-none">
                                <i class="fas ${change.change_type_id == 1 ? 'fa-bug' : (change.change_type_id == 2 ? 'fa-arrow-up' : ( change.change_type_id == 3 ? 'fa-star' : '' )) } mr-1"></i> ${change.changetype.name}
                            </span>
                            <h3 class="text-xl font-bold text-gray-800">${change.title}</h3>
                        </div>

                        <p class="text-gray-600 mb-6">${change.description}</p>

                        {{-- <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-indigo-600 mt-1 mr-3"></i>
                                <span>Real-time data visualization with beautiful charts</span>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-indigo-600 mt-1 mr-3"></i>
                                <span>Customizable dashboard with drag-and-drop widgets</span>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-indigo-600 mt-1 mr-3"></i>
                                <span>Export capabilities in CSV, PDF, and Excel formats</span>
                            </div>
                        </div> --}}

                        {{-- <div class="mt-6">
                            <div class="flex items-center">
                                <div class="avatar-group flex">
                                    <div class="avatar w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">JS</div>
                                    <div class="avatar w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold">AL</div>
                                </div>
                                <span class="ml-2 text-sm text-gray-500">Designed by John & Alex</span>
                            </div>
                        </div> --}}
                    </div>

                    <div class="md:w-1/2 ">
                        <div class="grid grid-cols-${change.changelogfiles.length <=2 ? '1' : '2'} gap-4">

                                ${change.changelogfiles.map((file,index) => {
                                    const url = file.mediafile;
                                    if (isImageFile(url)) {
                                        return `
                                        <div class="image-card rounded-xl overflow-hidden shadow-sm">
                                            <a href="{{asset('${url}')}}" data-fancybox="change${change.id}-changelogfile" data-caption="Change ${idx+1} Media"><img src="{{asset('${url}')}}" alt="${file.name}" class="w-full h-[180px] object-cover rounded" /></a>
                                        </div>
                                        `;
                                    } else if (isVideoFile(url)) {
                                        return `
                                        <div class="image-card rounded-xl overflow-hidden shadow-sm">
                                            <a href="{{asset('${url}')}}" data-fancybox="change${change.id}-changelogfile" data-caption="Change ${idx+1} Media"><video src="{{asset('${url}')}}" class="w-full h-[180px] object-cover rounded" controls></video></a>
                                        </div>
                                        `;
                                    }
                                    return '';
                                }).join('')}
                        </div>
                    </div>
                </div>
            </div>
                `
            });
            $('#changesList').html(html);

        }
        function isImageFile(url){
            const extension = url.split('.').pop().toLowerCase();
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

            return imageExtensions.includes(extension);
        }

        function isVideoFile(url){
            const extension = url.split('.').pop().toLowerCase();
            const videoExtensions = ['mp4', 'webm', 'ogg'];

            return videoExtensions.includes(extension);
        }


        $('#agree-btn').click(function(e){
            console.log("Agree");
             $.ajax({
                url:'{{url("/api/changelogs/$changelog->id/agree")}}',
                meethod:"GET",
                data: $('#agreeForm').serialize(),
                dataType:"json",
                success:function(response){
                    {{-- console.log(response); --}}
                    const datas = response;
                    console.log(datas);

                    Swal.fire({
                        icon: 'success',
                        title: "ကျေးဇူးတင်ပါသည်။ စနစ်၏ အဆင့်မြှင့်တင်မှုများအတွက် သဘောတူညီချက်ကို အတည်ပြုပြီးဖြစ်ပါသည်။",
                        text: "စနစ်၏ အသစ်ထည့်သွင်းထားသော လုပ်ဆောင်ချက်များကို သင်အသုံးပြုနိုင်ပါပြီ။",
                        confirmButtonText: "{{ __('message.ok') }}",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/home';
                        }
                    });
                }
            });

        });


    function updateStats() {
        const total = changes.length;
        const bugfixes = changes.filter(c => c.changetype.id === 1).length;
        const improvements = changes.filter(c => c.changetype.id === 2).length;
        const features = changes.filter(c => c.changetype.id === 3).length;
        const mediafiles = changes.reduce((sum, c) => sum + (c.mediafiles ? c.mediafiles.length : 0), 0);
        const changelogfiles = changes.reduce((sum, c) => sum + (c.changelogfiles ? c.changelogfiles.length : 0), 0);


        {{-- document.getElementById('totalChanges').textContent = total; --}}
        document.getElementById('newFeatures').textContent = features;
        document.getElementById('improvements').textContent = improvements;
        document.getElementById('bugFixes').textContent = bugfixes;
        document.getElementById('mediaCount').textContent = mediafiles+changelogfiles;
    }


    })
</script>
@endsection


   {{-- <div class="md:w-1/2 ">
                        <div class="grid grid-cols-1">
                             <div class="image-card rounded-xl overflow-hidden shadow-sm">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='400' viewBox='0 0 800 400'%3E%3Crect width='800' height='400' fill='%23f8fafc'/%3E%3Crect x='40' y='40' width='720' height='320' rx='8' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Crect x='60' y='60' width='200' height='120' rx='6' fill='%23c7d2fe'/%3E%3Ccircle cx='160' cy='100' r='30' fill='%238b5cf6'/%3E%3Ctext x='160' y='140' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3ETotal Users%3C/text%3E%3Ctext x='160' y='165' font-family='Arial' font-size='20' font-weight='bold' text-anchor='middle' fill='%234b5563'%3E24,521%3C/text%3E%3Crect x='280' y='60' width='200' height='120' rx='6' fill='%23dbeafe'/%3E%3Cpath d='M380 100 L350 120 L360 90 L380 80 L400 90 L410 120 L380 100' fill='%233b82f6'/%3E%3Ctext x='380' y='140' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3EActive Sessions%3C/text%3E%3Ctext x='380' y='165' font-family='Arial' font-size='20' font-weight='bold' text-anchor='middle' fill='%234b5563'%3E1,342%3C/text%3E%3Crect x='500' y='60' width='200' height='120' rx='6' fill='%23fae8ff'/%3E%3Crect x='550' y='90' width='10' height='40' fill='%23d946ef'/%3E%3Crect x='570' y='80' width='10' height='50' fill='%23d946ef'/%3E%3Crect x='590' y='70' width='10' height='60' fill='%23d946ef'/%3E%3Crect x='610' y='100' width='10' height='30' fill='%23d946ef'/%3E%3Crect x='630' y='85' width='10' height='45' fill='%23d946ef'/%3E%3Ctext x='600' y='140' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3EConversion Rate%3C/text%3E%3Ctext x='600' y='165' font-family='Arial' font-size='20' font-weight='bold' text-anchor='middle' fill='%234b5563'%3E8.7%25%3C/text%3E%3Crect x='60' y='200' width='440' height='140' rx='6' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Cpath d='M80 300 L140 260 L200 280 L260 240 L320 260 L380 220 L440 240 L480 260' stroke='%238b5cf6' stroke-width='3' fill='none'/%3E%3Ccircle cx='140' cy='260' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='200' cy='280' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='260' cy='240' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='320' cy='260' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='380' cy='220' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='440' cy='240' r='4' fill='%238b5cf6'/%3E%3Ctext x='280' y='220' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3EUser Growth Trend%3C/text%3E%3Crect x='520' y='200' width='180' height='140' rx='6' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Ccircle cx='610' cy='250' r='50' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Cpath d='M610 250 L610 200 A50 50 0 0 1 656 275 L610 250' fill='%238b5cf6'/%3E%3Cpath d='M610 250 L656 275 A50 50 0 0 1 564 275 L610 250' fill='%233b82f6'/%3E%3Cpath d='M610 250 L564 275 A50 50 0 0 1 610 200 L610 250' fill='%23d946ef'/%3E%3Ctext x='610' y='320' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3ETraffic Sources%3C/text%3E%3C/svg%3E" alt="Analytics Dashboard" class="w-full h-auto">
                            </div>
                            <div class="image-card rounded-xl overflow-hidden shadow-sm">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='400' viewBox='0 0 800 400'%3E%3Crect width='800' height='400' fill='%23f8fafc'/%3E%3Crect x='40' y='40' width='720' height='320' rx='8' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Crect x='60' y='60' width='200' height='120' rx='6' fill='%23c7d2fe'/%3E%3Ccircle cx='160' cy='100' r='30' fill='%238b5cf6'/%3E%3Ctext x='160' y='140' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3ETotal Users%3C/text%3E%3Ctext x='160' y='165' font-family='Arial' font-size='20' font-weight='bold' text-anchor='middle' fill='%234b5563'%3E24,521%3C/text%3E%3Crect x='280' y='60' width='200' height='120' rx='6' fill='%23dbeafe'/%3E%3Cpath d='M380 100 L350 120 L360 90 L380 80 L400 90 L410 120 L380 100' fill='%233b82f6'/%3E%3Ctext x='380' y='140' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3EActive Sessions%3C/text%3E%3Ctext x='380' y='165' font-family='Arial' font-size='20' font-weight='bold' text-anchor='middle' fill='%234b5563'%3E1,342%3C/text%3E%3Crect x='500' y='60' width='200' height='120' rx='6' fill='%23fae8ff'/%3E%3Crect x='550' y='90' width='10' height='40' fill='%23d946ef'/%3E%3Crect x='570' y='80' width='10' height='50' fill='%23d946ef'/%3E%3Crect x='590' y='70' width='10' height='60' fill='%23d946ef'/%3E%3Crect x='610' y='100' width='10' height='30' fill='%23d946ef'/%3E%3Crect x='630' y='85' width='10' height='45' fill='%23d946ef'/%3E%3Ctext x='600' y='140' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3EConversion Rate%3C/text%3E%3Ctext x='600' y='165' font-family='Arial' font-size='20' font-weight='bold' text-anchor='middle' fill='%234b5563'%3E8.7%25%3C/text%3E%3Crect x='60' y='200' width='440' height='140' rx='6' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Cpath d='M80 300 L140 260 L200 280 L260 240 L320 260 L380 220 L440 240 L480 260' stroke='%238b5cf6' stroke-width='3' fill='none'/%3E%3Ccircle cx='140' cy='260' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='200' cy='280' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='260' cy='240' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='320' cy='260' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='380' cy='220' r='4' fill='%238b5cf6'/%3E%3Ccircle cx='440' cy='240' r='4' fill='%238b5cf6'/%3E%3Ctext x='280' y='220' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3EUser Growth Trend%3C/text%3E%3Crect x='520' y='200' width='180' height='140' rx='6' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Ccircle cx='610' cy='250' r='50' fill='%23ffffff' stroke='%23e2e8f0' stroke-width='1'/%3E%3Cpath d='M610 250 L610 200 A50 50 0 0 1 656 275 L610 250' fill='%238b5cf6'/%3E%3Cpath d='M610 250 L656 275 A50 50 0 0 1 564 275 L610 250' fill='%233b82f6'/%3E%3Cpath d='M610 250 L564 275 A50 50 0 0 1 610 200 L610 250' fill='%23d946ef'/%3E%3Ctext x='610' y='320' font-family='Arial' font-size='14' text-anchor='middle' fill='%234b5563'%3ETraffic Sources%3C/text%3E%3C/svg%3E" alt="Analytics Dashboard" class="w-full h-auto">
                            </div>
                        </div>
                    </div> --}}
