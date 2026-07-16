#!/bin/bash
# Deploy file ke digimaya.com lewat FTPS.
#
#   ./deploy.sh                  -> kirim file yang berubah sejak commit terakhir
#   ./deploy.sh path/ke/file.php -> kirim file tertentu (relatif ke digimaya_app/)
#
# Kredensial dibaca dari ~/.digimaya-ftp, isinya SATU baris: username:password
# File itu tidak pernah masuk repo. Buat dengan:
#   printf '%s' 'user@digimaya.com:PASSWORD' > ~/.digimaya-ftp && chmod 600 ~/.digimaya-ftp
#
# Perhatikan '%s' di atas — WAJIB. Tanpa itu printf menelan tanda % di dalam
# password ("%J: invalid directive") dan menulis file yang rusak.
#
# CATATAN PENTING soal host: sertifikat TLS server diterbitkan untuk
# alezio.id.rapidplex.com, BUKAN ftp.renrasedoya.com. Memakai nama yang salah
# bikin verifikasi TLS gagal. Jangan diakali dengan -k — itu mengirim password
# tanpa perlindungan. Pakai nama di bawah ini.

set -euo pipefail

HOST='ftp://alezio.id.rapidplex.com'
CREDFILE="$HOME/.digimaya-ftp"
APPDIR="digimaya_app"
REMOTE_ROOT="digimaya_app"

cd "$(dirname "$0")"

[ -f "$CREDFILE" ] || { echo "ERROR: $CREDFILE tidak ada. Lihat komentar di atas."; exit 1; }
CRED="$(tr -d '\n' < "$CREDFILE")"

# Tentukan daftar file
if [ $# -gt 0 ]; then
    FILES=("$@")
else
    mapfile -t FILES < <(git diff --name-only HEAD~1 HEAD -- "$APPDIR" | sed "s|^$APPDIR/||")
    [ ${#FILES[@]} -eq 0 ] && { echo "Tidak ada file berubah di commit terakhir."; exit 0; }
fi

echo "Akan mengirim ${#FILES[@]} file ke digimaya.com:"
printf '  %s\n' "${FILES[@]}"
echo

fail=0
for f in "${FILES[@]}"; do
    local_path="$APPDIR/$f"
    [ -f "$local_path" ] || { echo "LEWAT  : $f (tidak ada di lokal)"; continue; }

    # Percobaan sampai 3x: server kadang menolak dengan 451 secara acak dan
    # meninggalkan file 0 byte. Ukuran diverifikasi setelah tiap percobaan.
    want=$(wc -c < "$local_path" | tr -d ' ')
    sent=0
    for attempt in 1 2 3; do
        if curl -s --ssl-reqd --max-time 90 -u "$CRED" -T "$local_path" "$HOST/$REMOTE_ROOT/$f" 2>/dev/null; then
            got=$(curl -s --ssl-reqd --max-time 30 -u "$CRED" "$HOST/$REMOTE_ROOT/$(dirname "$f")/" 2>/dev/null \
                  | awk -v n="$(basename "$f")" '$NF == n {print $5}')
            if [ "$got" = "$want" ]; then
                echo "OK     : $f ($want byte)"
                sent=1
                break
            fi
            echo "  ulang: $f (server melaporkan '${got:-kosong}', harusnya $want)"
        fi
    done
    [ $sent -eq 1 ] || { echo "GAGAL  : $f — PERIKSA SITUS, file mungkin tinggal 0 byte"; fail=1; }
done

echo
[ $fail -eq 0 ] && echo "Selesai. Semua file terkirim dan ukurannya cocok." \
               || echo "ADA YANG GAGAL — cek di atas. Situs bisa dalam kondisi separuh ter-update."
exit $fail
