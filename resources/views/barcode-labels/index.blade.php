<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Barcode Templates</title>

    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-100">

    <div class="max-w-6xl mx-auto p-6">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Barcode Templates</h1>

            <a href="{{ route('barcode-labels.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Create New Template
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">

            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Template Name</th>
                        <th class="p-3">Width</th>
                        <th class="p-3">Height</th>
                        <th class="p-3">Created</th>
                        <th class="p-3">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($templates as $template)
                        <tr class="border-t">
                            <td class="p-3">
                                {{ $template->name }}
                            </td>

                            <td class="p-3">
                                {{ $template->width_mm }} mm
                            </td>

                            <td class="p-3">
                                {{ $template->height_mm }} mm
                            </td>

                            <td class="p-3">
                                {{ $template->created_at->format('Y-m-d') }}
                            </td>

                            <td class="p-3">
                                <a href="{{ route('barcode-labels.edit', $template) }}" class="text-blue-600 mr-3">
                                    Edit
                                </a>

                                <form method="POST" action="{{ route('barcode-labels.destroy', $template) }}"
                                    class="inline" onsubmit="return confirm('Delete this template?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                No templates created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</body>

</html>
