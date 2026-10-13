<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Projects'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:1100px; margin:0 auto; padding:20px;">
        <header style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
            <div>
                <h1 style="margin:0;">My projects</h1>
                <p style="margin:6px 0 0; color:#4f4f4f;">Organize academic, creative, and collaborative work in one place.</p>
            </div>
            <div style="display:flex; gap:12px; align-items:center;">
                <a href="/life-network-campus/public/dashboard" style="padding:10px 14px; border-radius:10px; background:#f4efe9; text-decoration:none; color:#1b1b1b; font-weight:600;">Dashboard</a>
                <a href="/life-network-campus/public/projects/new" style="padding:10px 14px; border-radius:10px; background:#67462d; text-decoration:none; color:#fff; font-weight:600;">New project</a>
            </div>
        </header>

        <?php if ($error): ?>
            <div style="background:#fdeaea; border:1px solid #ebbbbb; color:#7a2b2b; padding:12px 14px; border-radius:10px; margin-bottom:20px;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($projects)): ?>
            <div class="card" style="padding:32px; text-align:center;">
                <h2 style="margin-top:0;">No projects yet</h2>
                <p style="margin:0; color:#4f4f4f; line-height:1.7;">Create your first project to capture your ideas, milestones, and momentum.</p>
            </div>
        <?php else: ?>
            <div style="display:grid; gap:18px;">
                <?php foreach ($projects as $project): ?>
                    <div class="card" style="padding:20px;">
                        <div style="display:flex; justify-content:space-between; gap:14px; align-items:flex-start; flex-wrap:wrap;">
                            <div>
                                <h2 style="margin:0 0 6px; font-size:1.5rem;"> <?php echo e($project['title']); ?> </h2>
                                <p style="margin:0; color:#4f4f4f;"> <?php echo e($project['category']); ?> · <?php echo e($project['status']); ?> </p>
                            </div>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <a href="/life-network-campus/public/projects/<?php echo (int) $project['id']; ?>/edit" style="padding:10px 12px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Edit</a>
                                <form method="post" action="/life-network-campus/public/projects/<?php echo (int) $project['id']; ?>/delete" onsubmit="return confirm('Delete this project?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                    <button type="submit" style="padding:10px 12px; border:none; border-radius:10px; background:#a43c3c; color:#fff; font-weight:600; cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </div>

                        <p style="margin:16px 0; line-height:1.7; color:#444;">
                            <?php echo nl2br(e($project['description'])); ?>
                        </p>

                        <?php if (!empty($project['deadline'])): ?>
                            <p style="margin:0; color:#4f4f4f;">
                                Deadline: <?php echo e($project['deadline']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
