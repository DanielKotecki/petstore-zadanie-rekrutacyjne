<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image for Pet #{{ $pet->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Upload Image for Pet #{{ $pet->id }}</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- Ważne: 'enctype="multipart/form-data"' jest niezbędne do przesyłania plików --}}
    <form action="{{ route('pets.upload') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded shadow-md">
        @csrf
        <input name="petId" value="{{$pet->id}}" type="number" hidden="hidden"/>
        <div class="mb-4">
            <label for="additionalMetadata" class="block text-gray-700">Additional Metadata (Opcjonalnie)</label>
            <input type="text" name="additionalMetadata" id="additionalMetadata" value="{{ old('additionalMetadata') }}"
                   class="w-full border rounded p-2" placeholder="Wprowadź dodatkowe metadane">
        </div>

        <div class="mb-4">
            <label for="file" class="block text-gray-700">Image File (Wymagany)</label>
            <input type="file" name="file" id="file" class="w-full border rounded p-2"
                   accept="image/jpeg,image/png,image/gif">
            <p class="text-xs text-gray-500 mt-1">Dozwolone formaty: JPG, PNG, GIF. Max 5MB.</p>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('pets.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Anuluj</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Prześlij Zdjęcie
            </button>
        </div>
    </form>
</div>
</body>
</html>
