<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['totalCount'] === 1 ? 'Let\'s Try Again' : 'Don\'t Give Up!' }}</title>
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
                        <span style="font-size:22px; font-weight:900; color:#1F3B67; letter-spacing:-0.5px;">{{ $data['platformName'] ?? 'Learning Platform' }}</span>
                    </div>
                    <div style="width:72px; height:72px; background:#f9fafb; border-radius:50%; margin:0 auto 20px; text-align:center; line-height:72px; font-size:34px; border: 1px solid #f3f4f6;">
                        💪
                    </div>
                    <h1 style="margin:0 0 8px; color:#1F3B67; font-size:24px; font-weight:900; letter-spacing:-0.3px;">
                        {{ $data['totalCount'] === 1 ? 'Don\'t Give Up — A Retake Is Available' : 'Don\'t Give Up — Retakes Are Available' }}
                    </h1>
                    <p style="margin:0; color:#6b7280; font-size:14px;">
                        {{ $data['totalCount'] === 1 ? 'You\'ve done the hard part — a retake is all it takes' : 'You\'ve done the hard part — retakes are all it takes' }}
                    </p>
                </td>
            </tr>

            <tr><td style="padding:0 40px;"><div style="height:1px; background:#f3f4f6;"></div></td></tr>

            <tr>
                <td style="padding:32px 40px;">
                    <p style="margin:0 0 8px; color:#1F3B67; font-size:18px; font-weight:700;">Hi {{ $data['name'] }},</p>
                    <p style="margin:0 0 28px; color:#000000; font-size:15px; line-height:1.75;">
                        @if($data['totalCount'] === 1)
                            You've completed your mandatory module for the <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> job role — that takes real commitment. However, your score is below the required pass mark of 70%. A retake can get you there.
                        @else
                            You've completed mandatory modules for the <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> job role — that takes real commitment. However, some of your scores are below the required pass mark of 70%. Retakes can get you there.
                        @endif
                    </p>

                    @if($data['courses']->count() > 0)
                    <p style="margin:0 0 12px; font-size:12px; font-weight:700; color:#1F3B67; text-transform:uppercase; letter-spacing:0.6px;">
                        {{ $data['totalCount'] === 1 ? 'Your Score' : 'Module Scores' }}
                    </p>
                    @foreach($data['courses']->take(3) as $module)
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
                        <tr>
                            <td style="padding:13px 16px; background:#f9fafb; border-radius:8px; border-left:3px solid {{ $module->score >= 70 ? '#FFB901' : '#1F3B67' }};">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <a href="{{ route('course.detail', $module->id) }}"
                                               style="font-size:14px; font-weight:700; color:#1F3B67; text-decoration:none;">
                                                {{ $module->courses->display_name }}
                                            </a>
                                        </td>
                                        <td align="right" style="white-space:nowrap; padding-left:12px;">
                                            <span style="display:inline-block; background:{{ $module->score >= 70 ? '#FFB901' : '#1F3B67' }}; color:{{ $module->score >= 70 ? '#1F3B67' : '#ffffff' }}; font-size:12px; font-weight:700; padding:3px 10px; border-radius:20px;">
                                                {{ $module->score }}%
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    @endforeach
                    @if($data['courses']->count() > 3)
                    <p style="margin:6px 0 0; font-size:13px; color:#9ca3af; text-align:center;">
                        + {{ $data['courses']->count() - 3 }} more modules on the platform
                    </p>
                    @endif
                    @endif


                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px; border-radius:10px; border-left:4px solid #FFB901;">
                        <tr>
                            <td style="padding:20px 24px; background:#f9fafb; border-radius:0 10px 10px 0;">
                                <p style="margin:0 0 10px; font-size:13px; font-weight:700; color:#1F3B67;">What you can do now</p>
                                <ul style="margin:0; padding-left:18px; color:#6b7280; font-size:13px; line-height:1.9;">
                                    @if($data['totalCount'] === 1)
                                        <li>Re-read the study materials before retaking</li>
                                        <li>Take your time — accuracy matters more than speed</li>
                                        <li>Focus on the areas where you lost marks</li>
                                    @else
                                        <li>Revisit modules where you scored below 70%</li>
                                        <li>Re-read study materials before retaking</li>
                                        <li>Take your time — accuracy matters more than speed</li>
                                    @endif
                                </ul>
                            </td>
                        </tr>
                    </table>

                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center">
                                <a href="{{ $data['loginUrl'] . route('profile.courses', [], false) }}" style="display:inline-block; background:#FFB901; color:#1F3B67; padding:15px 44px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                    {{ $data['totalCount'] === 1 ? 'Retake My Module →' : 'Retake My Modules →' }}
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td style="background:#1F3B67; padding:28px 40px; text-align:center;">
                    <p style="margin:0 0 6px; color:#ffffff; font-size:14px; font-weight:700;">{{ $data['supportTeam'] }}</p>
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