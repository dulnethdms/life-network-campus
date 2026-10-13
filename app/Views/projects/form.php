<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Project'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:760px; margin:0 auto; padding:32px 20px;">
        <a href="/life-network-campus/public/projects" style="display:inline-block; margin-bottom:18px; color:#67462d; font-weight:600; text-decoration:none;">← Back to projects</a>

        <div class="card" style="padding:26px;">
            <h1 style="margin-top:0; margin-bottom:20px;"><?php echo e($title ?? 'Project'); ?></h1>

            <?php if (!empty($errors)): ?>
                <div style="background:#fdeaea; border:1px solid #ebbbbb; color:#7a2b2b; padding:12px 14px; border-radius:10px; margin-bottom:18px;">
                    <ul style="margin:0; padding-left:18px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php $projectData = $project ?? []; ?>
            <?php $isEditing = !empty($projectData['id']); ?>
            <?php $action = $isEditing ? '/life-network-campus/public/projects/' . (int) $projectData['id'] . '/update' : '/life-network-campus/public/projects/store'; ?>

            <form method="post" action="<?php echo e($action); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">

                <div style="display:grid; gap:18px;">
                    <div>
                        <label for="title" style="display:block; margin-bottom:8px; font-weight:600;">Project title</label>
                        <input id="title" name="title" value="<?php echo e($projectData['title'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                    </div>

                    <div>
                        <label for="description" style="display:block; margin-bottom:8px; font-weight:600;">Description</label>
                        <textarea id="description" name="description" rows="5" required style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box; resize:vertical; "><?php echo e($projectData['description'] ?? ''); ?></textarea>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px;">
                        <div>
                            <label for="category" style="display:block; margin-bottom:8px; font-weight:600;">Category</label>
                            <select id="category" name="category" style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                                <?php $categories = ['Academic', 'Research', 'Startup', 'Community', 'Creative', 'Career']; ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo e($category); ?>" <?php echo (($projectData['category'] ?? 'Academic') === $category) ? 'selected' : ''; ?>><?php echo e($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="status" style="display:block; margin-bottom:8px; font-weight:600;">Status</label>
                            <select id="status" name="status" style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                                <?php $statuses = ['Open', 'In Progress', 'Completed', 'On Hold']; ?>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo e($status); ?>" <?php echo (($projectData['status'] ?? 'Open') === $status) ? 'selected' : ''; ?>><?php echo e($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="deadline" style="display:block; margin-bottom:8px; font-weight:600;">Deadline</label>
                        <input id="deadline" type="date" name="deadline" value="<?php echo e($projectData['deadline'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:8px;">
                        <a href="/life-network-campus/public/projects" style="padding:12px 16px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Cancel</a>
                        <button type="submit" style="padding:12px 18px; border:none; border-radius:10px; background:#67462d; color:#fff; font-weight:600; cursor:pointer;">
                            <?php echo $isEditing ? 'Save changes' : 'Create project'; ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
