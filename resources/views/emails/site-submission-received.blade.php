<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>New {{ $submissionType }} request</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f4ed; color:#17352a; font-family:Arial, Helvetica, sans-serif;">
    <div style="display:none; max-height:0; overflow:hidden; opacity:0;">
        A new {{ $submissionType }} request has been received by Bizetu Hub.
    </div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#f5f4ed;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px; background-color:#ffffff; border:1px solid #e5e7df; border-radius:14px; overflow:hidden;">
                    <tr>
                        <td style="padding:28px 32px; background-color:#0a1f1a; border-bottom:5px solid #facc15;">
                            <p style="margin:0 0 8px; color:#facc15; font-size:12px; font-weight:bold; letter-spacing:2px; text-transform:uppercase;">Bizetu Hub</p>
                            <h1 style="margin:0; color:#ffffff; font-size:25px; line-height:1.3;">New {{ $submissionType }} request</h1>
                            <p style="margin:10px 0 0; color:#dce8df; font-size:14px; line-height:1.6;">A new request has been received. Review the details below and follow up with the sender.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 32px 12px;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="padding:8px 13px; background-color:#fff7d6; border:1px solid #f3dc78; border-radius:999px; color:#17352a; font-size:13px; font-weight:bold;">Request #{{ $submissionId }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
                                @foreach($details as $label => $value)
                                    @if($value !== null && $value !== '')
                                        <tr>
                                            <td style="padding:13px 15px; border-bottom:1px solid #e8ece6; background-color:#f8faf7; color:#52645a; font-size:12px; font-weight:bold; letter-spacing:0.7px; text-transform:uppercase; vertical-align:top; width:30%;">{{ str($label)->replace('_', ' ')->title() }}</td>
                                            <td style="padding:13px 15px; border-bottom:1px solid #e8ece6; color:#17352a; font-size:14px; line-height:1.6; overflow-wrap:anywhere;">{{ $value }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px; background-color:#0a1f1a; color:#dce8df; font-size:12px; line-height:1.6;">
                            This notification was sent from the Bizetu Hub website. You can manage this request in the admin area.
                        </td>
                    </tr>
                </table>
                <p style="margin:16px 0 0; color:#738078; font-size:11px; line-height:1.5;">Build. Belong. Become.</p>
            </td>
        </tr>
    </table>
</body>
</html>
