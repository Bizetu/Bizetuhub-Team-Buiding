<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['totalCount'] === 1 ? 'Your Module Awaits' : 'Your Training Awaits' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
</head>

<body style="margin:0; padding:0; background-color:#f0f2f5; font-family:'Lato', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f2f5; padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #dde0e6;">

                    <!-- Top accent bar -->
                    <tr>
                        <td style="background:#FFB901; height:5px; font-size:0; line-height:0;">&nbsp;</td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td style="padding:40px 40px 32px; text-align:center; background:#ffffff;">
                            <div style="margin-bottom:28px;">
                                <span
                                    style="font-size:22px; font-weight:900; color:#1F3B67; letter-spacing:-0.5px;">{{ $data['sender'] ?? 'Learning Platform' }}</span>
                            </div>
                            <!-- Graduation cap SVG icon -->
                        <div style="width:72px; height:72px; background:#FFF7E0; border-radius:50%; margin:0 auto 20px; text-align:center; line-height:72px;">
                            🎓
                        </div>
                            <h1
                                style="margin:0 0 8px; color:#1F3B67; font-size:24px; font-weight:900; letter-spacing:-0.3px;">
                                {{ $data['totalCount'] === 1 ? 'Your Module Awaits' : 'Your Training Awaits' }}
                            </h1>
                            <p style="margin:0; color:#7a8499; font-size:14px; font-weight:400;">
                                {{ $data['totalCount'] === 1 ? 'You have a mandatory module ready to begin' : 'You have mandatory modules ready to begin' }}
                            </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:0 40px;">
                            <div style="height:1px; background:#e5e8ee;"></div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px 40px;">
                            <p style="margin:0 0 8px; color:#1F3B67; font-size:18px; font-weight:700;">Hi
                                {{ $data['name'] }},</p>
                            <p style="margin:0 0 28px; color:#4a5568; font-size:15px; line-height:1.75;">
                                As a <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong>,
                                @if ($data['totalCount'] === 1)
                                    you have a mandatory training module to complete on our platform. You haven't
                                    started yet — let's change that today.
                                @else
                                    you have mandatory training modules to complete on our platform. You haven't started
                                    yet — let's change that today.
                                @endif
                            </p>

                            <!-- Stats Cards -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                                <tr>
                                    <td width="48%"
                                        style="text-align:center; padding:24px 16px; background:#FFF7E0; border-radius:10px; border:1px solid #FFE580;">
                                        <div style="font-size:40px; font-weight:900; color:#FFB901;">
                                            {{ $data['totalCount'] }}</div>
                                        <div
                                            style="font-size:11px; font-weight:700; color:#c98f00; margin-top:4px; text-transform:uppercase; letter-spacing:0.6px;">
                                            {{ $data['totalCount'] === 1 ? 'Mandatory Module' : 'Mandatory Modules' }}
                                        </div>
                                    </td>
                                    <td width="4%"></td>
                                    <td width="48%"
                                        style="text-align:center; padding:24px 16px; background:#f0f2f5; border-radius:10px; border:1px solid #dde0e6;">
                                        <div style="font-size:40px; font-weight:900; color:#1F3B67;">0%</div>
                                        <div
                                            style="font-size:11px; font-weight:700; color:#7a8499; margin-top:4px; text-transform:uppercase; letter-spacing:0.6px;">
                                            Completed</div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Role badge -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                                <tr>
                                    <td
                                        style="padding:18px 20px; background:#f8f9fb; border-radius:10px; border-left:4px solid #FFB901;">
                                        <table cellpadding="0" cellspacing="0" style="margin-bottom:4px;">
                                            <tr>
                                                <td style="padding-right:6px; vertical-align:middle;">
                                                    <svg width="13" height="13" viewBox="0 0 16 16"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect x="1" y="4" width="14" height="10" rx="1.5"
                                                            fill="#FFB901" opacity="0.25" />
                                                        <rect x="1" y="4" width="14" height="10" rx="1.5"
                                                            stroke="#FFB901" stroke-width="1.2" fill="none" />
                                                        <path d="M5 4V3a3 3 0 016 0v1" stroke="#FFB901"
                                                            stroke-width="1.2" stroke-linecap="round" fill="none" />
                                                        <circle cx="8" cy="9" r="1.5" fill="#FFB901" />
                                                    </svg>
                                                </td>
                                                <td
                                                    style="font-size:11px; font-weight:700; color:#FFB901; text-transform:uppercase; letter-spacing:0.8px;">
                                                    Your Role</td>
                                            </tr>
                                        </table>
                                        <p style="margin:0; font-size:16px; font-weight:700; color:#1F3B67;">
                                            {{ $data['jobRole'] }}</p>
                                        <p style="margin:4px 0 0; font-size:13px; color:#7a8499;">
                                            {{ $data['totalCount'] }}
                                            {{ $data['totalCount'] === 1 ? 'mandatory module' : 'mandatory modules' }}
                                            assigned to this role
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Module list — capped at 3 -->
                            @if ($data['courses']->count() > 0)
                                <p
                                    style="margin:0 0 10px; font-size:11px; font-weight:700; color:#1F3B67; text-transform:uppercase; letter-spacing:0.6px;">
                                    {{ $data['totalCount'] === 1 ? 'Your module' : 'Your modules' }}
                                </p>
                                @foreach ($data['courses']->take(3) as $module)
                                    <table width="100%" cellpadding="0" cellspacing="0"
                                        style="margin-bottom:8px;">
                                        <tr>
                                            <td
                                                style="padding:12px 14px; background:#f8f9fb; border-radius:8px; border-left:3px solid #FFB901;">
                                                <table cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="padding-right:10px; vertical-align:middle;">
                                                            <svg width="15" height="15" viewBox="0 0 16 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M3 2h10a1 1 0 011 1v11l-6-3-6 3V3a1 1 0 011-1z"
                                                                    fill="#FFB901" />
                                                            </svg>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <a href="{{ route('courses.detail', $module->short_name) }}"
                                                                style="font-size:14px; font-weight:700; color:#1F3B67; text-decoration:none;">
                                                                {{ $module->display_name }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                @endforeach
                                @if ($data['courses']->count() > 3)
                                    <p style="margin:6px 0 24px; font-size:13px; color:#7a8499; text-align:center;">
                                        + {{ $data['courses']->count() - 3 }} more modules on the platform
                                    </p>
                                @else
                                    <div style="margin-bottom:24px;"></div>
                                @endif
                            @endif

                            <p style="margin:0 0 28px; color:#4a5568; font-size:14px; line-height:1.75;">
                                Completing your training keeps you up to date with your role requirements and unlocks
                                your certificate upon passing.
                            </p>

                            <!-- CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $data['loginUrl'] }}"
                                            style="display:inline-block; background:#FFB901; color:#1F3B67; padding:15px 44px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                            {{ $data['totalCount'] === 1 ? 'Begin My Module →' : 'Begin My Training →' }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#1F3B67; padding:28px 40px; text-align:center;">
                            <p style="margin:0 0 6px; color:#ffffff; font-size:14px; font-weight:700;">
                                {{ $data['supportTeam'] }}</p>
                            <p style="margin:0 0 12px; color:rgba(255,255,255,0.45); font-size:12px;">You're receiving
                                this because you are registered to take training on our platform.</p>
                            <p style="margin:0; font-size:12px;">
                                <a href="{{ $unsubscribeLink }}"
                                    style="color:rgba(255,255,255,0.4); text-decoration:underline;">
                                    Manage notifications
                                </a>
                                &nbsp;&middot;&nbsp;
                                <span style="color:rgba(255,255,255,0.4);">© {{ date('Y') }} Learning
                                    Platform</span>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
