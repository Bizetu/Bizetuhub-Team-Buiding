<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['totalCount'] === 1 ? 'Your Module Awaits' : 'Your Training Awaits' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Lato', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:40px 20px;">
    <tr><td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

            <tr>
                <td style="background:#FFB901; height:5px; font-size:0; line-height:0;">&nbsp;</td>
            </tr>

            <tr>
                <td style="padding:40px 40px 32px; text-align:center; background:#ffffff;">
                    <div style="margin-bottom:28px;">
                        <span style="font-size:22px; font-weight:900; color:#1F3B67; letter-spacing:-0.5px;">{{ $data['sender'] ?? 'Learning Platform' }}</span>
                    </div>
                    <div style="width:72px; height:72px; background:#fff8e6; border-radius:50%; margin:0 auto 20px; text-align:center; line-height:72px; font-size:34px; border: 1px solid #FFB901;">
                        🎓
                    </div>
                    <h1 style="margin:0 0 8px; color:#1F3B67; font-size:24px; font-weight:900; letter-spacing:-0.3px;">
                        {{ $data['totalCount'] === 1 ? 'Your Module Awaits' : 'Your Training Awaits' }}
                    </h1>
                    <p style="margin:0; color:#6b7280; font-size:14px; font-weight:400;">
                        {{ $data['totalCount'] === 1 ? 'You have a mandatory module ready to begin' : 'You have mandatory modules ready to begin' }}
                    </p>
                </td>
            </tr>

            <tr><td style="padding:0 40px;"><div style="height:1px; background:#f3f4f6;"></div></td></tr>

            <tr>
                <td style="padding:32px 40px;">
                    <p style="margin:0 0 8px; color:#1F3B67; font-size:18px; font-weight:700;">Hi {{ $data['name'] }},</p>
                    <p style="margin:0 0 28px; color:#000000; font-size:15px; line-height:1.75;">
                        As a <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong>,
                        @if($data['totalCount'] === 1)
                            you have a mandatory module to complete as part of your required training. You haven't started yet — let's change that today.
                        @else
                            you have mandatory modules to complete as part of your required training. You haven't started yet — let's change that today.
                        @endif
                    </p>
                    
                    @if($data['courses']->count() > 0)
                    @foreach($data['courses']->take(3) as $module)
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
                        <tr>
                            <td style="padding:13px 16px; background:#f9fafb; border-radius:8px; border-left:3px solid #FFB901;">
                                <a href="{{ $data['loginUrl'].route('course.detail', $module->short_name, false) }}"
                                    style="font-size:14px; font-weight:700; color:#1F3B67; text-decoration:none;">
                                    {{ $module->display_name }}
                                </a>
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @if($data['courses']->count() > 3)
                    <p style="margin:6px 0 24px; font-size:13px; color:#9ca3af; text-align:center;">
                        + {{ $data['courses']->count() - 3 }} more modules on the platform
                    </p>
                    @else
                    <div style="margin-bottom:24px;"></div>
                    @endif
                    @endif

                    <p style="margin:0 0 28px; color:#6b7280; font-size:14px; line-height:1.75;">
                        @if($data['totalCount'] === 1)
                            Completing this module keeps you up to date with your job role requirements and brings you one step closer to your certificate.
                        @else
                            Completing these modules keeps you up to date with your job role requirements and brings you one step closer to your certificate.
                        @endif
                    </p>

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center">
                                <a href="{{ $data['loginUrl'] }}" style="display:inline-block; background:#FFB901; color:#1F3B67; padding:15px 44px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                    {{ $data['totalCount'] === 1 ? 'Begin My Module →' : 'Begin My Training →' }}
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="background:#1F3B67; padding:28px 40px; text-align:center;">
                    <p style="margin:0 0 6px; color:#ffffff; font-size:14px; font-weight:700;">{{$data['supportTeam']}}</p>
                    <p style="margin:0 0 12px; color:rgba(255,255,255,0.6); font-size:12px;">You're receiving this because you are registered to take training on our platform.</p>
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