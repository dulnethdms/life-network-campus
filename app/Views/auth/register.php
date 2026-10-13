<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Register'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:760px; margin:50px auto; padding:20px;">
        <div class="card" style="padding:30px;">
            <h1 style="margin-top:0;">Create your account</h1>
            <?php if (!empty($errors)): ?>
                <div style="background:#fdeaea; border:1px solid #ebbbbb; padding:12px 14px; border-radius:10px; color:#7a2b2b; margin-bottom:16px;">
                    <?php foreach ($errors as $error): ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/life-network-campus/register">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div>
                        <label for="name" style="display:block; margin-bottom:8px; font-weight:600;">Full name</label>
                        <input id="name" name="name" type="text" value="<?php echo e($values['name'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="email" style="display:block; margin-bottom:8px; font-weight:600;">Email</label>
                        <input id="email" name="email" type="email" value="<?php echo e($values['email'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="password" style="display:block; margin-bottom:8px; font-weight:600;">Password</label>
                        <input id="password" name="password" type="password" required style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="university" style="display:block; margin-bottom:8px; font-weight:600;">University</label>
                        <input id="university" name="university" type="text" value="<?php echo e($values['university'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="field_of_study" style="display:block; margin-bottom:8px; font-weight:600;">Field of study</label>
                        <input id="field_of_study" name="field_of_study" type="text" value="<?php echo e($values['field_of_study'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="year_of_study" style="display:block; margin-bottom:8px; font-weight:600;">Year of study</label>
                        <input id="year_of_study" name="year_of_study" type="number" min="1" max="8" value="<?php echo e($values['year_of_study'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                </div>

                <div style="margin-top:18px;">
                    <label for="bio" style="display:block; margin-bottom:8px; font-weight:600;">Bio</label>
                    <textarea id="bio" name="bio" rows="4" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px; resize:vertical;"><?php echo e($values['bio'] ?? ''); ?></textarea>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-top:18px;">
                    <div>
                        <label for="interests" style="display:block; margin-bottom:8px; font-weight:600;">Interests</label>
                        <input id="interests" name="interests" type="text" value="<?php echo e($values['interests'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                    <div>
                        <label for="skills" style="display:block; margin-bottom:8px; font-weight:600;">Skills</label>
                        <input id="skills" name="skills" type="text" value="<?php echo e($values['skills'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                    </div>
                </div>

                <div style="margin-top:18px;">
                    <label for="career_interests" style="display:block; margin-bottom:8px; font-weight:600;">Career interests</label>
                    <input id="career_interests" name="career_interests" type="text" value="<?php echo e($values['career_interests'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d9d1c7; border-radius:10px;">
                </div>

                <button type="submit" style="margin-top:24px; width:100%; background:#67462d; color:white; border:none; border-radius:10px; padding:12px; font-size:1rem; font-weight:700; cursor:pointer;">Create account</button>
            </form>

            <p style="text-align:center; margin-top:18px; color:#4d4d4d;">
                Already have an account? <a href="/life-network-campus/login" style="color:#67462d; font-weight:600;">Log in</a>
            </p>
        </div>
    </main>
</body>
</html>
