@extends("changelogs::layouts.tailwindcentral.index")

@section('content')
<header class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 header-gradient">Create Change Log</h1>
            <p class="text-gray-600 mt-2">Announce system updates to all users</p>
        </div>
        <div class="flex items-center space-x-4">
            {{-- <button id="previewBtn" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-eye mr-2"></i>Preview
            </button>
            <button id="saveDraftBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Save Draft
            </button> --}}
            <button id="publishBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>Submit
            </button>
        </div>
    </div>
</header>


<div class="mb-8 border-b border-gray-200">
    <div class="flex space-x-8">
        <button class="tab-button active px-4 py-3 text-indigo-600 font-medium">
            <i class="fas fa-info-circle mr-2"></i>Basic Info
        </button>
        <button class="tab-button px-4 py-3 text-gray-500 font-medium">
            <i class="fas fa-list mr-2"></i>Changes
        </button>
        <button class="tab-button px-4 py-3 text-gray-500 font-medium">
            <i class="fas fa-users mr-2"></i>Target Roles
        </button>
        <button class="tab-button px-4 py-3 text-gray-500 font-medium">
            <i class="fas fa-eye mr-2"></i>Preview
        </button>
    </div>
</div>



<form id="changeLogForm"  method="" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value={{Auth::id()}}>
    <!-- Basic Info Tab -->
    <div class="tab-content active" id="basic-info-tab">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Version Information -->
                <div class="form-section bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Release Information</h2>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-600">*</span></label>
                        <input name="title" type="text" id="releaseTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Enhanced Analytics & Dark Mode" required>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-600">*</span></label>
                        <textarea name="description" id="releaseDescription" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Brief description of this release..." required></textarea>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Release Type <span class="text-red-600">*</span></label>
                        <select id="releaseType" name="release_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="" selected disabled>Select release type</option>
                            @foreach ($releasetypes as $releasetype)
                                <option value="{{ $releasetype->id }}">{{ $releasetype->name }}</option>

                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Version Number</label>
                            <input type="text" name="version_number" id="versionNumber" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., 2.1.0" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Release Date <span class="text-red-600">*</span></label>
                            <input type="date" name="release_date" id="releaseDate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                    </div>
                </div>

                <!-- Priority & Status -->
                <div class="form-section bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Priority & Status</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priority Level</label>
                            <select id="priorityLevel" name="priority_level_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="" selected disabled>Select Priority Level</option>
                                @foreach ($prioritylevels as $prioritylevel)
                                    <option value="{{ $prioritylevel->id }}">{{ $prioritylevel->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="releaseStatus" name="status_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="" selected disabled>Select Status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="notifyUsers" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Send notification to all users</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Changes</span>
                            <span id="totalChanges" class="text-lg font-bold text-indigo-600">0</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">New Features</span>
                            <span id="newFeatures" class="text-lg font-bold text-purple-600">0</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Improvements</span>
                            <span id="improvements" class="text-lg font-bold text-blue-600">0</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Bug Fixes</span>
                            <span id="bugFixes" class="text-lg font-bold text-red-600">0</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Media Files</span>
                            <span id="mediaCount" class="text-lg font-bold text-green-600">0</span>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-indigo-800 mb-2">Tips</h3>
                    <ul class="text-sm text-indigo-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb mr-2 mt-0.5"></i>
                            <span>Use clear, user-friendly language</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb mr-2 mt-0.5"></i>
                            <span>Add screenshots for visual changes</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-lightbulb mr-2 mt-0.5"></i>
                            <span>Select relevant user roles carefully</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Changes Tab -->
    <div class="tab-content" id="changes-tab">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-3 space-y-6">
                <!-- Changes Lists Section -->
                <div id="changelists" class="form-section bg-white rounded-xl shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Changes List</h2>
                        <button type="button" id="addChangeBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Change
                        </button>
                    </div>

                    <div>
                        <div id="changesList" class="space-y-4">
                            <!-- Changes will be added here dynamically -->
                        </div>

                        <div id="noChanges" class="text-center py-8 text-gray-500">
                            <i class="fas fa-plus-circle text-4xl mb-4"></i>
                            <p>No changes added yet. Click "Add Change" to get started.</p>
                        </div>
                    </div>

                </div>

                {{--  --}}
                <div id="changeForm" class="bg-white rounded-xl shadow-md p-6"style="display: none; margin:0 !important;">
                    <div class="flex justify-between items-center mb-4">
                        <h2 id="changeformtitle" class="text-xl font-semibold text-gray-800 ">Add New Change</h2>
                        <button type="button" id="closechangeForm" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6">

                        <div class="space-y-4">

                            <div class="type-selector">
                                <div class="type-option bug-fixes"  data-change-type="1">
                                    <i class="fas fa-bug text-red-600 mb-2"></i>
                                    <div class="font-medium">Bug Fix</div>
                                    <div class="text-xs text-gray-500">Fixed an issue</div>
                                </div>
                                <div class="type-option improvements"  data-change-type="2">
                                    <i class="fas fa-arrow-up text-cyan-600 mb-2"></i>
                                    <div class="font-medium">Improvement</div>
                                    <div class="text-xs text-gray-500">Enhanced existing feature</div>
                                </div>
                                <div class="type-option new-feature"  data-change-type="3">
                                    <i class="fas fa-star text-purple-600 mb-2"></i>
                                    <div class="font-medium">New Feature</div>
                                    <div class="text-xs text-gray-500">Brand new functionality</div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text" id="changeTitle" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g., Analytics Dashboard" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                                <textarea id="changeDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Describe the change..." required></textarea>
                            </div>

                            <!-- Media Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Media Files (Max 5)</label>
                                <div class="drag-drop-area border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-indigo-500 transition-colors">
                                    <input type="file" id="mediaUpload" multiple accept="image/*,video/*" class="hidden">
                                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-500 mt-1">Images and videos up to 10MB each</p>
                                </div>

                                <div id="mediaPreview" class="mt-4 grid grid-cols-2 gap-2">
                                    <!-- Media previews will appear here -->
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <button type="button" id="saveChangeBtn" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Save Change
                                </button>
                                <button type="button" id="cancelChangeBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Media Guidelines</h3>
                        <ul class="text-sm text-yellow-700 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-0.5"></i>
                                <span>Maximum 5 files per change</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-0.5"></i>
                                <span>Images: JPG, PNG, GIF (max 10MB)</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-0.5"></i>
                                <span>Videos: MP4, WebM (max 10MB)</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check mr-2 mt-0.5"></i>
                                <span>Use high-quality screenshots</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Target Roles Tab -->
    <div class="tab-content" id="target-roles-tab">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="form-section bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Select Target User Roles</h2>
                    <p class="text-gray-600 mb-6">Choose which user roles should be notified about this change log. Users will only see changes relevant to their role.</p>

                    <div class="space-y-4 h-[500px] overflow-y-auto">
                        <div class="flex items-center">
                            <input type="checkbox" name="" id="role-all" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="all">
                            <label for="role-all" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-gray-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">All Users</div>
                                    <div class="text-sm text-gray-500">Notify everyone in the system</div>
                                </div>
                            </label>
                        </div>

                        @foreach ($roles as $role)
                            @php
                                // Extract all uppercase letters
                                preg_match_all('/[A-Z]/', $role->name, $matches);

                                // Join first two uppercase letters
                                $initials = strtoupper(implode('', array_slice($matches[0], 0, 2)));
                            @endphp
                        <div class="flex items-center">
                            <input type="checkbox" name="role_ids[]" id="role-{{$role->id}}" class="role-checkbox roles rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" value="{{ $role->id }}">
                            <label for="role-{{$role->id}}" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{-- <i class="fas fa-user-shield"></i> --}}
                                    {{-- <img src="{{ asset('images/user/' . $role->name . '.png') }}" alt="{{ $role->name }}" class="w-full h-full object-cover"> --}}
                                    <span class="">{{ $initials }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">{{ $role->name }}</div>
                                    {{-- <div class="text-sm text-gray-500">System administrators and managers</div> --}}
                                </div>
                            </label>
                        </div>
                        @endforeach

                        {{-- <div class="flex items-center">
                            <input type="checkbox" id="role-admin" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-admin" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Administrators</div>
                                    <div class="text-sm text-gray-500">System administrators and managers</div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="role-manager" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-manager" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Managers</div>
                                    <div class="text-sm text-gray-500">Team leads and department managers</div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="role-employee" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-employee" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Employees</div>
                                    <div class="text-sm text-gray-500">Regular staff members</div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="role-hr" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-hr" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">HR Department</div>
                                    <div class="text-sm text-gray-500">Human resources team</div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="role-finance" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-finance" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-yellow-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Finance Team</div>
                                    <div class="text-sm text-gray-500">Accounting and finance department</div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="role-sales" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-sales" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-orange-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Sales Team</div>
                                    <div class="text-sm text-gray-500">Sales representatives and support</div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="role-support" class="role-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="role-support" class="ml-3 flex items-center">
                                <div class="w-10 h-10 bg-teal-600 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Customer Support</div>
                                    <div class="text-sm text-gray-500">Help desk and customer service</div>
                                </div>
                            </label>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Selected Roles Summary</h3>
                    <div id="selectedRolesSummary" class="space-y-2">
                        <p class="text-gray-500 text-sm">No roles selected yet</p>
                    </div>
                </div> --}}

                <div class="bg-blue-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Role Selection Tips</h3>
                    <ul class="text-sm text-blue-700 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Select "All Users" for system-wide changes</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Choose specific roles for targeted updates</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                            <span>Users will only see relevant notifications</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Notification Settings</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">Email Notification</div>
                                <div class="text-sm text-gray-500">Send email to selected roles</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="emailNotification" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">In-App Notification</div>
                                <div class="text-sm text-gray-500">Show notification in system</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="inAppNotification" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-800">SMS Notification</div>
                                <div class="text-sm text-gray-500">Send SMS for critical updates</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="smsNotification" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</form>
@endsection


@section('css')

{{-- summernote css1 js1 --}}
<link href="{{ asset('vendor/pro1/changelog/assets/libs/summernote-0.9.0-dist/summernote-lite.min.css') }}" rel="stylesheet" type="text/css"/>
<style>
.note-editable h1, h2, h3, h4, h5, h6 {
    font-weight: 500;
    margin: 0.67em 0;
}
.note-editable ul {
    list-style: disc inside;
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.note-editable ol {
    list-style: decimal inside;
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}
.note-editable table {
    border-collapse: collapse;
    width: 100%;
}
.note-editable th, .note-editable td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    vertical-align: top;
}
</style>
<style>
.media-preview {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.media-preview:hover {
    transform: scale(1.02);
}

.media-preview .remove-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.media-preview:hover .remove-btn {
    opacity: 1;
}
</style>
@endsection

@section("scripts")
{{-- summernote css1 js1 --}}
<script src="{{ asset('vendor/pro1/changelog/assets/libs/summernote-0.9.0-dist/summernote-lite.min.js') }}" type="text/javascript"></script>
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

    // Global Variables
    const changes = [];
    let mediafiles = [];
    let changetypes = [];
    let currentEditIndex = -1;


    function initializeTabs() {
        const $tabButtons = $('.tab-button');
        const $tabContents = $('.tab-content');

        $tabButtons.each(function(index) {
            $(this).on('click', function() {
                // Remove active class from all tabs
                $tabButtons.removeClass('active text-indigo-600').addClass('text-gray-500');
                $tabContents.removeClass('active');

                // Add active class to clicked tab and corresponding content
                $(this).addClass('active text-indigo-600').removeClass('text-gray-500');
                $tabContents.eq(index).addClass('active');
            });
        });
    }
    initializeTabs();


    {{-- Start Add Change Form Btn --}}
    $('#addChangeBtn').click(function(){
        $('#changeformtitle').text('Add New Change')
        showChangeForm();
    });
    {{-- End Add Change Form Btn --}}

    {{-- Start Close New Change --}}
    $('#closechangeForm, #cancelChangeBtn').click(function(){
        $('#changelists').css('display','block');
        $('#changeForm').css('display','none');
    })
    {{-- End Close New Change --}}


    {{-- Start Save Change Btn --}}
    $.ajax({
        url:"{{url('/api/changetypes')}}",
        meethod:"GET",
        dataType:"json",
        success:function(response){
            const datas = response;

            changetypes = datas;
        }
    });

    $('#saveChangeBtn').click(function(){

        Swal.fire({
            title: "ပြင်ဆင်မှုအသစ်တစ်ခုထည့်မည်မှာသေချာပါသလား",
            text: "ထပ်တိုးပြင်ဆင်မှုစာစောင်တစ်ခုတွင် ပြင်ဆင်မှုအသေးစိတ်များစွာထည့်၍ရပါသည်။",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ပေါင်းထည့်မည်"
        }).then((result) => {
            if (result.isConfirmed) {
                const title = $("#changeTitle").val();
                const description = $("#changeDescription").val();
                const change_type_id = getActiveType();
                const changetype = getChangeType(change_type_id);
                if (!title || !description || !change_type_id) {
                    Swal.fire({
                            icon: "error",
                            title: "Submission Failed",
                            text: "Please fill in all required fields and select a type.",
                    });
                    return;
                }


                const change = {
                    title,
                    description,
                    change_type_id,
                    changetype,
                    mediafiles,
                }


                if (currentEditIndex >= 0) {
                    changes[currentEditIndex] = change;
                } else {
                    changes.push(change);
                }

                updateChangesList();
                hideChangeForm();
                updateStats();

                console.log(changes);

            }
        });



    })

    function getActiveType() {
        const active = document.querySelector('.type-option.active');
        return active ? active.getAttribute('data-change-type') : null;
    }

    function getChangeType(change_type_id){
        return changetypes.find((changetype)=>changetype.id == change_type_id)
    }
    {{-- End Save Change Btn --}}


    {{-- Start Type Option --}}
    $('.type-option').click(function(){
        $('.type-option').removeClass('active');
        $(this).addClass('active')
    });
    {{-- End Type Option --}}


    {{-- Start Media Upload --}}
    $(document).on('click','.drag-drop-area',function(){
        document.getElementById('mediaUpload').click();
    })

    // Media upload
    $('#mediaUpload').change(handleMediaUpload);

    // Handle media upload
    function handleMediaUpload(e) {
        const files = Array.from(e.target.files);
        handleMediaFiles(files);
    }
    function handleMediaFiles(files) {
        if (mediafiles.length + files.length > 5) {
            alert('Maximum 5 media files allowed per change');
            return;
        }

        files.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                alert(`File ${file.name} is too large. Maximum size is 10MB`);
                return;
            }
            mediafiles.push(file);
        });
        updateMediaPreview();

        console.log(mediafiles);
    }

    // Update media preview
    function updateMediaPreview() {
        const preview = document.getElementById('mediaPreview');
        preview.innerHTML = '';

        mediafiles.forEach(async (file, index) => {
            const reader = new FileReader();

            reader.onload =  function(e) {
                const fileURL = e.target.result;

                const div = document.createElement('div');
                div.className = 'media-preview relative';

                if (file.type.startsWith('image/')) {
                    div.innerHTML = `
                        <a href="${fileURL}" data-fancybox="change-changelogfile" data-caption="Change Media"><img src="${fileURL}" alt="${file.name}" class="w-full h-20 object-cover rounded"></a>
                        <button type="button" class="remove-btn" data-index="${index}">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                } else if (file.type.startsWith('video/')) {
                    div.innerHTML = `
                        <a href="${fileURL}" data-fancybox="change-changelogfile" data-caption="Change Media"><video src="${fileURL}" class="w-full h-20 object-cover rounded" controls  data-index="${index}"></video></a>
                        <button type="button" class="remove-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                }

                preview.appendChild(div);
            };

            await reader.readAsDataURL(file);
        });
    }

    // Remove media file
    $('#mediaPreview').on('click', '.remove-btn', function () {
        const index = $(this).data('index');
        mediafiles.splice(index, 1);
        updateMediaPreview();
    });
    {{-- End Media Upload --}}


    function updateChangesList(){
        $('#changesList').html('');

        let html = '';
        changes.forEach((change,idx)=>{
            html += `
            <div class="change-item ${change.changetype.slug} p-4 bg-gray-50 rounded-xl shadow-sm hover:shadow-md transition-all">
                <div class="flex items-start">
                    <span class="flex-none ${change.change_type_id == 1 ? 'badge-bugfix' : (change.change_type_id == 2 ? 'badge-improvement' : ( change.change_type_id == 3 ? 'badge-feature' : '' )) }  text-white text-xs font-semibold px-3 py-1.5 rounded-lg mr-3 mt-1 flex items-center">
                        <i class="fas ${change.change_type_id == 1 ? 'fa-bug' : (change.change_type_id == 2 ? 'fa-arrow-up' : ( change.change_type_id == 3 ? 'fa-star' : '' )) } mr-1"></i> ${change.changetype.name}
                    </span>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-lg">${change.title}</h3>
                        <p class="text-gray-600 mt-1">${ change.description }</p>

                    </div>
                </div>
                <div class="flex space-x-2 justify-end">
                    <button type="button" class="edit-change-btn text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="delete-change-btn text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="change-media grid grid-cols-4 gap-2">
                      ${change.mediafiles.map(file => {
                        const url = URL.createObjectURL(file);
                        if (file.type.startsWith('image/')) {
                            return `<a href="${url}" data-fancybox="change${idx}-changelogfile" data-caption="Change ${idx+1} Media"><img src="${url}" alt="${file.name}" class="w-full h-20 object-cover rounded" /></a>`;
                        } else if (file.type.startsWith('video/')) {
                            return `<a href="${url}" data-fancybox="change${idx}-changelogfile" data-caption="Change ${idx+1} Media"><video src="${url}" class="w-full h-20 object-cover rounded" controls></video></a>`;
                        }
                        return '';
                    }).join('')}
                </div>
            </div>
            `
        });
        $('#changesList').html(html);

    }

    // Show change form
    function showChangeForm(){
        $('#changeForm').css('display','block');
        $('#changelists').css('display','none');
        currentEditIndex = -1;
        clearChangeForm();
    }

    // Hide change form
    function hideChangeForm(){

        $('#changelists').css('display','block');
        $('#changeForm').css('display','none');

        clearChangeForm();

        if (changes.length === 0) {
            $('#noChanges').css('display','block');
        }else{
            $('#noChanges').css('display','none');
        }
    }

    // Clear Change Form
    function clearChangeForm(){
        $("#changeTitle").val('');
        $("#changeDescription").val('');
        $('#changeDescription').summernote('code', '');
        $(".type-option").removeClass('active');
        mediafiles = [];
        updateMediaPreview();
    }


    {{-- Start Public Btn --}}
    $('#publishBtn').click(function(){
        const formElement = document.getElementById('changeLogForm');
        const formData = new FormData(formElement);

        changes.forEach((change, i) => {
            formData.append(`changes[${i}][title]`, change.title);
            formData.append(`changes[${i}][description]`, change.description);
            formData.append(`changes[${i}][change_type_id]`, change.change_type_id);

            change.mediafiles.forEach((file, j) => {
            formData.append(`changes[${i}][mediafiles][${j}]`, file);
            });
        });

        fetch('/api/changelogs', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if(data.errors){
                errorMessage = Object.values(data.errors)
                            .flat()
                            .map((msg, i) => `${i + 1}. ${msg}`)
                            .join("<br>");
            }else{
                console.log('Success:', data);
                window.location.href = '{{ route("changelogs.index") }}';
            }

            Swal.fire({
                    icon: "error",
                    title: "Submission Failed",
                    html: errorMessage,
            });

        })
        .catch(err => {


            console.error('Error:', err);
        });
    });
    {{-- End Public Btn --}}

    {{-- Start Edit Change Btn --}}
    $(document).on('click','.edit-change-btn',function(){
        $('#changeformtitle').text('Edit Change')


        const index = $('.edit-change-btn').index(this);

        const change = changes[index];
        currentEditIndex = index;

        $("#changeTitle").val(change.title);
        {{-- $("#changeDescription").val(change.description); --}}
        if(change.description){
            $('#changeDescription').summernote('code', change.description);
        }
        mediafiles = [...change.mediafiles];
        updateMediaPreview();

        $('#changeForm').css('display','block');
        $('#changelists').css('display','none');

        $('.type-option').removeClass('active');
        $(`.type-option.${change.changetype.slug}`).addClass('active');

    });
    {{-- End Edit Change Btn --}}

    {{-- Start Delete Change Btn --}}

    $(document).on('click','.delete-change-btn',function(){

        const index = $('.delete-change-btn').index(this);
        currentEditIndex = index;

        Swal.fire({
            title: "ပြင်ဆင်မှုအသစ်တစ်ခုဖျက်မှာသေချာပါသလား",
            text: "ထပ်တိုးပြင်ဆင်မှုစာစောင်တစ်ခုတွင် ယခုပြင်ဆင်မှုပါဝင်တော့မည်မဟုတ်ပါ",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "ဖျက်မည်"
        }).then((result) => {
            if (result.isConfirmed) {
                changes.splice(index, 1);
                updateChangesList();
                updateStats();
                updateMediaPreview();

                if (changes.length === 0) {
                    document.getElementById('noChanges').style.display = 'block';
                }
            }
        });
    });
    {{-- console.log(mediafiles); --}}


    function updateStats() {
        const total = changes.length;
        const bugfixes = changes.filter(c => c.changetype.id === 1).length;
        const improvements = changes.filter(c => c.changetype.id === 2).length;
        const features = changes.filter(c => c.changetype.id === 3).length;
        const mediafiles = changes.reduce((sum, c) => sum + (c.mediafiles ? c.mediafiles.length : 0), 0);

        document.getElementById('totalChanges').textContent = total;
        document.getElementById('newFeatures').textContent = features;
        document.getElementById('improvements').textContent = improvements;
        document.getElementById('bugFixes').textContent = bugfixes;
        document.getElementById('mediaCount').textContent = mediafiles;
    }

    // All users checkbox
    $('#role-all').change(function () {

        if ($(this).is(':checked')) {
            $('.role-checkbox').each(function () {
                    this.checked = true;
            });
        }else{
            $('.role-checkbox').each(function () {
                    this.checked = false;
            });
        }
    });


    $('.role-checkbox.roles').change(function(){
        $('#role-all').prop('checked', false);
    });



    // Start text editor for content
    $('#changeDescription').summernote({
        placeholder: 'Say Something....',
        tabsize: 2,
        height: 120,
        {{-- toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
        ] --}}
    });
    // End text editor for content
})


</script>
@endsection
