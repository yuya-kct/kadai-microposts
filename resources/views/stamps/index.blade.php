<!-- resources/views/stamps/index.blade.php -->
<h1>スタンプ一覧</h1>

@foreach ($stamps as $stamp)
    <div>
        <img src="{{ asset($stamp->image_path) }}" alt="{{ $stamp->name }}" width="50">
        <p>{{ $stamp->name }}</p>
    </div>
@endforeach