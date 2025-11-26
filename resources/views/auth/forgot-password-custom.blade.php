<h2>Lupa Password</h2>

<form method="POST" action="{{ route('password.submit.custom') }}">
    @csrf
    <label>Email</label>
    <input type="email" name="email" required>

    <button type="submit">Kirim Permintaan</button>
</form>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif
