<h2>Permintaan Reset Password</h2>

@if(session('new_password'))
    <div style="background: #d4edda; padding: 10px; margin-bottom: 15px;">
        {{ session('new_password') }}

        @if(isset($req) && $req->user->whatsapp)
            <br><br>
            <a 
                href="https://wa.me/{{ $req->user->whatsapp }}?text=Password%20baru%20Anda:%20{{ urlencode(session('new_password')) }}"
                target="_blank"
                style="color: green; font-weight: bold;"
            >
                Kirim via WhatsApp
            </a>
        @endif
    </div>
@endif

<table>
    <tr>
        <th>Email</th>
        <th>Nomor WA</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    @foreach ($requests as $req)
    <tr>
        <td>{{ $req->user->email }}</td>

        <td>
            @if($req->user->whatsapp)
                <a 
                    href="https://wa.me/{{ $req->user->whatsapp }}" 
                    target="_blank"
                    style="color: blue; text-decoration: underline;"
                >
                    {{ $req->user->whatsapp }}
                </a>
            @else
                -
            @endif
        </td>

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
