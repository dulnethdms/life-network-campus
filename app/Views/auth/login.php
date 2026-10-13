<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Log in'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:500px; margin:80px auto; padding:20px;">
        <div class="card" style="padding:30px;">
            <h1 style="margin-top:0;">Welcome back</h1>
            <?php if (!empty($errors)): ?>
                <div style="background:#fdeaea; border:1px solid #ebbbbb; padding:12px 14px; border-radius:10px; color:#7a2b2b; margin-bottom:16px;">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/life-network-campus/public/login">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">

                <div style="margin-bottom:16px;">
                    <label for="email" style="display:block; margin-bottom:8px; font-weight:600;">Email</label>
                    <input id="email" name="email" type="email" value="<?php echo e($values['email'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                </div>

                <div style="margin-bottom:20px;">
                    <label for="password" style="display:block; margin-bottom:8px; font-weight:600;">Password</label>
                    <input id="password" name="password" type="password" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                </div>

                <button type="submit" style="width:100%; background:#67462d; color:white; border:none; border-radius:10px; padding:12px; font-size:1rem; font-weight:700; cursor:pointer;">Log in</button>
            </form>

            <p style="margin-top:18px; text-align:center; color:#4d4d4d;">
                New here? <a href="/life-network-campus/public/register" style="color:#67462d; font-weight:600;">Create an account</a>
            </p>
        </div>
    </main>
</body>
</html>
