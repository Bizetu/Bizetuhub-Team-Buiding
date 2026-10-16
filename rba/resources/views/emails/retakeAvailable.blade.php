<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Let's Try Again</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#f0f2f5; font-family:'Lato', Arial, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f2f5; padding:40px 20px;">
    <tr><td align="center">
        <table width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #dde0e6;">

            <!-- Top accent bar -->
            <tr>
                <td style="background:#FFB901; height:5px; font-size:0; line-height:0;">&nbsp;</td>
            </tr>

            <!-- Header -->
            <tr>
                <td style="padding:40px 40px 32px; text-align:center; background:#ffffff;">
                    <div style="margin-bottom:28px;">
                        <span style="font-size:22px; font-weight:900; color:#1F3B67; letter-spacing:-0.5px;">{{ $data['sender'] ?? 'Learning Platform' }}</span>
                    </div>
                    <!-- Target / retry icon -->
                    <div style="width:72px; height:72px; background:#FFF7E0; border-radius:50%; margin:0 auto 20px; text-align:center; line-height:72px;">
                        🎯
                    </div>
                    <h1 style="margin:0 0 8px; color:#1F3B67; font-size:24px; font-weight:900; letter-spacing:-0.3px;">Don't Give Up — Retakes Available</h1>
                    <p style="margin:0; color:#7a8499; font-size:14px;">You've done the hard part — a retake is all it takes</p>
                </td>
            </tr>

            <tr><td style="padding:0 40px;"><div style="height:1px; background:#e5e8ee;"></div></td></tr>

            <!-- Body -->
            <tr>
                <td style="padding:32px 40px;">
                    <p style="margin:0 0 8px; color:#1F3B67; font-size:18px; font-weight:700;">Hi {{ $data['name'] }},</p>
                    <p style="margin:0 0 28px; color:#4a5568; font-size:15px; line-height:1.75;">
                        You've completed all your mandatory modules for the <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> job role — that takes real commitment. However, your current average score is below the required pass mark. A retake can get you there.
                    </p>

                    <!-- Score visual -->
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
                        <tr>
                            <td style="background:#f8f9fb; border-radius:12px; border:1px solid #dde0e6; padding:28px; text-align:center;">

                                <!-- Score comparison -->
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
                                    <tr>
                                        <td width="45%" style="text-align:center;">
                                            <div style="font-size:12px; font-weight:700; color:#7a8499; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Your Score</div>
                                            <div style="font-size:44px; font-weight:900; color:#1F3B67;">{{ $data['averageScore'] }}%</div>
                                        </td>
                                        <td width="10%" style="text-align:center; font-size:20px; color:#c5cad6; vertical-align:middle;">vs</td>
                                        <td width="45%" style="text-align:center;">
                                            <div style="font-size:12px; font-weight:700; color:#7a8499; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:6px;">Pass Mark</div>
                                            <div style="font-size:44px; font-weight:900; color:#FFB901;">70%</div>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Progress bar -->
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="background:#e5e8ee; border-radius:999px; height:12px; overflow:hidden;">
                                            <div style="width:{{ $data['averageScore'] }}%; background:#1F3B67; height:12px; border-radius:999px;"></div>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:8px;">
                                    <tr>
                                        <td style="font-size:12px; color:#aab0bc;">0%</td>
                                        <td style="text-align:center; font-size:12px; font-weight:700; color:#1F3B67;">You: {{ $data['averageScore'] }}%</td>
                                        <td style="text-align:right; font-size:12px; font-weight:700; color:#FFB901;">Pass: 70%</td>
                                    </tr>
                                </table>

                                <p style="margin:16px 0 0; font-size:13px; color:#4a5568;">
                                    You need <strong style="color:#1F3B67;">{{ round((70 - $data['averageScore']), 1) }}% more</strong> to pass and unlock your certificate
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Tips box -->
                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px; background:#f8f9fb; border-radius:10px; border-left:4px solid #FFB901;">
                        <tr>
                            <td style="padding:20px 24px;">
                                <table cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
                                    <tr>
                                        <td style="padding-right:8px; vertical-align:middle;">
                                            <svg width="15" height="15" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="8" cy="8" r="7" stroke="#FFB901" stroke-width="1.5" fill="none"/>
                                                <line x1="8" y1="5" x2="8" y2="9" stroke="#FFB901" stroke-width="1.5" stroke-linecap="round"/>
                                                <circle cx="8" cy="11.5" r="0.75" fill="#FFB901"/>
                                            </svg>
                                        </td>
                                        <td style="font-size:13px; font-weight:700; color:#1F3B67;">What you can do now</td>
                                    </tr>
                                </table>
                                <ul style="margin:0; padding-left:18px; color:#4a5568; font-size:13px; line-height:1.9;">
                                    <li>Revisit modules where you scored below 70%</li>
                                    <li>Re-read study materials before retaking</li>
                                    <li>Take your time — accuracy matters more than speed</li>
                                </ul>
                            </td>
                        </tr>
                    </table>

                    <!-- CTA -->
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center">
                                <a href="{{ $data['loginUrl'] . route('profile.courses', [], false) }}" style="display:inline-block; background:#FFB901; color:#1F3B67; padding:15px 44px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                    Retake My Modules →
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="background:#1F3B67; padding:28px 40px; text-align:center;">
                    <p style="margin:0 0 6px; color:#ffffff; font-size:14px; font-weight:700;">{{ $data['supportTeam'] }}</p>
                    <p style="margin:0 0 12px; color:rgba(255,255,255,0.45); font-size:12px;">You're receiving this because you completed training on our platform.</p>
                    <p style="margin:0; font-size:12px;">
                        <a href="{{ $unsubscribeLink }}" style="color:rgba(255,255,255,0.4); text-decoration:underline;">
                            Manage notifications
                        </a>
                        &nbsp;&middot;&nbsp;
                        <span style="color:rgba(255,255,255,0.4);">© {{ date('Y') }} Learning Platform</span>
                    </p>
                </td>
            </tr>

        </table>
    </td></tr>
</table>
</body>
</html>