Halo {{ $member->name }},

Kami menerima request reset password untuk akun member kamu di Digimaya Academy.

Klik link di bawah ini untuk set password baru:

{{ $resetUrl }}

Link ini berlaku selama {{ $expiresHours }} jam. Kalau kamu tidak request reset password, abaikan email ini, password kamu tidak akan berubah.

Setelah set password baru, kamu bisa login di:

{{ $loginUrl }}


Tim Digimaya Academy
{{ config('mail.from.address') }}
