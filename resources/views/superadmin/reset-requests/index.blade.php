<h2>Permintaan Reset Password</h2>

@if(session('new_password'))
    <div class="alert alert-success">
        {{ session('new_password') }}
    </div>
@endif

<table>
    <tr>
        <th>Email</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach ($requests as $req)
    <tr>
        <td>{{ $req->user->email }}</td>
        <td>{{ $req->status }}</td>
        <td>
            @if($req->status == 'pending')
            <form method="POST" action="{{ route('superadmin.reset.perform', $req->id) }}">
                @csrf
                <button type="submit">Reset Password</button>
            </form>
            @else
            Selesai
            @endif
        </td>
    </tr>
    @endforeach
</table>
