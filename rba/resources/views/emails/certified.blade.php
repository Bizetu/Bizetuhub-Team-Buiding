<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Certified!</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Lato', Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#1F3B67; padding:52px 40px 44px; text-align:center;">
                            <div style="width:88px; height:88px; background:rgba(255,185,1,0.18); border-radius:50%; margin:0 auto 20px; text-align:center; line-height:88px; border:2px solid rgba(255,185,1,0.5);">
                                <svg width="44" height="44" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-top:22px;">
                                    <path d="M18 4L22 14H33L24 20.5L27.5 31L18 25L8.5 31L12 20.5L3 14H14L18 4Z" fill="#FFB901"/>
                                </svg>
                            </div>
                            <h1 style="margin:0 0 10px; color:#ffffff; font-size:28px; font-weight:900; letter-spacing:-0.5px;">
                                You're Certified!
                            </h1>
                            <p style="margin:0; color:rgba(255,255,255,0.85); font-size:15px; line-height:1.6;">
                                You've successfully completed all mandatory modules<br>and met the required pass mark.
                            </p>
                            <div style="display:inline-block; margin-top:24px; background:rgba(255,185,1,0.15); border-radius:999px; padding:8px 24px; border:1px solid rgba(255,185,1,0.4);">
                                <span style="color:#ffffff; font-size:14px; font-weight:700;">Average Score: <span style="color:#FFB901;">{{ $data['averageScore'] }}%</span></span>
                            </div>
                        </td>
                    </tr>

                    <!-- Achievement strip -->
                    <tr>
                        <td style="background:#EEF3FA; border-bottom:1px solid #C8D8EE; padding:0;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="33%" style="text-align:center; padding:20px 8px; border-right:1px solid #C8D8EE;">
                                        <div style="font-size:22px; font-weight:900; color:#1F3B67;">{{ $data['totalCount'] }}/{{ $data['totalCount'] }}</div>
                                        <div style="font-size:11px; font-weight:700; color:#1F3B67; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px; opacity:0.65;">Modules Done</div>
                                    </td>
                                    <td width="33%" style="text-align:center; padding:20px 8px; border-right:1px solid #C8D8EE;">
                                        <div style="font-size:22px; font-weight:900; color:#FFB901;">{{ $data['averageScore'] }}%</div>
                                        <div style="font-size:11px; font-weight:700; color:#B88200; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px;">Avg Score</div>
                                    </td>
                                    <td width="33%" style="text-align:center; padding:20px 8px;">
                                        <div style="font-size:22px; font-weight:900; color:#1F3B67;">{{ \Carbon\Carbon::parse($data['completedAt'])->format('M Y') }}</div>
                                        <div style="font-size:11px; font-weight:700; color:#1F3B67; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px; opacity:0.65;">Completed</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:36px 40px 32px;">
                            <p style="margin:0 0 8px; color:#000000; font-size:18px; font-weight:700;">
                                Congratulations, {{ $data['name'] }}!
                            </p>
                            <p style="margin:0 0 28px; color:#6b7280; font-size:15px; line-height:1.75;">
                                Your hard work has paid off. You've completed all mandatory training for the
                                <strong style="color:#000000;">{{ $data['jobRole'] }}</strong> job role with a score of
                                <strong style="color:#1F3B67;">{{ $data['averageScore'] }}%</strong> — above the 70% pass mark.
                                Your certificate is ready to download.
                            </p>

                            <!-- Certificate download card -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                                <tr>
                                    <td style="background:#EEF3FA; border-radius:12px; border:1px solid #C8D8EE; padding:32px; text-align:center;">
                                        <div style="width:52px; height:52px; background:#1F3B67; border-radius:50%; margin:0 auto 14px; display:flex; align-items:center; justify-content:center;">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="3" y="4" width="18" height="14" rx="2" stroke="#FFB901" stroke-width="1.5"/>
                                                <path d="M8 10h8M8 13h5" stroke="#FFB901" stroke-width="1.5" stroke-linecap="round"/>
                                                <circle cx="12" cy="20" r="2" fill="#FFB901"/>
                                                <path d="M10 18.5v-1M14 18.5v-1" stroke="#FFB901" stroke-width="1"/>
                                            </svg>
                                        </div>
                                        <p style="margin:0 0 4px; font-size:17px; font-weight:900; color:#1F3B67;">{{ $data['jobRole'] }}</p>
                                        <p style="margin:0 0 4px; font-size:14px; color:#6b7280;">Certificate of Completion</p>
                                        <p style="margin:0 0 24px; font-size:12px; color:#9ca3af;">Issued: {{ \Carbon\Carbon::parse($data['completedAt'])->format('F j, Y') }}</p>
                                        <a href="{{ $data['certificateUrl'] }}"
                                            style="display:inline-block; background:#1F3B67; color:#ffffff; padding:14px 36px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                            Download Certificate &rarr;
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- What's next -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background:#FFF8E6; border-radius:10px; border-left:4px solid #FFB901;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 4px; font-size:13px; font-weight:700; color:#000000;">What's next?</p>
                                        <p style="margin:0; font-size:13px; color:#6b7280; line-height:1.7;">
                                            Your completion has been recorded on the platform. You may be assigned
                                            additional modules as your job role requirements are updated. Keep an eye on
                                            your dashboard.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#000000; padding:28px 40px; text-align:center;">
                            <p style="margin:0 0 6px; color:#ffffff; font-size:14px; font-weight:700;">{{ $data['supportTeam'] }}</p>
                            <p style="margin:0 0 12px; color:rgba(255,255,255,0.5); font-size:12px;">
                                You're receiving this because you completed training on our platform.
                            </p>
                            <p style="margin:0; font-size:12px;">
                                <a href="{{ $unsubscribeLink }}"
                                    style="color:rgba(255,255,255,0.4); text-decoration:underline;">
                                    Manage notifications
                                </a>
                                &nbsp;&middot;&nbsp;
                                <span style="color:rgba(255,255,255,0.35);">&copy; {{ date('Y') }} Learning Platform</span>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>