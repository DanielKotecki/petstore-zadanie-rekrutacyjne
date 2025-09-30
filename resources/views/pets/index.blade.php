<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Store - List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Pet Store</h1>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4 flex justify-between">
       <div>
           <a href="{{ route('pets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Dodaj nowego Peta</a>
       </div>
        <div class="">
            <a href="{{ route('pets.index',['status'=>'available']) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Availavble</a>
            <a href="{{ route('pets.index',['status'=>'pending']) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Pending</a>
            <a href="{{ route('pets.index',['status'=>'sold']) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Sold</a>
        </div>

    </div>

    <table class="w-full bg-white shadow-md rounded">
        <thead>
        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
            <th class="py-3 px-6 text-left">ID</th>
            <th class="py-3 px-6 text-left">Name</th>
            <th class="py-3 px-6 text-left">Category</th>
            <th class="py-3 px-6 text-left">Status</th>
            <th class="py-3 px-6 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="text-gray-600 text-sm">
        @forelse ($pets??[] as $pet)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6">{{ $pet['id'] }}</td>
                <td class="py-3 px-6">{{ $pet['name']??'' }}</td>
                <td class="py-3 px-6">{{ $pet['category']['name'] ?? '[BRAK]' }}</td>
                <td class="py-3 px-6">{{ $pet['status'] }}</td>
                <td class="py-3 px-6 text-center">
                    <a href="{{ route('pets.show', ['pet' => $pet['id']]) }}" class="text-green-500 hover:underline">Pokaż</a>
                    <a href="{{ route('pets.upload.show', ['id' => $pet['id']]) }}" class="text-pink-500 hover:underline">Upload Image</a>
                    <a href="{{ route('pets.edit', $pet['id']) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('pets.destroy', $pet['id']) }}" method="POST" class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this pet?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-3 px-6 text-center">Brak zwierzaków.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
