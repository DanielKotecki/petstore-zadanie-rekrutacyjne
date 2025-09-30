<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Store - {{ isset($pet) ? 'Edit Pet' : 'Add Pet' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">{{ isset($pet) ? 'Edit Pet' : 'Add New Pet' }}</h1>

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

    <form action="{{ isset($pet) && is_object($pet) ? route('pets.update', $pet->id) : route('pets.store') }}"
          method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        @if (isset($pet) && is_object($pet))
            @method('PUT')
        @endif

        @if(isset($pet) && is_object($pet))
            <div class="mb-4">
                <label for="id" class="block text-gray-700">Pet ID (Required)</label>
                <input type="number" name="id" id="id" value="{{ old('id', $pet->id) }}"
                       class="w-full border rounded p-2" required>
            </div>
        @endif

        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name (Required)</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', isset($pet) && is_object($pet) ? $pet->name : '') }}"
                   class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-gray-700">Status (Required)</label>
            <select name="status" id="status" class="w-full border rounded p-2" required>
                <option
                    value="available" {{ old('status', isset($pet) && is_object($pet) ? $pet->status : 'available') == 'available' ? 'selected' : '' }}>
                    Available
                </option>
                <option
                    value="pending" {{ old('status', isset($pet) && is_object($pet) ? $pet->status : 'available') == 'pending' ? 'selected' : '' }}>
                    Pending
                </option>
                <option
                    value="sold" {{ old('status', isset($pet) && is_object($pet) ? $pet->status : 'available') == 'sold' ? 'selected' : '' }}>
                    Sold
                </option>
            </select>
        </div>

        <div class="mb-4">
            <label for="category_id" class="block text-gray-700">Category ID (Optional)</label>
            <input type="number" name="category[id]" min="1" id="category_id"
                   value="{{ old('category.id', isset($pet) && is_object($pet) ? $pet->category?->id : '') }}"
                   class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label for="category_name" class="block text-gray-700">Category Name (Optional)</label>
            <input type="text" name="category[name]" id="category_name"
                   value="{{ old('category.name', isset($pet) && is_object($pet) ? $pet->category?->name : '') }}"
                   class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Photo URLs (Required, at least one)</label>
            <div id="photoUrls-container">
                @if (isset($pet) && is_object($pet) && !empty($pet->photoUrls))
                    @foreach ($pet->photoUrls as $index => $url)
                        <div class="photoUrl-input flex mb-2">
                            <input type="url" name="photoUrls[]" value="{{ old('photoUrls.'.$index, $url) }}"
                                   class="w-full border rounded p-2" placeholder="Enter Photo URL" required>
                            @if($index !== 0)
                                <button type="button" onclick="removePhotoUrlInput(this)"
                                        class="ml-2 text-red-500 hover:underline">Remove
                                </button>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="photoUrl-input flex mb-2">
                        <input type="url" name="photoUrls[]" value="{{ old('photoUrls.0') }}"
                               class="w-full border rounded p-2" placeholder="Enter Photo URL" required>

                    </div>
                @endif
            </div>
            <button type="button" onclick="addPhotoUrlInput()" class="text-blue-500 hover:underline">Add Photo URL
            </button>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Tags (Optional)</label>
            <div id="tags-container">
                @if (isset($pet) && is_object($pet) && !empty($pet->tags))
                    @foreach ($pet->tags as $index => $tag)
                        <div class="tag-input flex mb-2">
                            <input type="number" name="tags[{{$index}}][id]"
                                   value="{{ old('tags.'.$index.'.id', $tag->id ?? '') }}"
                                   class="w-1/4 border rounded p-2 mr-2" placeholder="Tag ID">
                            <input type="text" name="tags[{{$index}}][name]"
                                   value="{{ old('tags.'.$index.'.name', $tag->name ?? '') }}"
                                   class="w-3/4 border rounded p-2" placeholder="Tag Name">
                            <button type="button" onclick="removeTagInput(this)"
                                    class="ml-2 text-red-500 hover:underline">Remove
                            </button>

                        </div>
                    @endforeach
                    @php
                        $nextIndex = count($pet->tags);
                    @endphp
                @else
                    @php
                        $nextIndex = 0;
                    @endphp
                @endif
            </div>
            <button type="button" onclick="addTagInput()" class="text-blue-500 hover:underline">Add Tag</button>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('pets.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</a>
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">{{ isset($pet) ? 'Update Pet' : 'Add Pet' }}</button>
        </div>
    </form>
</div>

<script>
    function addPhotoUrlInput() {
        const container = document.getElementById('photoUrls-container');
        const div = document.createElement('div');
        div.className = 'photoUrl-input flex mb-2';
        div.innerHTML = `
            <input type="url" name="photoUrls[]" class="w-full border rounded p-2" placeholder="Enter Photo URL" required>
            <button type="button" onclick="removePhotoUrlInput(this)" class="ml-2 text-red-500 hover:underline">Remove</button>
        `;
        container.appendChild(div);
    }

    function removePhotoUrlInput(button) {
        const container = document.getElementById('photoUrls-container');
        if (container.children.length > 1) {
            button.parentElement.remove();
        }
    }

    function getNextUniqueTagId() {
        const tagIdInputs = document.querySelectorAll('[name^="tags["][name$="][id]"]');
        let maxId = 0;

        tagIdInputs.forEach(input => {
            const currentId = parseInt(input.value, 10);
            if (!isNaN(currentId) && currentId > maxId) {
                maxId = currentId;
            }
        });

        return maxId + 1;
    }

    function addTagInput() {
        const container = document.getElementById('tags-container');
        const arrayIndex = window.nextTagIndex;
        const nextIdValue = getNextUniqueTagId();

        const div = document.createElement('div');
        div.className = 'tag-input flex mb-2';
        div.innerHTML = `
            <input type="number" name="tags[${arrayIndex}][id]" value="${nextIdValue}" class="w-1/4 border rounded p-2 mr-2" placeholder="Tag ID">
            <input type="text" name="tags[${arrayIndex}][name]" value="" class="w-3/4 border rounded p-2" placeholder="Tag Name">
            <button type="button" onclick="removeTagInput(this)" class="ml-2 text-red-500 hover:underline">Remove</button>
        `;
        container.appendChild(div);

        window.nextTagIndex++;
    }

    function removeTagInput(button) {
        button.parentElement.remove();
    }

    window.nextTagIndex = {{ $nextIndex }};
</script>
</body>
</html>
