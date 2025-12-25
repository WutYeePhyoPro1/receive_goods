@extends("changelogs::layouts.tailwindcentral.index")

@section('content')
    <header class="mb-16 text-center relative">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-indigo-500 rounded-full opacity-10 blur-3xl"></div>
        <div class="absolute -top-5 -right-20 w-60 h-60 bg-purple-500 rounded-full opacity-10 blur-3xl"></div>

        <h1 class="text-5xl font-bold mb-3 header-gradient relative z-10">System Change Log</h1>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg">Track all updates, improvements, and bug fixes to our system in one place.</p>

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
        function fetchalldatas(){
             $("#changelog-container").html('');
                $.ajax({
                    url:"{{url('/whatsnews')}}",
                    method:"GET",
                    data: {
                        status : '{{ request()->status }}'
                    },
                    dataType:"json",
                    success:function(response){
                        console.log(response); // {status: 'scuccess', data: Array(2)}
                        const datas = response.data;
                        console.log(datas);

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

                                                            ${data.isAgreed ? `<span class='text-indigo-700 ms-2'><i class='fas fa-check-double '></i> Read On ${data.isAgreed}</span>`  : '' }
                                                        {{-- ${data.isAgreed ? "<div class='inline w-auto bg-indigo-200 px-2 py-2'> <i class='fas fa-check-double text-indigo-700'></i></div>" : ''} --}}

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
            const id = $(this).data("id");
            console.log(id);
            $.ajax({
                url:`api/changelogs/${id}`,
                method:"DELETE",
                dataType:"json",
                success:function(response){
                    console.log(response);


                    Swal.fire({
                        title: "Deleted!",
                        text: "Change Log Deleted Successfully",
                        icon: "success"
                    });
                    fetchalldatas();


                }
            });

        });
    })
</script>
@endsection
