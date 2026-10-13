<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Portfolio item'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:760px; margin:0 auto; padding:32px 20px;">
        <a href="/life-network-campus/public/portfolio" style="display:inline-block; margin-bottom:18px; color:#67462d; font-weight:600; text-decoration:none;">Back to portfolio</a>
        <div class="card" style="padding:26px;">
            <h1 style="margin-top:0;"><?php echo e($title ?? 'Portfolio item'); ?></h1>
            <?php if (!empty($errors)): ?>
                <div style="background:#fdeaea; border:1px solid #ebbbbb; color:#7a2b2b; padding:12px 14px; border-radius:10px; margin-bottom:18px;">
                    <?php foreach ($errors as $error): ?><div><?php echo e($error); ?></div><?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php $itemData = $item ?? []; ?>
            <?php $isEditing = !empty($itemData['id']); ?>
            <?php $action = $isEditing ? '/life-network-campus/public/portfolio/' . (int) $itemData['id'] . '/update' : '/life-network-campus/public/portfolio/store'; ?>
            <form method="post" action="<?php echo e($action); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                <div style="display:grid; gap:18px;">
                    <div>
                        <label for="title" style="display:block; margin-bottom:8px; font-weight:600;">Title</label>
                        <input id="title" name="title" required value="<?php echo e($itemData['title'] ?? ''); ?>" style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #d8ccbb; border-radius:10px;">
                    </div>
                    <div>
                        <label for="description" style="display:block; margin-bottom:8px; font-weight:600;">Description</label>
                        <textarea id="description" name="description" rows="6" required style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #d8ccbb; border-radius:10px; resize:vertical;"><?php echo e($itemData['description'] ?? ''); ?></textarea>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                        <div>
                            <label for="item_type" style="display:block; margin-bottom:8px; font-weight:600;">Type</label>
                            <select id="item_type" name="item_type" style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #d8ccbb; border-radius:10px;">
                                <?php foreach (['Project', 'Achievement', 'Certificate', 'Research', 'Publication', 'Other'] as $type): ?>
                                    <option value="<?php echo e($type); ?>" <?php echo (($itemData['item_type'] ?? 'Project') === $type) ? 'selected' : ''; ?>><?php echo e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="visibility" style="display:block; margin-bottom:8px; font-weight:600;">Visibility</label>
                            <select id="visibility" name="visibility" style="width:100%; box-sizing:border-box; padding:12px; border:1px solid #d8ccbb; border-radius:10px;">
                                <option value="public" <?php echo (($itemData['visibility'] ?? 'public') === 'public') ? 'selected' : ''; ?>>Public</option>
                                <option value="private" <?php echo (($itemData['visibility'] ?? '') === 'private') ? 'selected' : ''; ?>>Private</option>
                            </select>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:flex-end; gap:10px;">
                        <a href="/life-network-campus/public/portfolio" style="padding:12px 16px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Cancel</a>
                        <button type="submit" style="padding:12px 18px; border:0; border-radius:10px; background:#67462d; color:#fff; font-weight:600; cursor:pointer;"><?php echo $isEditing ? 'Save changes' : 'Add item'; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
