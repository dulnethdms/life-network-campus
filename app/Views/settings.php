<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Settings'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:760px; margin:40px auto; padding:20px;">
        <div class="card" style="padding:24px; margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h1 style="margin:0;">Account settings</h1>
                    <p style="margin:6px 0 0; color:#4f4f4f;">Manage your password and account status.</p>
                </div>
                <a href="/life-network-campus/public/dashboard" style="padding:10px 14px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Dashboard</a>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div style="background:#fdeaea; border:1px solid #ebbbbb; padding:12px 14px; border-radius:10px; color:#7a2b2b; margin-bottom:16px;">
                <?php foreach ($errors as $error): ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background:#eaf6ee; border:1px solid #bad9c2; padding:12px 14px; border-radius:10px; color:#214d38; margin-bottom:16px;">
                <?php echo e($success); ?>
            </div>
        <?php endif; ?>

        <div class="card" style="padding:24px; margin-bottom:20px;">
            <h2 style="margin-top:0;">Change password</h2>
            <form method="post" action="/life-network-campus/public/settings/password">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">

                <div style="margin-bottom:16px;">
                    <label for="current_password" style="display:block; margin-bottom:8px; font-weight:600;">Current password</label>
                    <input id="current_password" name="current_password" type="password" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                </div>

                <div style="margin-bottom:16px;">
                    <label for="new_password" style="display:block; margin-bottom:8px; font-weight:600;">New password</label>
                    <input id="new_password" name="new_password" type="password" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                </div>

                <div style="margin-bottom:16px;">
                    <label for="confirm_password" style="display:block; margin-bottom:8px; font-weight:600;">Confirm new password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                </div>

                <button type="submit" style="background:#67462d; color:white; border:none; border-radius:10px; padding:12px 18px; font-weight:700; cursor:pointer;">Update password</button>
            </form>
        </div>

        <div class="card" style="padding:24px; background:#fff7f7; border-color:#f0d3d3;">
            <h2 style="margin-top:0; color:#7a2b2b;">Deactivate account</h2>
            <p style="color:#4f4f4f; line-height:1.7;">This will disable your account and sign you out. You can re-activate it later only through a new account registration flow in the MVP.</p>
            <form method="post" action="/life-network-campus/public/settings/deactivate" onsubmit="return confirm('Are you sure you want to deactivate your account?');">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <button type="submit" style="background:#a43c3c; color:white; border:none; border-radius:10px; padding:12px 18px; font-weight:700; cursor:pointer;">Deactivate account</button>
            </form>
        </div>
    </main>
</body>
</html>
