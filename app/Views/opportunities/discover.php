<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Discover opportunities'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:1100px; margin:0 auto; padding:20px;">
        <header style="display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
            <div>
                <h1 style="margin:0;">Discover opportunities</h1>
                <p style="margin:6px 0 0; color:#4f4f4f;">Find open opportunities, save promising ones, and keep applications moving.</p>
            </div>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="/life-network-campus/public/opportunities" style="padding:10px 14px; border-radius:10px; background:#f4efe9; text-decoration:none; color:#1b1b1b; font-weight:600;">My opportunities</a>
                <a href="/life-network-campus/public/dashboard" style="padding:10px 14px; border-radius:10px; background:#67462d; text-decoration:none; color:#fff; font-weight:600;">Dashboard</a>
            </div>
        </header>

        <?php if (empty($opportunities)): ?>
            <div class="card" style="padding:32px; text-align:center;">
                <h2 style="margin-top:0;">No open opportunities yet</h2>
                <p style="margin:0; color:#4f4f4f; line-height:1.7;">Check back later or create an opportunity for other students to discover.</p>
            </div>
        <?php else: ?>
            <div style="display:grid; gap:18px;">
                <?php foreach ($opportunities as $opportunity): ?>
                    <article class="card" style="padding:20px;">
                        <div style="display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap; align-items:flex-start;">
                            <div>
                                <h2 style="margin:0 0 6px; font-size:1.45rem;"><?php echo e($opportunity['title']); ?></h2>
                                <p style="margin:0; color:#4f4f4f;"><?php echo e($opportunity['opportunity_type']); ?> · <?php echo e($opportunity['category']); ?> · Posted by <?php echo e($opportunity['creator_name']); ?></p>
                            </div>
                            <span style="padding:6px 10px; border-radius:999px; background:#edf3e8; color:#315b2d; font-weight:600;">Open</span>
                        </div>

                        <p style="margin:16px 0; line-height:1.7; color:#444;"><?php echo nl2br(e($opportunity['description'])); ?></p>

                        <div style="display:flex; gap:12px; flex-wrap:wrap; color:#4f4f4f; margin-bottom:16px;">
                            <?php if (!empty($opportunity['organization_name'])): ?><span><strong>Organization:</strong> <?php echo e($opportunity['organization_name']); ?></span><?php endif; ?>
                            <?php if (!empty($opportunity['deadline'])): ?><span><strong>Deadline:</strong> <?php echo e($opportunity['deadline']); ?></span><?php endif; ?>
                        </div>

                        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:flex-start;">
                            <form method="post" action="/life-network-campus/public/opportunities/<?php echo (int) $opportunity['id']; ?>/<?php echo !empty($opportunity['is_saved']) ? 'unsave' : 'save'; ?>">
                                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                <button type="submit" style="padding:10px 14px; border:0; border-radius:10px; background:#f4efe9; color:#1b1b1b; font-weight:600; cursor:pointer;"><?php echo !empty($opportunity['is_saved']) ? 'Unsave' : 'Save opportunity'; ?></button>
                            </form>

                            <?php if (!empty($opportunity['has_applied'])): ?>
                                <form method="post" action="/life-network-campus/public/opportunities/<?php echo (int) $opportunity['id']; ?>/withdraw">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                    <button type="submit" style="padding:10px 14px; border:0; border-radius:10px; background:#a43c3c; color:#fff; font-weight:600; cursor:pointer;">Withdraw application</button>
                                </form>
                            <?php else: ?>
                                <form method="post" action="/life-network-campus/public/opportunities/<?php echo (int) $opportunity['id']; ?>/apply" style="display:flex; gap:8px; flex:1; min-width:280px;">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                    <input name="message" placeholder="Short application message" maxlength="1000" style="flex:1; min-width:180px; padding:10px; border:1px solid #d8ccbb; border-radius:10px;">
                                    <button type="submit" style="padding:10px 14px; border:0; border-radius:10px; background:#67462d; color:#fff; font-weight:600; cursor:pointer;">Apply</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
