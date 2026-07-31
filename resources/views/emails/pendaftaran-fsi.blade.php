<!-- resources/views/emails/pendaftaran-fsi.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Baru FSI</title>
</head>
<body style="font-family: sans-serif; color: #1a1a1a;">

    <h2>Pendaftaran Open Recruitment FSI Baru</h2>

    <table cellpadding="8" style="border-collapse: collapse;">
        <tr>
            <td><strong>Nama Lengkap</strong></td>
            <td>{{ $registration->nama_lengkap }}</td>
        </tr>
        <tr>
            <td><strong>Nama Panggilan</strong></td>
            <td>{{ $registration->nama_panggilan }}</td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>{{ $registration->kelas }}</td>
        </tr>
        <tr>
            <td><strong>Username Instagram</strong></td>
            <td>{{ $registration->instagram_username }}</td>
        </tr>
        <tr>
            <td valign="top"><strong>Alasan Bergabung</strong></td>
            <td>{{ $registration->alasan_bergabung }}</td>
        </tr>
    </table>

    <p style="color: #777; font-size: 13px; margin-top: 24px;">
        Dikirim otomatis dari sistem TSAQIB.
    </p>

</body>
</html>
