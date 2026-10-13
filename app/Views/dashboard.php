<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Dashboard'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:1200px; margin:0 auto; padding:20px;">
        <header class="card" style="padding:20px 24px; display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
            <div>
                <h1 style="margin:0;">Dashboard</h1>
                <p style="margin:6px 0 0; color:#4f4f4f;">Welcome back, <?php echo e($user['name'] ?? 'Student'); ?>.</p>
            </div>
            <div style="display:flex; gap:12px; align-items:center;">
                <a href="/life-network-campus/public/profile" style="padding:10px 14px; border-radius:10px; background:#f4efe9; text-decoration:none; color:#1b1b1b; font-weight:600;">Profile</a>
                <a href="/life-network-campus/public/settings" style="padding:10px 14px; border-radius:10px; background:#f4efe9; text-decoration:none; color:#1b1b1b; font-weight:600;">Settings</a>
                <form method="post" action="/life-network-campus/public/logout">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                    <button type="submit" style="padding:10px 14px; border:none; border-radius:10px; background:#67462d; color:white; font-weight:600; cursor:pointer;">Logout</button>
                </form>
            </div>
        </header>

        <section style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
            <div class="card" style="padding:18px;">
                <p style="color:#5a5a5a; margin:0 0 8px;">Goals</p>
                <h2 style="margin:0; font-size:2rem;">0</h2>
            </div>
            <div class="card" style="padding:18px;">
                <p style="color:#5a5a5a; margin:0 0 8px;">Projects</p>
                <h2 style="margin:0; font-size:2rem;">0</h2>
            </div>
            <div class="card" style="padding:18px;">
                <p style="color:#5a5a5a; margin:0 0 8px;">Connections</p>
                <h2 style="margin:0; font-size:2rem;">0</h2>
            </div>
            <div class="card" style="padding:18px;">
                <p style="color:#5a5a5a; margin:0 0 8px;">Notifications</p>
                <h2 style="margin:0; font-size:2rem;"><?php echo e($notifications ?? 0); ?></h2>
            </div>
        </section>

        <section style="margin-top:24px; display:grid; grid-template-columns:1.2fr 0.8fr; gap:20px;">
            <div class="card" style="padding:20px;">
                <h3 style="margin-top:0;">Your next priority</h3>
                <p style="color:#4f4f4f; line-height:1.7; margin-bottom:0;">
                    This dashboard is connected to the authenticated user record and is ready for the goal, project, and community modules that will be added in later phases.
                </p>
            </div>
            <div class="card" style="padding:20px;">
                <h3 style="margin-top:0;">Quick profile snapshot</h3>
                <p style="margin:0; color:#4f4f4f; line-height:1.7;">
                    <strong>University:</strong> <?php echo e($user['university'] ?? 'Not set'); ?><br>
                    <strong>Field:</strong> <?php echo e($user['field_of_study'] ?? 'Not set'); ?><br>
                    <strong>Interests:</strong> <?php echo e($user['interests'] ?? 'Not set'); ?>
                </p>
            </div>
        </section>
    </main>
</body>
</html>
