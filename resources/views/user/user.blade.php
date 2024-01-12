@extends('layout.layout')

@section('content')
    <div class="m-5">
        <div class="ms-1 flex justify-between">
            <span class="text-2xl font-serif tracking-wide">Users List</span>
            <button class="bg-emerald-400 px-2 py-1 rounded-md mr-2 hover:bg-emerald-600" onclick="javascirpt:window.location.href = 'create_user'"><i class='bx bx-user-plus text-white text-xl ms-1' ></i></button>
        </div>
        {{-- <form action="{{ route('list') }}" method="Get">
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
                        <label for="status">Choose Status :</label>
                        <Select name="status" id="status" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                            <option value="">Choose Status</option>
                            <option value="complete" {{ request('status')== 'complete' ? 'selected' : '' }}>Complete</option>
                            <option value="incomplete" {{ request('status')== 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="from_date">From Date :</label>
                        <input type="date" name="from_date" id="from_date" class="px-4  h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('from_date') ?? '' }}">
                    </div>

                    <div class="flex flex-col">
                        <label for="to_date">To Date :</label>
                        <input type="date" name="to_date" id="to_date" class="px-4  h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('to_date') ?? '' }}">
                    </div>

                    <div class="flex flex-col">
                        <label for="status">Choose Search Method :</label>
                        <Select name="search" id="search" class="h-10 mt-3 rounded-t-lg px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                            <option value="" selected>Choose Method</option>
                            <option value="document_no" {{ request('search')=='document_no' ? 'selected' : '' }}>Document No</option>
                            <option value="truck_no" {{ request('search')=='truck_no' ? 'selected' : '' }}>Truck No</option>
                            <option value="driver_name" {{ request('search')=='driver_name' ? 'selected' : '' }}>Driver_name</option>
                        </Select>
                    </div>

                    <div class="flex flex-col">
                        <label for="search_data">Search Data :</label>
                        <input type="text" name="search_data" id="search_data" class="px-4 w-[80%] h-10 border border-slate-400 rounded-md mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2" value="{{ request('search_data') ?? '' }}">
                    </div>

                <div class="">
                    <button class="bg-amber-400 h-10 w-[40%] rounded-lg ms-4 mt-9 hover:bg-amber-600 hover:text-white">Search</button>
                </div>
            </div>
        </form> --}}


        <div class="">
            <table class="w-full mt-4">
                <thead>
                    <tr class="">
                        <th class="py-2 bg-slate-400  rounded-tl-md w-10"></th>
                        <th class="py-2 bg-slate-400 border">User's Name</th>
                        <th class="py-2 bg-slate-400 border">User's Employee Code</th>
                        <th class="py-2 bg-slate-400 border">User's Branch</th>
                        <th class="py-2 bg-slate-400 border">User's Status</th>
                        <th class="py-2 bg-slate-400  rounded-tr-md">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="h-10 text-center border border-slate-400">{{ $data->firstItem()+$loop->index  }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->name }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->employee_code }}</td>
                            <td class="h-10 text-center border border-slate-400">{{ $item->branch->branch_name }}</td>
                            <td class="h-10 text-center border border-slate-400 {{ $item->active ? 'text-emerald-600' : 'text-rose-600' }}">{{ $item->active ? 'Active' : 'Inactive' }}</td>
                            <td class="h-10 text-center border border-slate-400 ">
                                @if ($item->role != 1)
                                <button class="bg-sky-500 px-1 rounded-md mr-1"><i class='bx bxs-edit text-white mt-1' ></i></button>
                                <button class="bg-rose-500 px-1 rounded-md mr-1"><i class='bx bxs-trash-alt text-white mt-1'></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if (request('search') || request('search_data') || request('branch') || request('status') || request('from_date') || request('to_date'))
        <div class="mt-2">
            <button class="bg-sky-600 text-white px-3 py-2 rounded-md" onclick="javascirpt:window.location.href = 'list'">Back to Default</button>
        </div>
        @endif
        <div class="flex justify-center text-xs mt-2 bg-white mt-6">
            {{ $data->appends(request()->query())->links() }}

    </div>
    </div>

    @push('js')
    @endpush
@endsection
