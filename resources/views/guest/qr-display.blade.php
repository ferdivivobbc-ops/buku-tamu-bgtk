<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Buku Tamu BGTK Sumatera Barat</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            background: radial-gradient(circle at top, #07518a 0, #0b3259 55%, #072442 100%);
        }
        .page { min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 24px; }
        .brand { text-align: center; }
        .eyebrow { color: #f9df7b; font-size: 15px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        h1 { margin: 8px 0; font-size: clamp(24px, 3vw, 42px); }
        .subtitle { margin: 0; color: #dbeafe; font-size: clamp(15px, 1.6vw, 20px); }
        .card {
            width: min(100%, 980px);
            margin: 24px auto 16px;
            padding: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(28px, 6vw, 72px);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 24px;
            background: rgba(255,255,255,.97);
            color: #0b3259;
            box-shadow: 0 24px 70px rgba(0,0,0,.28);
        }
        .qr-frame { flex: 0 0 auto; padding: 14px; border: 1px solid #dbe3ec; border-radius: 18px; background: #fff; }
        #qr img { display: block; width: min(46vw, 500px); height: min(46vw, 500px); image-rendering: pixelated; }
        #qr canvas { display: none; }
        .details { max-width: 360px; }
        .details h2 { margin: 0 0 12px; font-size: clamp(22px, 2.4vw, 32px); line-height: 1.2; }
        .details p { color: #475569; line-height: 1.6; }
        .url-input { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 9px; color: #0f172a; font: inherit; }
        .hint { margin: 8px 0 0; font-size: 13px; }
        .warning { color: #9a3412 !important; }
        .status { min-height: 22px; color: #b91c1c; font-size: 14px; }
        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 16px; }
        button {
            border: 0;
            border-radius: 9px;
            padding: 12px 15px;
            color: #0b3259;
            background: #fbbf24;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }
        button.secondary { color: #fff; background: #0b3259; }
        button:hover { filter: brightness(.95); }
        .instruction { max-width: 980px; color: #dbeafe; text-align: center; line-height: 1.6; font-size: 14px; }
        @media (max-width: 700px) {
            .page { padding: 18px 12px; }
            .card { flex-direction: column; padding: 18px; gap: 20px; }
            #qr img { width: min(72vw, 420px); height: min(72vw, 420px); }
            .details { width: 100%; max-width: 500px; }
        }
        @media print {
            body { background: #fff; color: #0b3259; }
            .page { min-height: auto; padding: 0; }
            .card { box-shadow: none; border: 1px solid #cbd5e1; }
            .actions, .instruction, .url-input, .hint, .status { display: none !important; }
        }
    </style>
</head>
<body>
    <main class="page">
        <header class="brand">
            <div class="eyebrow">Kementerian Pendidikan Dasar &amp; Menengah RI</div>
            <h1>Buku Tamu BGTK Sumatera Barat</h1>
            <p class="subtitle">Arahkan kamera ponsel untuk membuka formulir kunjungan</p>
        </header>

        <section class="card" aria-label="QR code pendaftaran tamu">
            <div class="qr-frame">
                <div id="qr" aria-label="QR code formulir buku tamu"></div>
            </div>
            <div class="details">
                <h2>Scan untuk mengisi buku tamu</h2>
                <label for="form-url"><strong>Alamat formulir yang dibuka QR:</strong></label>
                <input id="form-url" class="url-input" type="url" value="{{ $formUrl }}" autocomplete="url">
                <p class="hint warning">Pastikan alamat ini dapat dibuka dari ponsel. Alamat localhost atau 127.0.0.1 hanya berlaku di perangkat server.</p>
                <p id="status" class="status" role="status" aria-live="polite"></p>
                <div class="actions">
                    <button id="generate" type="button">Perbarui QR</button>
                    <button id="download-png" type="button" class="secondary">Unduh PNG</button>
                    <button id="download-html" type="button" class="secondary">Unduh HTML</button>
                    <button id="fullscreen" type="button" class="secondary">Layar penuh</button>
                </div>
            </div>
        </section>

        <p class="instruction">
            Untuk TV melalui flashdisk, unduh PNG lalu salin ke flashdisk dan buka melalui pemutar foto TV.
            Berkas HTML juga tersedia, tetapi tidak semua TV dapat membuka HTML dari USB.
        </p>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const qrElement = document.getElementById('qr');
        const urlInput = document.getElementById('form-url');
        const statusElement = document.getElementById('status');
        let currentUrl = '';

        function validateUrl(value) {
            let url;
            try {
                url = new URL(value);
            } catch (error) {
                throw new Error('Masukkan alamat URL yang valid, misalnya http://192.168.1.10:8000.');
            }

            if (!['http:', 'https:'].includes(url.protocol)) {
                throw new Error('Alamat formulir harus menggunakan http:// atau https://.');
            }

            return url.href;
        }

        function generateQr() {
            statusElement.textContent = '';
            try {
                currentUrl = validateUrl(urlInput.value.trim());
            } catch (error) {
                statusElement.textContent = error.message;
                return false;
            }

            if (!window.QRCode) {
                statusElement.textContent = 'QR tidak dapat dibuat karena pustaka QR gagal dimuat. Periksa koneksi internet lalu muat ulang halaman.';
                return false;
            }

            qrElement.replaceChildren();
            new QRCode(qrElement, {
                text: currentUrl,
                width: 720,
                height: 720,
                colorDark: '#072442',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M
            });

            const image = qrElement.querySelector('img');
            if (image) {
                image.alt = 'QR code menuju ' + currentUrl;
            }
            return true;
        }

        function getPngDataUrl() {
            const source = qrElement.querySelector('canvas');
            if (!source) {
                throw new Error('QR belum siap. Tekan Perbarui QR lalu coba lagi.');
            }

            const output = document.createElement('canvas');
            output.width = 1000;
            output.height = 1000;
            const context = output.getContext('2d');
            context.fillStyle = '#ffffff';
            context.fillRect(0, 0, output.width, output.height);
            context.imageSmoothingEnabled = false;
            context.drawImage(source, 60, 60, 880, 880);
            return output.toDataURL('image/png');
        }

        function downloadFile(name, content, type) {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(new Blob([content], { type: type }));
            link.download = name;
            link.click();
            setTimeout(() => URL.revokeObjectURL(link.href), 1000);
        }

        document.getElementById('generate').addEventListener('click', generateQr);
        document.getElementById('download-png').addEventListener('click', function () {
            if (!generateQr()) return;
            try {
                const link = document.createElement('a');
                link.href = getPngDataUrl();
                link.download = 'QR-Buku-Tamu-BGTK.png';
                link.click();
            } catch (error) {
                statusElement.textContent = error.message;
            }
        });
        document.getElementById('download-html').addEventListener('click', function () {
            if (!generateQr()) return;
            try {
                const imageData = getPngDataUrl();
                const safeUrl = currentUrl.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                const html = `<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>QR Buku Tamu BGTK Sumatera Barat</title>
<style>
*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;background:linear-gradient(135deg,#0b3259,#07518a);font-family:Arial,Helvetica,sans-serif;color:#fff;text-align:center;padding:24px}
main{width:min(100%,1100px);padding:clamp(20px,5vw,60px);border-radius:28px;background:#fff;color:#0b3259;box-shadow:0 24px 70px #0005}
.eyebrow{font-weight:bold;letter-spacing:.12em;text-transform:uppercase;color:#a87800}h1{font-size:clamp(28px,5vw,56px);margin:12px 0}p{font-size:clamp(18px,2.4vw,28px);line-height:1.5}
img{display:block;width:min(70vw,620px);height:auto;margin:24px auto}.url{font-size:clamp(14px,1.8vw,20px);overflow-wrap:anywhere;color:#334155}
</style></head><body><main><div class="eyebrow">Kementerian Pendidikan Dasar &amp; Menengah RI</div>
<h1>Buku Tamu BGTK Sumatera Barat</h1><p>Scan QR untuk mengisi formulir kunjungan</p>
<img src="${imageData}" alt="QR code formulir buku tamu"><div class="url">${safeUrl}</div></main></body></html>`;
                downloadFile('QR-Buku-Tamu-BGTK.html', html, 'text/html;charset=utf-8');
            } catch (error) {
                statusElement.textContent = error.message;
            }
        });
        document.getElementById('fullscreen').addEventListener('click', function () {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(function (error) {
                    statusElement.textContent = 'Layar penuh tidak dapat dibuka: ' + error.message;
                });
            } else {
                document.exitFullscreen();
            }
        });

        generateQr();
    </script>
</body>
</html>
