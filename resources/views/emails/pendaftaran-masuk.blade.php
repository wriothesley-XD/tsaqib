<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #1a1a1a;">

    <h2>Pendaftaran Baru — Tsaqib Island</h2>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 500px;">
        <tr>
            <td style="font-weight: bold; width: 160px;">Komunitas</td>
            <td>{{ $data['komunitas_nama'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Role</td>
            <td>{{ $data['role_nama'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Lengkap</td>
            <td>{{ $data['nama_lengkap'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Panggilan</td>
            <td>{{ $data['nama_panggilan'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Instagram</td>
            <td>{{ $data['instagram'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Alasan</td>
            <td>{{ $data['alasan'] }}</td>
        </tr>
    </table>

</body>
</html>
