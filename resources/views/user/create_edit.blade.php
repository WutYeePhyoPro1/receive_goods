@extends('layout.layout')

@section('content')
@push('css')
    <style>
        .ts-control>.item {
            background-color: #EFEFEF !important;
            color: black !important;
            border: none !important;
            background-image: none !important;
        }
    </style>
@endpush
<div class="px-20 mt-20">
    @if (session('fails'))
    <div class="my-4 bg-rose-200 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-red-600" style="width:99%">
        {{ session('fails') }}
    </div>
    @endif
        <fieldset class="mt-3 border border-slate-500 rounded-md p-5">
            <legend class="px-4 text-2xl font-serif"> @switch($type)
                @case('user')
                    @if (!isset($data))
                    Create User
                    @else
                    Edit User
                    @endif
                    @break
                @case('role')
                    @if (!isset($data))
                        Create Role
                    @else
                        Edit Role
                    @endif
                    @break
                @case('permission')
                        Create Permission
                @break
                @case('gate')
                    Create Gate
                @break
                @case('car_type')
                    Create Car Type
                @break
                @default
                    Error
                @break
            @endswitch </legend>

            @if ($type == 'user')
                <form action="{{ isset($data) ? route('update_user') : route('store_user') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="name">Name<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="name" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('name',$data->name ?? '') }}">
                            @error('name')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <input type="hidden" name="{{ isset($data) ? 'id' : '' }}" value="{{ isset($data) ? $data->id : '' }}">
                        <div class="flex flex-col px-10">
                            <label for="employee_code">Employee Code<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="employee_code" id="employee_code" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('employee_code',$data->employee_code ?? '') }}">
                            @error('employee_code')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="password">Password<span class="text-rose-600">*</span> :</label>
                            <input type="password" name="password" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" autocomplete="off" value="{{
                            $data->password_str ?? '' }}">
                            @error('password')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>

                        <div class="flex flex-col px-10">
                            <label for="password_confirmation">Confirm Password<span class="text-rose-600">*</span> :</label>
                            <input type="password" name="password_confirmation" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" autocomplete="off" value="{{ $data->password_str ?? '' }}">
                            @error('password_confirmation')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="department">Department<span class="text-rose-600">*</span> :</label>
                            <Select name="department" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Department</option>
                                @foreach ($department as $item)
                                    <option value="{{ $item->id }}" {{ isset($data) ? ($data->department_id == $item->id ? 'selected' : '') : (old('department') == $item->id ? 'selected' : '') }}>{{ $item->name }}</option>
                                @endforeach
                            </Select>
                            @error('department')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                            <div class="flex flex-col px-10">
                                <label for="status">Status<span class="text-rose-600">*</span> :</label>
                                <Select name="status" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                    <option value="">Choose Status</option>
                                    <option value="active" {{ isset($data) ? ($data->status == 1 ? 'selected' : '') : (old('status') == 'active' ? 'selected' : '') }}>Active</option>
                                    <option value="inactive" {{ isset($data) ? ($data->status == 0 ? 'selected' : '') : (old('status') == 'inactive' ? 'selected' : '') }}>Inactive</option>
                                </Select>
                                @error('status')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                            </div>

                    </div>

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="branch">Branch<span class="text-rose-600">*</span> :</label>
                            <Select name="branch[]" id="branch" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;" multiple>
                                <option value="">Choose Branch</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->id }}" {{ isset($user_branch) ? (in_array($item->id,$user_branch->toArray()) ? 'selected' : (isset($data) ? ($data->branch_id == $item->id ? 'selected' : '') : '')) : (old('branch') == $item->id ? 'selected' : '') }}>{{ $item->branch_name }}</option>
                                @endforeach
                            </Select>
                            @error('branch')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        
                        </div>
                        <div class="flex flex-col px-10">
                            <label for="role">Role<span class="text-rose-600">*</span> :</label>
                            <Select name="role" id="role" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;" >
                                @foreach ($role as $item)
                                    <option value="{{ $item->id }}" {{ isset($data) ? ($data->role == $item->id ? 'selected' : '') : (old('role') == $item->id ? 'selected' : '') }} {{ $item->id == 2 ? 'selected' : '' }}>{{
                                    ucwords($item->name) }}</option>
                                @endforeach
                                    {{-- <option value="3" {{ isset($data) ? ($data->role == 3 ? 'selected' : '') : (old('role') == 3 ? 'selected' : '') }}>Supervisor</option>
                                    <option value="4" {{ isset($data) ? ($data->role == 4 ? 'selected' : '') : (old('role') == 4 ? 'selected' : '') }}>Manager</option> --}}
                            </Select>
                            @error('Role')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="">
                        </div>
                        <div class="">
                            <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">{{ isset($data) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>

                </form>
            @elseif ($type == 'role')
                <form action="{{ isset($data) ? route('update_role') : route('store_role') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="role">Role name<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="role" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('role',$data->name ?? '') }}">
                            @error('role')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <input type="hidden" name="{{ isset($data) ? 'id' : '' }}" value="{{ isset($data) ? $data->id : '' }}">

                    </div>

                    <div class="grid grid-cols-4 gap-5 my-5 ">
                            @foreach ($permission->chunk(4) as $item)
                                    @foreach ($item as $index=>$tem)
                                        <div class="flex px-10">
                                            <input type="checkbox" name="permission[]" value="{{ $tem->id }}" class="per_check" id="per_{{ $index }}" {{ isset($data) ? ($data->permissions->contains($tem) ? 'checked' : '') : '' }}>
                                            <label class="ms-4 cursor-pointer select-none" for="per_{{ $index }}">{{ $tem->name }}</label>
                                        </div>
                                    @endforeach
                            @endforeach
                            @error('permission')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                    </div>


                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="">
                        </div>
                        <div class="">
                            <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">{{ isset($data) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>

                </form>
            @elseif ($type == 'permission')
                <form action="{{ route('store_permission') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="permission">Permission name<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="permission" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('permission') }}">
                            @error('permission')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="">
                        </div>
                        <div class="">
                            <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">{{ isset($data) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                </form>
            @elseif ($type == 'gate')
                <form action="{{ isset($data) ? route('update_gate') : route('store_gate') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="gate">Gate name<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="gate" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('gate',$data->name ?? '') }}">
                            @error('gate')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="flex flex-col px-10">
                            <label for="gate_branch">Branch<span class="text-rose-600">*</span> :</label>
                            <Select name="branch" id="gate_branch" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                                <option value="">Choose Branch</option>
                                @foreach ($branch as $item)
                                    <option value="{{ $item->branch_code }}" {{ isset($data) ? ($data->branch == $item->branch_code ? 'selected' : '') : (old('branch') == $item->branch_code ? 'selected' : '')  }}>{{ $item->branch_name }}</option>
                                @endforeach
                            </Select>
                            @error('branch')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>
                    </div>
                    <input type="hidden" name="{{ isset($data) ? 'id' : '' }}" value="{{ isset($data) ? $data->id : '' }}">
                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="">
                        </div>
                        <div class="">
                            <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">{{ isset($data) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>

                </form>
            @elseif ('car_type')
                <form action="{{ isset($data) ? route('update_car_type') : route('store_car_type') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-5 my-5">
                        <div class="flex flex-col px-10">
                            <label for="car_type">Car Type<span class="text-rose-600">*</span> :</label>
                            <input type="text" name="car_type" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" value="{{ old('car_type',$data->truck_name ?? '') }}">
                            @error('car_type')
                                <small class="text-rose-500 ms-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="">
                            <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">{{ isset($data) ? 'Update' : 'Save' }}</button>
                        </div>
                    </div>
                    <input type="hidden" name="{{ isset($data) ? 'id' : '' }}" value="{{ isset($data) ? $data->id : '' }}">

                </form>

            @endif
        </fieldset>
    </div>
        <script>
            $(document).ready(function() {
                new TomSelect("#branch",{
                    selectOnTab	: true
                });
            });
        </script>
@endsection
