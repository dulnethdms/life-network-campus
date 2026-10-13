<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Goals'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:1100px; margin:0 auto; padding:20px;">
        <header style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:24px;">
            <div>
                <h1 style="margin:0;">My goals</h1>
                <p style="margin:6px 0 0; color:#4f4f4f;">Track meaningful progress and turn goals into action.</p>
            </div>
            <div style="display:flex; gap:12px;">
                <a href="/life-network-campus/public/dashboard" style="padding:10px 14px; border-radius:10px; background:#f4efe9; text-decoration:none; color:#1b1b1b; font-weight:600;">Dashboard</a>
                <a href="/life-network-campus/public/goals/new" style="padding:10px 14px; border-radius:10px; background:#67462d; text-decoration:none; color:#fff; font-weight:600;">New goal</a>
            </div>
        </header>

        <?php if ($error): ?>
            <div style="background:#fdeaea; border:1px solid #ebbbbb; color:#7a2b2b; padding:12px 14px; border-radius:10px; margin-bottom:20px;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($goals)): ?>
            <div class="card" style="padding:32px; text-align:center;">
                <h2 style="margin-top:0;">No goals yet</h2>
                <p style="color:#4f4f4f; line-height:1.7; margin-bottom:0;">Create your first goal to start connecting it to skills, communities, projects, and opportunities.</p>
            </div>
        <?php else: ?>
            <div style="display:grid; gap:18px;">
                <?php foreach ($goals as $goal): ?>
                    <?php $progress = $goal['progress'] ?? ['percent' => 0, 'completed' => 0, 'total' => 0]; ?>
                    <div class="card" style="padding:20px;">
                        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                            <div>
                                <h2 style="margin:0 0 6px; font-size:1.5rem;"><?php echo e($goal['title']); ?></h2>
                                <p style="margin:0; color:#4f4f4f;"><?php echo e($goal['category']); ?> · <?php echo e($goal['status']); ?></p>
                            </div>
                            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                <a href="/life-network-campus/public/goals/<?php echo (int) $goal['id']; ?>/edit" style="padding:10px 12px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Edit</a>
                                <form method="post" action="/life-network-campus/public/goals/<?php echo (int) $goal['id']; ?>/delete" onsubmit="return confirm('Delete this goal?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                    <button type="submit" style="padding:10px 12px; border:none; border-radius:10px; background:#a43c3c; color:#fff; font-weight:600; cursor:pointer;">Delete</button>
                                </form>
                            </div>
                        </div>

                        <p style="margin:16px 0; line-height:1.7; color:#444;">
                            <?php echo nl2br(e($goal['description'])); ?>
                        </p>

                        <div style="margin-bottom:16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; font-size:0.95rem; color:#4f4f4f;">
                                <span>Progress</span>
                                <strong><?php echo (int) $progress['percent']; ?>%</strong>
                            </div>
                            <div style="height:10px; background:#eee; border-radius:999px; overflow:hidden;">
                                <div style="height:100%; width:<?php echo (int) $progress['percent']; ?>%; background:#67462d; border-radius:999px;"></div>
                            </div>
                        </div>

                        <div style="display:grid; gap:8px;">
                            <?php foreach (($goal['steps'] ?? []) as $step): ?>
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; padding:10px 12px; border:1px solid #e7dfd4; border-radius:10px; background:#faf8f6;">
                                    <div style="display:flex; align-items:center; gap:10px; flex:1;">
                                        <form method="post" action="/life-network-campus/public/goals/<?php echo (int) $goal['id']; ?>/steps/<?php echo (int) $step['id']; ?>/toggle">
                                            <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                            <button type="submit" style="padding:6px 10px; border:1px solid #d0c4b7; background:<?php echo ((int) $step['is_completed'] === 1) ? '#dff2e9' : '#fff'; ?>; border-radius:8px; cursor:pointer;">
                                                <?php echo ((int) $step['is_completed'] === 1) ? 'Done' : 'Mark done'; ?>
                                            </button>
                                        </form>
                                        <span style="text-decoration:<?php echo ((int) $step['is_completed'] === 1) ? 'line-through' : 'none'; ?>; color:#1d1d1d;">
                                            <?php echo e($step['title']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($goal['target_date'])): ?>
                            <p style="margin-top:16px; margin-bottom:0; color:#4f4f4f;">
                                Target date: <?php echo e($goal['target_date']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
