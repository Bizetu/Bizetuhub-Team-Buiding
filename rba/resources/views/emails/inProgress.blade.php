<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keep Going!</title>
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
                            <!-- Flame icon -->
                            <div style="width:72px; height:72px; background:#FFF7E0; border-radius:50%; margin:0 auto 20px; text-align:center; line-height:72px;">
                                🎓
                            </div>
                            <h1
                                style="margin:0 0 8px; color:#1F3B67; font-size:24px; font-weight:900; letter-spacing:-0.3px;">
                                You're Making Progress!</h1>
                            <p style="margin:0; color:#7a8499; font-size:14px;">Keep the momentum going — you're on your
                                way</p>
                        </td>
                    </tr>

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
                                @if ($data['completedCount'] === 0)
                                    You have <strong style="color:#1F3B67;">{{ $data['totalCount'] }} mandatory
                                        modules</strong> to complete as part of your <strong
                                        style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> training — let's get
                                    started.
                                @elseif($data['completedCount'] < ceil($data['totalCount'] / 2))
                                    You've made a start on your <strong
                                        style="color:#1F3B67;">{{ $data['jobRole'] }}</strong> training. You've
                                    completed <strong style="color:#1F3B67;">{{ $data['completedCount'] }} of
                                        {{ $data['totalCount'] }}</strong> mandatory modules — keep the momentum going.
                                @else
                                    Great work on your <strong style="color:#1F3B67;">{{ $data['jobRole'] }}</strong>
                                    training. You've completed <strong
                                        style="color:#1F3B67;">{{ $data['completedCount'] }} of
                                        {{ $data['totalCount'] }}</strong> mandatory modules — you're almost there.
                                @endif
                            </p>

                            <!-- Stats Row -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin-bottom:24px; border-radius:10px; overflow:hidden; border:1px solid #dde0e6;">
                                <tr>
                                    <td width="33%"
                                        style="text-align:center; padding:20px 8px; background:#f8f9fb; border-right:1px solid #dde0e6;">
                                        <div style="font-size:32px; font-weight:900; color:#FFB901;">
                                            {{ $data['completedCount'] }}</div>
                                        <div
                                            style="font-size:10px; font-weight:700; color:#FFB901; margin-top:4px; text-transform:uppercase; letter-spacing:0.6px;">
                                            Done</div>
                                    </td>
                                    <td width="33%"
                                        style="text-align:center; padding:20px 8px; background:#f8f9fb; border-right:1px solid #dde0e6;">
                                        <div style="font-size:32px; font-weight:900; color:#1F3B67;">
                                            {{ $data['remainingCount'] }}</div>
                                        <div
                                            style="font-size:10px; font-weight:700; color:#1F3B67; margin-top:4px; text-transform:uppercase; letter-spacing:0.6px;">
                                            Remaining</div>
                                    </td>
                                    <td width="33%" style="text-align:center; padding:20px 8px; background:#f8f9fb;">
                                        <div style="font-size:32px; font-weight:900; color:#7a8499;">
                                            {{ $data['totalCount'] }}</div>
                                        <div
                                            style="font-size:10px; font-weight:700; color:#aab0bc; margin-top:4px; text-transform:uppercase; letter-spacing:0.6px;">
                                            Total</div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Progress Bar -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
                                <tr>
                                    <td
                                        style="font-size:11px; font-weight:700; color:#1F3B67; text-transform:uppercase; letter-spacing:0.6px;">
                                        Overall Progress</td>
                                    <td style="text-align:right; font-size:12px; font-weight:700; color:#FFB901;">
                                        {{ $data['progressPercent'] }}% complete</td>
                                </tr>
                            </table>
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:32px;">
                                <tr>
                                    <td style="background:#f0f2f5; border-radius:999px; height:12px; overflow:hidden;">
                                        <div
                                            style="width:{{ $data['progressPercent'] }}%; background:#FFB901; height:12px; border-radius:999px; min-width:12px;">
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Up next — top 3 only -->
                            @if ($data['pendingModules']->count() > 0)
                                <p
                                    style="margin:0 0 10px; font-size:11px; font-weight:700; color:#1F3B67; text-transform:uppercase; letter-spacing:0.6px;">
                                    Up Next</p>
                                @foreach ($data['pendingModules']->take(3) as $module)
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:8px;">
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
                                                            <a href="{{ $data['loginUrl'] . route('courses.detail', $module->short_name, false) }}"
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
                                @if ($data['pendingModules']->count() > 3)
                                    <p style="margin:8px 0 0; font-size:13px; color:#7a8499; text-align:center;">
                                        + {{ $data['pendingModules']->count() - 3 }} more modules remaining on the
                                        platform
                                    </p>
                                @endif
                            @endif

                            <!-- CTA -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:32px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $data['loginUrl'] }}"
                                            style="display:inline-block; background:#FFB901; color:#1F3B67; padding:15px 44px; text-decoration:none; border-radius:8px; font-size:15px; font-weight:700; letter-spacing:0.3px;">
                                            Continue Training →
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
                                this because you're enrolled in training on our platform.</p>
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
