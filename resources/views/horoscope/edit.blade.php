<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Horoscope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Edit Horoscope</h1>
    <form action="{{ route('horoscopes.update', $horoscope->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="text_ru" class="form-label">Text RU</label>
            <textarea class="form-control" id="text_ru" name="text_ru" rows="3" required>{{ $horoscope->text_ru }}</textarea>
        </div>
        <div class="mb-3">
            <label for="text_en" class="form-label">Text EN</label>
            <textarea class="form-control" id="text_en" name="text_en" rows="3" required>{{ $horoscope->text_en }}</textarea>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{ $horoscope->active ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
</body>
</html>
