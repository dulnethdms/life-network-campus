<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'My portfolio'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:1100px; margin:0 auto; padding:20px;">
        <header style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
            <div>
                <h1 style="margin:0;">My portfolio</h1>
                <p style="margin:6px 0 0; color:#4f4f4f;">Keep a concise record of the work and achievements you want to carry forward.</p>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="/life-network-campus/public/dashboard" style="padding:10px 14px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Dashboard</a>
                <a href="/life-network-campus/public/portfolio/new" style="padding:10px 14px; border-radius:10px; background:#67462d; color:#fff; text-decoration:none; font-weight:600;">Add item</a>
            </div>
        </header>

        <?php if (empty($items)): ?>
            <div class="card" style="padding:32px; text-align:center;">
                <h2 style="margin-top:0;">Your portfolio is empty</h2>
                <p style="margin:0; color:#4f4f4f; line-height:1.7;">Add a project, certificate, research effort, or achievement to make your progress visible.</p>
            </div>
        <?php else: ?>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:18px;">
                <?php foreach ($items as $item): ?>
                    <article class="card" style="padding:20px;">
                        <div style="display:flex; justify-content:space-between; gap:12px; align-items:flex-start;">
                            <div>
                                <p style="margin:0 0 7px; color:#67462d; font-size:.9rem; font-weight:700; text-transform:uppercase;"> <?php echo e($item['item_type']); ?> </p>
                                <h2 style="margin:0; font-size:1.35rem;"> <?php echo e($item['title']); ?> </h2>
                            </div>
                            <span style="padding:5px 8px; border-radius:999px; background:#f4efe9; color:#4f4f4f; font-size:.85rem;"> <?php echo e(ucfirst($item['visibility'])); ?> </span>
                        </div>
                        <p style="margin:16px 0; color:#444; line-height:1.7;"> <?php echo nl2br(e($item['description'])); ?> </p>
                        <div style="display:flex; gap:10px;">
                            <a href="/life-network-campus/public/portfolio/<?php echo (int) $item['id']; ?>/edit" style="padding:9px 12px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Edit</a>
                            <form method="post" action="/life-network-campus/public/portfolio/<?php echo (int) $item['id']; ?>/delete" onsubmit="return confirm('Delete this portfolio item?');">
                                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                <button type="submit" style="padding:9px 12px; border:0; border-radius:10px; background:#a43c3c; color:#fff; font-weight:600; cursor:pointer;">Delete</button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
