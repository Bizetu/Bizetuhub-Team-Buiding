<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You've Passed!</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Lato', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:40px 20px;">
    <tr><td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

            <tr>
                <td style="background:#1F3B67; padding:52px 40px 44px; text-align:center;">
                    <div style="width:88px; height:88px; background:rgba(255,255,255,0.1); border-radius:50%; margin:0 auto 20px; text-align:center; line-height:88px; font-size:44px; border:2px solid #FFB901;">
                        🏆
                    </div>
                    <h1 style="margin:0 0 10px; color:#ffffff; font-size:28px; font-weight:900; letter-spacing:-0.5px;">You've Passed!</h1>
                    <p style="margin:0; color:#ffffff; font-size:15px; line-height:1.6; opacity: 0.9;">
                        @if($data['totalCount'] === 1)
                            You've successfully completed <strong style="color:#FFB901;">1 mandatory module</strong><br>and met the required pass mark.
                        @else
                            You've successfully completed <strong style="color:#FFB901;">{{$data['totalCount']}} mandatory modules</strong><br>and met the required pass mark.
                        @endif
                    </p>
                </td>
            </tr>

            <tr>
                <td style="padding:36px 40px 32px;">
                    <p style="margin:0 0 8px; color:#1F3B67; font-size:18px; font-weight:700;">Congratulations, {{ $data['name'] }}!</p>
                    <p style="margin:0 0 28px; color:#000000; font-size:15px; line-height:1.75;">
                        @if($data['totalCount'] === 1)
                            You've completed a mandatory module for the <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> job role with a score above the 70% pass mark. Your certificate is ready to download.
                        @else
                            You've completed mandatory modules for the <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> job role with scores above the 70% pass mark. Your certificate is ready to download.
                        @endif
                    </p>

                    @if($data['courses']->count() > 0)
                    <p style="margin:0 0 12px; font-size:12px; font-weight:700; color:#1F3B67; text-transform:uppercase; letter-spacing:0.6px;">Completed modules</p>
                    @foreach($data['courses']->take(3) as $module)
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
                        <tr>
                            <td style="padding:13px 16px; background:#f9fafb; border-radius:8px; border-left:3px solid #FFB901;">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <a href="{{ $data['loginUrl'] . route('profile.courses', [], false) }}"
                                               style="font-size:14px; font-weight:700; color:#1F3B67; text-decoration:none;">
                                                {{ $module->courses->display_name }}
                                            </a>
                                        </td>
                                        <td align="right" style="white-space:nowrap; padding-left:12px;">
                                            <span style="display:inline-block; background:#FFB901; color:#1F3B67; font-size:12px; font-weight:700; padding:3px 10px; border-radius:20px;">
                                                {{$module->score}}%
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @if($data['courses']->count() > 3)
                    <p style="margin:6px 0 0; font-size:13px; color:#6b7280; text-align:center;">
                        + {{ $data['courses']->count() - 3 }} more completed module(s) — view all on the platform
                    </p>
                    @endif
                    @endif

                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:32px;">
                        <tr>
                            <td align="center">
                                <a href="{{ $data['loginUrl'] . route('profile.courses', [], false) }}" style="display:inline-block; background:#FFB901; color:#1F3B67; padding:15px 44px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                    Download Certificate →
                                </a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>

            <tr>
                <td style="background:#1F3B67; padding:28px 40px; text-align:center;">
                    <p style="margin:0 0 6px; color:#ffffff; font-size:14px; font-weight:700;">{{$data['supportTeam']}}</p>
                    <p style="margin:0 0 12px; color:rgba(255,255,255,0.6); font-size:12px;">You're receiving this because you completed training on our platform.</p>
                    <p style="margin:0; font-size:12px;">
                        <a href="{{ $unsubscribeLink }}"
                            style="color:rgba(255,255,255,0.4); text-decoration:underline;">
                            Manage notifications
                        </a>
                        &nbsp;&middot;&nbsp;
                        <span style="color:rgba(255,255,255,0.6);">© {{ date('Y') }} Learning Platform</span>
                    </p>
                </td>
            </tr>

        </table>
    </td></tr>
</table>
</body>
</html>