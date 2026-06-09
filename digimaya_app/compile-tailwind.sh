#!/bin/bash
cd /home/digimay1/digimaya_app
./tailwindcss-linux-x64 -i resources/css/app.css -o public/css/tailwind.css --minify
echo "✅ Tailwind compiled at $(date '+%Y-%m-%d %H:%M:%S')"
ls -la public/css/tailwind.css
