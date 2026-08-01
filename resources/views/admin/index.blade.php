<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library</title>
    <!-- Adding simple Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <h1 class="text-3xl font-bold mb-8 text-center">Our Digital Library</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        <!-- Loop through each book -->
        @foreach($books as $book)
            <div class="bg-white p-5 rounded-lg shadow-md">
                
                <!-- Display Cover Image -->
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="w-full h-64 object-cover rounded mb-4">
                @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded mb-4">No Cover</div>
                @endif

                <!-- Display Details -->
                <h2 class="text-xl font-bold">{{ $book->title }}</h2>
                <p class="text-gray-600 mb-2">By: {{ $book->author }}</p>
                <p class="text-sm text-gray-500 mb-4">{{ $book->description }}</p>

                <!-- Download/View Button -->
                @if($book->pdf_path)
                    <a href="{{ asset('storage/' . $book->pdf_path) }}" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 block text-center">
                        Read PDF
                    </a>
                @endif

            </div>
        @endforeach
    </div>

</body>
</html>