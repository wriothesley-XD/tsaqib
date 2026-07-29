<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; color: #1a1a1a;">

    <h2>Pendaftaran Baru — Open Recruitment FSI (TSAQIB)</h2>

    <table cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 500px;">
        <tr>
            <td style="font-weight: bold; width: 160px;">Nama Lengkap</td>
            <td>{{ $registration->full_name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Nama Panggilan</td>
            <td>{{ $registration->nickname }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Kelas</td>
            <td>{{ $registration->class }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Instagram</td>
            <td>{{ $registration->username_ig }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Alasan Bergabung</td>
            <td>{{ $registration->reason }}</td>
        </tr>
    </table>

</body>
</html>
