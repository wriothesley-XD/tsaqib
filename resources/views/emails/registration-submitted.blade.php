<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pendaftaran Open Recruitment Baru</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <h2>Pendaftaran Open Recruitment TSAQIB Baru</h2>

    <p>Ada pendaftar baru yang mengisi formulir Open Recruitment. Berikut detailnya:</p>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Nama Lengkap</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registration->full_name }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Nama Panggilan</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registration->nickname }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Kelas</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registration->class }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Username Instagram</strong></td>
            <td style="border: 1px solid #ddd;">@{{ $registration->instagram_username }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Alasan Bergabung</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registration->reason }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Email Akun</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registration->user->email }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd;"><strong>Waktu Submit</strong></td>
            <td style="border: 1px solid #ddd;">{{ $registration->created_at->translatedFormat('d F Y, H:i') }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px; font-size: 12px; color: #888;">
        Email ini dikirim otomatis oleh sistem website TSAQIB.
    </p>
</body>
</html>
