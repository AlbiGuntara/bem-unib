<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balasan Pesan - BEM UNIB</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f7fc;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f7fc;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#1e40af,#3b82f6);padding:40px 40px 32px;text-align:center;">
                            <img src="{{ asset('images/logo/bem.png') }}" alt="BEM UNIB" style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin-bottom:16px;">
                            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;letter-spacing:-0.5px;">BEM UNIB</h1>
                            <p style="margin:6px 0 0;color:rgba(255,255,255,0.8);font-size:14px;">Badan Eksekutif Mahasiswa Universitas Ibrahimy</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:40px;">
                            <p style="margin:0 0 8px;color:#64748b;font-size:14px;">{{ $pesan->created_at->format('d F Y') }}</p>
                            <h2 style="margin:0 0 24px;color:#1e293b;font-size:20px;font-weight:600;">{{ $pesan->subject }}</h2>

                            <div style="background-color:#f8fafc;border-radius:12px;padding:20px;margin-bottom:24px;border:1px solid #e2e8f0;">
                                <p style="margin:0 0 4px;color:#94a3b8;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;">Pesan Asli</p>
                                <p style="margin:0;color:#475569;font-size:14px;line-height:1.7;">{{ $pesan->content }}</p>
                            </div>

                            <div style="background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;padding:20px;border:1px solid #bfdbfe;">
                                <p style="margin:0 0 4px;color:#3b82f6;font-size:12px;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;">Balasan</p>
                                <p style="margin:0;color:#1e293b;font-size:14px;line-height:1.7;">{{ $pesan->reply_content }}</p>
                                <p style="margin:16px 0 0;color:#64748b;font-size:12px;">
                                    Dibalas oleh {{ $replierName }} pada {{ $pesan->replied_at->format('d F Y H:i') }}
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 40px;background-color:#f8fafc;border-top:1px solid #e2e8f0;text-align:center;">
                            <p style="margin:0 0 12px;color:#94a3b8;font-size:12px;line-height:1.6;">
                                Email ini dikirim secara otomatis oleh sistem BEM UNIB.<br>
                                Mohon tidak membalas email ini.
                            </p>
                            <div style="margin:16px 0;">
                                <a href="#" style="display:inline-block;margin:0 6px;color:#3b82f6;text-decoration:none;font-size:13px;">Instagram</a>
                                <span style="color:#cbd5e1;">|</span>
                                <a href="#" style="display:inline-block;margin:0 6px;color:#3b82f6;text-decoration:none;font-size:13px;">TikTok</a>
                                <span style="color:#cbd5e1;">|</span>
                                <a href="#" style="display:inline-block;margin:0 6px;color:#3b82f6;text-decoration:none;font-size:13px;">YouTube</a>
                            </div>
                            <p style="margin:0;color:#cbd5e1;font-size:11px;">&copy; {{ date('Y') }} BEM UNIB. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
