@extends("changelogs::layouts.tailwindcentral.index")

@section('content')
    <header class="mb-16 text-center relative">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -top-5 -right-20 w-60 h-60 bg-purple-500 rounded-full opacity-10 blur-3xl"></div>

        <h1 class="text-5xl font-bold mb-3 header-gradient relative z-10">System Change Log</h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg">Track all updates, improvements, and bug fixes to our system in one place.</p>

        <form id="changelogform" action="" method="">
        <div class="search-container mt-10 flex flex-col md:flex-row justify-center items-center gap-4">

            <div class="flex justify-center items-center">
                <a href="{{ route("changelogs.create") }}" type="button" id="addChangeBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors mr-2 flex-none">
                    <i class="fas fa-plus mr-2"></i>Create
                </a>

                <div class="relative w-full md:w-auto">
                    <input type="text" id="title" name="title" placeholder="Search changes.... & Enter" class="search-input pl-12 pr-4 py-3 w-full md:w-80 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-indigo-500"></i>
                    {{-- <label for="search-input" class="floating-label text-gray-500">Search changes...</label> --}}
                </div>
            </div>

                <div id="filter-all" class="filter-pill px-4 py-2 active text-indigo-600 border border-indigo-700 rounded-full shadow-md hover:shadow-lg flex items-center gap-2 filter_changetypes" data-changetypeid="0">
                    <i class="fas fa-list-ul"></i>
                    <span>All</span>
                    <span id="allcount" class="count-badge bg-purple-400 text-white px-2.5 py-0.5 rounded-full text-xs font-bold min-w-[28px] text-center">0</span>
                </div>

                <div id="filter-bugfix" class="filter-pill bug-fixes px-4 py-2 bg-white text-red-600 border border-red-200 rounded-full shadow-md hover:shadow-lg flex items-center gap-2 filter_changetypes" data-changetypeid="1">
                    <i class="fas fa-bug"></i>
                    <span>Fixes</span>
                    <span id="bugfixescount" class="count-badge bg-red-100 text-red-700 px-2.5 py-0.5 rounded-full text-xs font-bold min-w-[28px] text-center pulse-badge">0</span>
                </div>
                <div id="filter-improvement" class="filter-pill improvements  px-4 py-2 bg-white text-blue-600 border border-blue-200 rounded-full shadow-md hover:shadow-lg flex items-center gap-2 filter_changetypes" data-changetypeid="2">
                    <i class="fas fa-arrow-up"></i>
                    <span>Improvements</span>
                    <span id="improvementscount" class="count-badge bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-bold min-w-[28px] text-center">0</span>
                </div>
                <div id="filter-feature" class="filter-pill new-feature px-4 py-2 bg-white text-purple-600 border border-purple-200 rounded-full shadow-md hover:shadow-lg flex items-center gap-2 filter_changetypes" data-changetypeid="3">
                    <i class="fas fa-star"></i>
                    <span>Features</span>
                    <span id="newfeaturescount" class="count-badge bg-purple-100 text-purple-700 px-2.5 py-0.5 rounded-full text-xs font-bold min-w-[28px] text-center">0</span>
                </div>
        </div>
        </form>
    </header>

    <div id="changelog-container" class="changelog-container">

        <div class="flex justify-center mt-12">
            <button id="load-more" class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transform hover:-translate-y-1 flex items-center gap-2">
                <i class="fas fa-history"></i>
                <span>Load More Versions</span>
            </button>
        </div>
    </div>
@endsection


@section('css')

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

        // Start Fetch All Datas
        function fetchalldatas(changetypeid){


             $("#changelog-container").html('');
                $.ajax({
                    url:"{{url('/api/changelogs')}}",
                    meethod:"GET",
                    data: {
                        change_type_id: changetypeid,
                        title: $('#title').val()
                    },
                    dataType:"json",
                    success:function(response){
                        const datas = response.data;

                        let html = '';
                        datas.forEach(function(data,idx){
                            // console.log(data);
                            html += `
                                    <div class="flex mb-12">
                                        <div class="flex flex-col items-center mr-6">
                                            <div class="version-dot bg-indigo-500"></div>
                                            <div class="version-line flex-grow my-2"></div>
                                        </div>
                                        <div class="w-full">
                                            <div class="changelog-item glass-effect rounded-2xl shadow-lg p-6 mb-8">
                                                ${ idx == 0 ? "<div class='absolute top-0 right-0 bg-indigo-600 text-white px-6 py-1 rounded-bl-2xl font-medium'>Latest</div>" : "" }

                                                <div class="flex justify-between items-center mb-6 cursor-pointer ">
                                                    <div>
                                                        <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                                                            ${data.title}
                                                            {{-- <span class="ml-3 px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">Patch</span> --}}
                                                        </h2>
                                                        <p class="text-gray-500 mt-1 flex items-center">
                                                            <i class="far fa-calendar-alt mr-2"></i>  ${data.created_at}
                                                            <span class="mx-3">•</span>
                                                            <i class="far fa-clock mr-2"></i> ${data.created_at_diff}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full mr-3">${data.subchanges.length} changes</span>
                                                        <button class="bg-indigo-100 hover:bg-indigo-200 transition-colors p-2 rounded-full toggle-version">
                                                            <i class="fas fa-chevron-down text-indigo-600 rotate-icon"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="expanded-content pt-2">
                                                    <div class="space-y-4">

                                                        ${data.subchanges.map((subchange,idx) => `
                                                        <div class="change-item ${subchange.changetype.slug} p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all">
                                                                <div class="flex items-start">
                                                                    <span class="flex-none ${subchange.changetype.id == 1 ? 'badge-bugfix' : (subchange.changetype.id == 2 ? 'badge-improvement' : ( subchange.changetype.id == 3 ? 'badge-feature' : '' )) }  text-white text-xs font-semibold px-3 py-1.5 rounded-lg mr-3 mt-1 flex items-center">
                                                                        <i class="fas ${subchange.changetype.id == 1 ? 'fa-bug' : (subchange.changetype.id == 2 ? 'fa-arrow-up' : ( subchange.changetype.id == 3 ? 'fa-star' : '' )) } mr-1"></i> ${subchange.changetype.name}
                                                                    </span>
                                                                    <div>
                                                                        <h3 class="font-semibold text-gray-800 text-lg">${subchange.title}</h3>
                                                                        <p class="text-gray-600 mt-1">${ subchange.description }</p>
                                                                        {{-- <div class="mt-3 flex gap-2">
                                                                            <span class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded-md">Data</span>
                                                                            <span class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded-md">Export</span>
                                                                        </div> --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `).join('')}

                                                        <div class="my-4 flex justify-between">
                                                            <a href="/changelogs/${data.id}" class="text-indigo-600">Read More...</a>

                                                            <div class="flex gap-4">
                                                                <a href="/changelogs/${data.id}/edit" type="button" class="edit-change-btn text-blue-600 hover:text-blue-800">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <button type="button" class="delete-change-btn text-red-600 hover:text-red-800" data-id="${data.id}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            `;

                        });
                        $("#changelog-container").prepend(html);

                    }
                });
        }
        fetchalldatas();
        // End Fetch All Datas



            // Toggle version details
            $(document).on('click', '.toggle-version', function () {
                const parent = $(this).closest('.changelog-item');
                {{-- console.log(parent); --}}
                parent.toggleClass('expanded');
            });


        $(document).on('click',".delete-change-btn",function(){

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    const id = $(this).data("id");
                    console.log(id);
                    $.ajax({
                        url:`api/changelogs/${id}`,
                        method:"DELETE",
                        dataType:"json",
                        success:function(response){

                            Swal.fire({
                                title: "Deleted!",
                                text: "Change Log Deleted Successfully",
                                icon: "success"
                            });
                            fetchalldatas();


                        }
                    });

                }
            });




        });

        var changetypeid = ''
        $('.filter_changetypes').click(function(){
            $('.filter_changetypes').removeClass('active');
            $(this).addClass('active')

            changetypeid = $(this).data('changetypeid');
            fetchalldatas(changetypeid);
        })



        $.ajax({
		url: '/api/changelogsdashboard',
		method: 'GET',
            success:function(data){
                console.log(data)



                $('#allcount').text(data.all);
                $('#bugfixescount').text(data.bugfixes);
                $('#improvementscount').text(data.improvements);
                $('#newfeaturescount').text(data.newfeatures);

            }
        });


        $("#changelogform").submit(function(e){
            e.preventDefault();
            {{-- console.log("Form Submitted"); --}}
            fetchalldatas(changetypeid);
        });
    })
</script>
@endsection
