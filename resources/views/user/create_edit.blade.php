@extends('layout.layout')

@section('content')
<div class="px-20 mt-20">
    @if (session('fails'))
    <div class="my-4 bg-rose-200 h-10 font-medium text-lg ps-5 pt-1 rounded-lg text-red-600" style="width:99%">
        {{ session('fails') }}
    </div>
    @endif
        <fieldset class="mt-3 border border-slate-500 rounded-md p-5">
            <legend class="px-4 text-2xl font-serif"> Create User </legend>

            <form action="{{ route('store_user') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-5 my-5">
                    <div class="flex flex-col px-10">
                        <label for="name">Name<span class="text-rose-600">*</span> :</label>
                        <input type="text" name="name" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('name')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="flex flex-col px-10">
                        <label for="employee_code">Employee Code<span class="text-rose-600">*</span> :</label>
                        <input type="text" name="employee_code" id="employee_code" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none">
                        @error('employee_code')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5 my-5">
                    <div class="flex flex-col px-10">
                        <label for="password">Password<span class="text-rose-600">*</span> :</label>
                        <input type="password" name="password" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" autocomplete="off">
                        @error('password')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="flex flex-col px-10">
                        <label for="confirm">Confirm Password<span class="text-rose-600">*</span> :</label>
                        <input type="confirm" name="confirm" class="mt-3 border-2 border-slate-600 rounded-lg ps-5 py-2 focus:border-b-4 focus:outline-none" autocomplete="off">
                        @error('confirm')
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
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </Select>
                            @error('status')
                            <small class="text-rose-500 ms-1">{{ $message }}</small>
                        @enderror
                        </div>

                </div>

                <div class="grid grid-cols-2 gap-5 my-5">
                    <div class="flex flex-col px-10">
                        <label for="branch">Branch<span class="text-rose-600">*</span> :</label>
                        <Select name="branch" class="h-10 rounded-t-lg mt-3 px-3 shadow-md focus:outline-none focus:border-0 focus:ring-2 focus:ring-offset-2" style="appearance: none;">
                            <option value="">Choose Branch</option>
                            @foreach ($branch as $item)
                                <option value="{{ $item->id }}">{{ $item->branch_name }}</option>
                            @endforeach
                        </Select>
                        @error('branch')
                        <small class="text-rose-500 ms-1">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="">
                        <button type="submit" class="bg-emerald-400 text-white px-10 py-2 rounded-md float-end mt-7 mr-10">Save</button>
                    </div>
                </div>

            </form>
        </fieldset>
    </div>

@endsection
