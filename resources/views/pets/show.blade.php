<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Details - {{ $pet->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Szczegóły Peta: {{ $pet->name }}</h1>
        <a href="{{ route('pets.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Powrót do listy
        </a>
    </div>

    <div class="bg-white p-6 rounded shadow-lg">

        <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Podstawowe Dane</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-700 font-medium">ID Peta:</p>
                <p class="text-xl font-bold">{{ $pet->id ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-700 font-medium">Status:</p>
                <p class="text-xl font-bold uppercase text-{{ $pet->status === 'available' ? 'green' : ($pet->status === 'pending' ? 'yellow' : 'red') }}-600">
                    {{ $pet->status ?? 'N/A' }}
                </p>
            </div>
        </div>

        <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Kategoria</h2>
        <div class="mb-6">
            @if ($pet->category)
                <p class="text-gray-700 font-medium">Nazwa Kategorii:</p>
                <p class="text-lg">{{ $pet->category->name ?? 'Brak nazwy' }}</p>
                <p class="text-gray-700 font-medium mt-2">ID Kategorii:</p>
                <p class="text-lg">{{ $pet->category->id ?? 'N/A' }}</p>
            @else
                <p class="text-lg text-gray-500">Brak przypisanej kategorii.</p>
            @endif
        </div>

        <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Zdjęcia ({{ count($pet->photoUrls) }})</h2>
        @if (!empty($pet->photoUrls))
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                @foreach ($pet->photoUrls as $url)
                    <div class="border rounded p-2 bg-gray-50 flex flex-col">
                        <img src="{{ $url }}" alt="Pet Photo" class="w-full h-32 object-cover rounded mb-2 border border-gray-300">
                        <a href="{{ $url }}" target="_blank" class="text-blue-500 text-sm truncate hover:underline" title="{{ $url }}">{{ $url }}</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-lg text-gray-500 mb-6">Brak zdjęć.</p>
        @endif

        <h2 class="text-2xl font-semibold mb-4 border-b pb-2">Tagi ({{ count($pet->tags ?? []) }})</h2>
        @if (!empty($pet->tags))
            <div class="flex flex-wrap gap-2">
                @foreach ($pet->tags as $tag)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                        {{ $tag->name ?? 'N/A' }} (ID: {{ $tag->id ?? 'N/A' }})
                    </span>
                @endforeach
            </div>
        @else
            <p class="text-lg text-gray-500">Brak przypisanych tagów.</p>
        @endif

        <div class="mt-8 flex justify-end">
            <a href="{{ route('pets.edit', $pet->id) }}" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">Edytuj Peta</a>
        </div>
    </div>
</div>

</body>
</html>
