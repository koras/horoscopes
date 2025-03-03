<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horoscopes List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <td>{{ $horoscope->active ? 'Yes' : 'No' }}</td>
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
</body>
</html>
