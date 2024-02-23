@extends('layout.layout')

@section('content')
<div class="error_msg">
    @if (Session::has('fails'))
    <div class="m-5 text-rose-500 bg-rose-200 ps-5 border-l-4 border-rose-500 py-2">{{ Session::get('fails') }}</div>
@endif
@if (Session::has('success'))
    <div class="m-5 text-emerald-500 bg-emerald-200 ps-5 border-l-4 border-emerald-500 py-2">{{ Session::get('success') }}</div>
@endif

</div>
    <div class="m-5">
        <div class="ms-1 flex justify-between">
            <span class="text-2xl font-serif tracking-wide">@switch($type)
                @case('user')
                Users Lists
                    @break
                @case('role')
                Roles Lists
                @break
                @case('permission')
                Permissions Lists
                @break
                @default
                Users Lists
            @endswitch</span>
            <?php
                switch ($type) {
                    case 'user':
                        $url = 'create_user';
                        break;
                    case 'role':
                        $url = 'create_role';
                        break;
                    case 'permission':
                        $url = 'create_permission';
                        break;
                    default:
                        $url = url()->current();
                        break;
                }
            ?>
            <button class="bg-emerald-400 px-2 py-1 rounded-md mr-2 hover:bg-emerald-600" onclick="javascirpt:window.location.href = '{{ $url }}'"><i class='bx {{ $type == 'user' ? 'bx-user-plus' : 'bx-list-plus'  }} text-white text-xl ms-1' ></i></button>
        </div>
        @if ($type == 'user')
            <form action="{{ route('user') }}" method="Get">
                <div class="grid grid-cols-7 gap-4">
                        <div class="flex flex-col">
                            <label for="branch">Choose Branch :</label>
                            <Select name="branch" id="branch" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Branch</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->id }}" {{ request('branch') == $item->id ? 'selected' : '' }}>{{ $item->branch_name }}</option>
                                @endforeach
                            </Select>
                        </div>

                        <div class="flex flex-col">
                            <label for="search_data">User Name / User Code :</label>
                            <input type="text" name="search_data" id="search_data" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('search_data') ?? '' }}">
                        </div>

                    <div class="">
                        <button class="bg-amber-400 h-10 w-[40%] rounded-lg ms-4 mt-9 hover:bg-amber-600 hover:text-white">Search</button>
                    </div>
                </div>
            </form>
        @endif

        <div class="">
            <table class="w-full mt-4">
                <thead>
                    @if ($type == 'user')
                        <tr class="">
                            <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                            <th class="py-2 bg-slate-400 border">User's Name</th>
                            <th class="py-2 bg-slate-400 border">User's Employee Code</th>
                            <th class="py-2 bg-slate-400 border">User's Branch</th>
                            <th class="py-2 bg-slate-400 border">User's Status</th>
                            <th class="py-2 bg-slate-400  rounded-tr-md">Action</th>
                        </tr>
                    @elseif ($type == 'role')
                        <tr class="">
                            <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                            <th class="py-2 bg-slate-400 border">Name</th>
                            <th class="py-2 bg-slate-400  rounded-tr-md">Action</th>
                        </tr>
                    @elseif ($type == 'permission')
                        <tr class="">
                            <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                            <th class="py-2 bg-slate-400 border">Name</th>
                            <th class="py-2 bg-slate-400  rounded-tr-md">Action</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    <input type="hidden" id="type" value="{{ $type }}">
                    @if ($type == 'user')
                        @foreach ($data as $item)
                            <tr>
                                <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->name }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->employee_code }}</td>
                                <td class="h-10 text-center border border-slate-400">{{ $item->branch->branch_name }}</td>
                                <td class="h-10 text-center border border-slate-400 {{ $item->active ? 'text-emerald-600' : 'text-rose-600' }} ">
                                    @if($item->role != 1)
                                    <span class="user_status">
                                        {{ $item->active ? 'Active' : 'Inactive' }}
                                    </span>

                                        <label class="relative inline-flex items-center cursor-pointer translate-y-1 ms-5">
                                            <input type="checkbox" value="{{ $item->id }}" class="sr-only peer user_active" {{ $item->active == 1 ? 'checked' : '' }}>
                                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                        </label>
                                        @endif
                                </td>
                                <td class="h-10 text-center border border-slate-400 ">
                                    @if ($item->role != 1)
                                    <button class="bg-sky-500 hover:bg-sky-700 px-1 rounded-md mr-1" onclick="window.location.href = 'edit_user/{{ $item->id }}'"><i class='bx bxs-edit text-white mt-1' ></i></button>
                                    <button class="bg-rose-500 hover:bg-rose-700 px-1 rounded-md mr-1 del_btn" data-id="{{ $item->id }}"><i class='bx bxs-trash-alt text-white mt-1'></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @elseif ($type == 'role')
                        @foreach ($data as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->name }}</td>
                            <td class="h-10 text-center border border-slate-400 ">
                                <button class="bg-sky-500 hover:bg-sky-700 px-1 rounded-md mr-1" onclick="window.location.href = 'edit_role/{{ $item->id }}'"><i class='bx bxs-edit text-white mt-1' ></i></button>
                                <button class="bg-rose-500 hover:bg-rose-700 px-1 rounded-md mr-1 del_btn" data-id="{{ $item->id }}"><i class='bx bxs-trash-alt text-white mt-1'></i></button>
                            </td>
                        </tr>
                    @endforeach
                    @elseif ($type == 'permission')
                        @foreach ($data as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->name }}</td>
                            <td class="h-10 text-center border border-slate-400 ">
                                <button class="bg-amber-500 hover:bg-amber-700 px-1 rounded-md mr-1" title="detail" onclick="window.location.href = 'view_permission/{{ $item->id }}'"><i class='bx bxs-info-circle text-white mt-1' ></i></button>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {{-- @if (request('search') || request('search_data') || request('branch') || request('status') || request('from_date') || request('to_date'))
        <div class="mt-2">
            <button class="bg-sky-600 text-white px-3 py-2 rounded-md" onclick="javascirpt:window.location.href = 'list'">Back to Default</button>
        </div>
        @endif --}}
        <div class="flex justify-center text-xs mt-2 bg-white mt-6">
            {{ $data->appends(request()->query())->links() }}

    </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function(){

                var token = $("meta[name='__token']").attr('content');

                $(document).on('click','.user_active',function(e){
                    $this = $(this);
                    $id = $this.val();
                    if($(this).prop('checked') == true){
                        $data = 1;
                    }else{

                        $data = 0;
                    }
                    $.ajax({
                        url : "{{ route('active_user') }}",
                        type: "POST",
                        data: {_token : token , data : $data , id : $id},
                        success : function(res){
                            if($data == 0)
                            {
                                $this.prop('checked',false);
                                $this.parent().parent().find('.user_status').text('Inactive');
                                $this.parent().parent().removeClass('text-emerald-600 text-rose-600');
                                $this.parent().parent().addClass('text-rose-600');
                            }else{
                                $this.prop('checked',true);
                                $this.parent().parent().find('.user_status').text('Active');
                                $this.parent().parent().removeClass('text-emerald-600 text-rose-600');
                                $this.parent().parent().addClass('text-emerald-600');
                            }
                        }
                    })
                })

                $(document).on('click','.del_btn',function(e){
                   $id = $(this).data('id');
                    $this = $(this);
                    $type = $('#type').val();
                    if($type == 'user')
                    {
                        $url = "{{ route('del_user') }}";
                    }else{
                        $url = "{{ route('del_role') }}"
                    }

                   Swal.fire({
                    icon : 'info',
                    title: 'Are You Sure?',
                    showCancelButton:true,
                    confirmButtonText:'Yes',
                    cancelButtonText: "No",
                   }).then((result)=>{
                    if(result.isConfirmed){
                        $.ajax({
                            url : $url,
                            type: 'post',
                            data: {_token:token , id : $id},
                            success: function(res){
                                $this.parent().parent().remove();
                                $('.error_msg').append(`
                                <div class="m-5 text-emerald-500 bg-emerald-200 ps-5 border-l-4 border-emerald-500 py-2">Delete Success</div>
                                `);
                            },
                            error: function(xhr,status,error)
                            {
                                $('.error_msg').append(`
                                <div class="m-5 text-rose-500 bg-rose-200 ps-5 border-l-4 border-rose-500 py-2">Delete Fails</div>
                                `);
                            }
                        })
                    }
                   })
                })
            })
        </script>
    @endpush
@endsection
