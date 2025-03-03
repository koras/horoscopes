<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horoscopes List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Подключите jQuery -->
</head>
<body>
<div class="container mt-5">
    <h1>Horoscopes List</h1>
    <a href="{{ route('horoscopes.create') }}" class="btn btn-success mb-3">Add New Horoscope</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Text RU</th>
            <th>Text EN</th>
            <th>Using</th>
            <th>Active</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($horoscopes as $horoscope)
            <tr>
                <td>{{ $horoscope->id }}</td>
                <td>{{ $horoscope->text_ru }}</td>
                <td>{{ $horoscope->text_en }}</td>
                <td>{{ $horoscope->using }}</td>
                <td>
                    <input type="checkbox" class="active-toggle" data-id="{{ $horoscope->id }}" {{ $horoscope->active ? 'checked' : '' }}>
                    <span class="active-status">{{ $horoscope->active ? 'Yes' : 'No' }}</span>
                </td>
                <td>{{ $horoscope->created_at }}</td>
                <td>{{ $horoscope->updated_at }}</td>
                <td>
                    <a href="{{ route('horoscopes.edit', $horoscope->id) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('horoscopes.destroy', $horoscope->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Пагинация -->
    {{ $horoscopes->links() }}
</div>

<script>
    $(document).ready(function () {
        // Обработка изменения состояния чекбокса
        $('.active-toggle').on('change', function () {
            const checkbox = $(this);
            const horoscopeId = checkbox.data('id');
            const isActive = checkbox.is(':checked');

            // Отправка AJAX-запроса
            $.ajax({
                url: `/horoscopes/${horoscopeId}/toggle-active`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // CSRF-токен
                },
                success: function (response) {
                    if (response.success) {
                        // Обновление текста статуса
                        checkbox.closest('td').find('.active-status').text(response.active ? 'Yes' : 'No');
                    } else {
                        alert('Ошибка при обновлении статуса.');
                    }
                },
                error: function () {
                    alert('Ошибка при отправке запроса.');
                    checkbox.prop('checked', !isActive); // Возвращаем чекбокс в исходное состояние
                }
            });
        });
    });
</script>
</body>
</html>
