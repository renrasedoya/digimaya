Halo {{ $member->name }},

Akun member kamu di Digimaya Academy sudah dibuat oleh tim Digimaya.

Untuk mulai akses materi, silakan set password kamu lewat link di bawah ini:

{{ $setupUrl }}

Link ini berlaku selama {{ $expiresHours }} jam. Kalau sudah expired, hubungi tim Digimaya untuk minta link baru.

Setelah set password, kamu bisa login kapan saja di:

{{ $loginUrl }}

Selamat belajar.


Tim Digimaya Academy
{{ config('mail.from.address') }}
