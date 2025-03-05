<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Подключаем Tailwind CSS -->
</head>
<body class="bg-gray-100">
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6">Create Post</h1>

        <!-- Сообщение об успехе -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Форма -->
        <form action="{{ route('horoscopes.store') }}" method="POST">
            @csrf <!-- Добавьте эту строку -->

            <!-- Поле для русского текста -->
            <div class="mb-4">
                <label for="text_ru" class="block text-sm font-medium text-gray-700">Text (Russian):</label>
                <textarea
                    name="text_ru"
                    id="text_ru"
                    rows="4"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Введите текст на русском"
                ></textarea>
            </div>

            <!-- Поле для английского текста -->
            <div class="mb-6">
                <label for="text_en" class="block text-sm font-medium text-gray-700">Text (English):</label>
                <textarea
                    name="text_en"
                    id="text_en"
                    rows="4"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter text in English"
                ></textarea>
            </div>

            <!-- Кнопка отправки -->
            <div class="flex justify-center">
                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
