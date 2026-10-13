<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Profile'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:900px; margin:40px auto; padding:20px;">
        <div class="card" style="padding:24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <div>
                    <h1 style="margin:0;">My profile</h1>
                    <p style="margin:6px 0 0; color:#4f4f4f;">Your account information and profile summary.</p>
                </div>
                <a href="/life-network-campus/dashboard" style="padding:10px 14px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Dashboard</a>
            </div>

            <?php if (!empty($errors)): ?>
                <div style="background:#fdeaea; border:1px solid #ebbbbb; padding:12px 14px; border-radius:10px; color:#7a2b2b; margin-bottom:16px;">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/life-network-campus/profile">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div>
                        <label for="name" style="display:block; margin-bottom:8px; font-weight:600;">Name</label>
                        <input id="name" name="name" type="text" value="<?php echo e($user['name'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="email" style="display:block; margin-bottom:8px; font-weight:600;">Email</label>
                        <input id="email" name="email" type="email" value="<?php echo e($user['email'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="university" style="display:block; margin-bottom:8px; font-weight:600;">University</label>
                        <input id="university" name="university" type="text" value="<?php echo e($user['university'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="field_of_study" style="display:block; margin-bottom:8px; font-weight:600;">Field of study</label>
                        <input id="field_of_study" name="field_of_study" type="text" value="<?php echo e($user['field_of_study'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="year_of_study" style="display:block; margin-bottom:8px; font-weight:600;">Year of study</label>
                        <input id="year_of_study" name="year_of_study" type="number" min="1" max="8" value="<?php echo e($user['year_of_study'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="career_interests" style="display:block; margin-bottom:8px; font-weight:600;">Career interests</label>
                        <input id="career_interests" name="career_interests" type="text" value="<?php echo e($user['career_interests'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                </div>

                <div style="margin-top:18px;">
                    <label for="bio" style="display:block; margin-bottom:8px; font-weight:600;">Biography</label>
                    <textarea id="bio" name="bio" rows="5" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px; resize:vertical;"><?php echo e($user['bio'] ?? ''); ?></textarea>
                </div>

                <div style="margin-top:18px; display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div>
                        <label for="interests" style="display:block; margin-bottom:8px; font-weight:600;">Interests</label>
                        <input id="interests" name="interests" type="text" value="<?php echo e($user['interests'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="skills" style="display:block; margin-bottom:8px; font-weight:600;">Skills</label>
                        <input id="skills" name="skills" type="text" value="<?php echo e($user['skills'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                </div>

                <button type="submit" style="margin-top:24px; background:#67462d; color:white; border:none; border-radius:10px; padding:12px 18px; font-weight:700; cursor:pointer;">Save profile</button>
            </form>
        </div>
    </main>
</body>
</html>
