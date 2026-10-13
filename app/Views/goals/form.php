<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Goal'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:760px; margin:0 auto; padding:32px 20px;">
        <a href="/life-network-campus/public/goals" style="display:inline-block; margin-bottom:18px; color:#67462d; font-weight:600; text-decoration:none;">← Back to goals</a>

        <div class="card" style="padding:26px;">
            <h1 style="margin-top:0; margin-bottom:20px;"><?php echo e($title ?? 'Goal'); ?></h1>

            <?php if (!empty($errors)): ?>
                <div style="background:#fdeaea; border:1px solid #ebbbbb; color:#7a2b2b; padding:12px 14px; border-radius:10px; margin-bottom:18px;">
                    <ul style="margin:0; padding-left:18px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php $goalData = $goal ?? []; ?>
            <?php $isEditing = !empty($goalData['id']); ?>
            <?php $action = $isEditing ? '/life-network-campus/public/goals/' . (int) $goalData['id'] . '/update' : '/life-network-campus/public/goals/store'; ?>

            <form method="post" action="<?php echo e($action); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">

                <div style="display:grid; gap:18px;">
                    <div>
                        <label for="title" style="display:block; margin-bottom:8px; font-weight:600;">Goal title</label>
                        <input id="title" name="title" value="<?php echo e($goalData['title'] ?? ''); ?>" required style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                    </div>

                    <div>
                        <label for="description" style="display:block; margin-bottom:8px; font-weight:600;">Description</label>
                        <textarea id="description" name="description" rows="5" required style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box; resize:vertical; "><?php echo e($goalData['description'] ?? ''); ?></textarea>
                    </div>

                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:18px;">
                        <div>
                            <label for="category" style="display:block; margin-bottom:8px; font-weight:600;">Category</label>
                            <select id="category" name="category" style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                                <?php $categories = ['Personal Growth', 'Relationships', 'Career', 'Learning', 'Contribution']; ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo e($category); ?>" <?php echo (($goalData['category'] ?? 'Learning') === $category) ? 'selected' : ''; ?>><?php echo e($category); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="status" style="display:block; margin-bottom:8px; font-weight:600;">Status</label>
                            <select id="status" name="status" style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                                <?php $statuses = ['Not Started', 'In Progress', 'Completed', 'Paused']; ?>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo e($status); ?>" <?php echo (($goalData['status'] ?? 'Not Started') === $status) ? 'selected' : ''; ?>><?php echo e($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="target_date" style="display:block; margin-bottom:8px; font-weight:600;">Target date</label>
                        <input id="target_date" type="date" name="target_date" value="<?php echo e($goalData['target_date'] ?? ''); ?>" style="width:100%; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:8px;">
                        <a href="/life-network-campus/public/goals" style="padding:12px 16px; border-radius:10px; background:#f4efe9; color:#1b1b1b; text-decoration:none; font-weight:600;">Cancel</a>
                        <button type="submit" style="padding:12px 18px; border:none; border-radius:10px; background:#67462d; color:#fff; font-weight:600; cursor:pointer;">
                            <?php echo $isEditing ? 'Save changes' : 'Create goal'; ?>
                        </button>
                    </div>
                </div>
            </form>

            <?php if ($isEditing && !empty($goalData['steps'])): ?>
                <div style="margin-top:30px; border-top:1px solid #ece1d6; padding-top:22px;">
                    <h2 style="margin-top:0; margin-bottom:16px;">Milestones</h2>
                    <div style="display:grid; gap:12px; margin-bottom:18px;">
                        <?php foreach ($goalData['steps'] as $step): ?>
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; padding:12px; border:1px solid #e8ded1; border-radius:10px; background:#faf8f6;">
                                <span style="text-decoration:<?php echo ((int) $step['is_completed'] === 1) ? 'line-through' : 'none'; ?>;">
                                    <?php echo e($step['title']); ?>
                                </span>
                                <form method="post" action="/life-network-campus/public/goals/<?php echo (int) $goalData['id']; ?>/steps/<?php echo (int) $step['id']; ?>/toggle">
                                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                                    <button type="submit" style="padding:8px 10px; border:none; border-radius:8px; background:#ddd0c3; cursor:pointer; font-weight:600;">
                                        <?php echo ((int) $step['is_completed'] === 1) ? 'Undo' : 'Complete'; ?>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <form method="post" action="/life-network-campus/public/goals/<?php echo (int) $goalData['id']; ?>/steps/add">
                    <input type="hidden" name="csrf_token" value="<?php echo e(csrfToken()); ?>">
                    <label for="step_title" style="display:block; margin-bottom:8px; font-weight:600;">Add milestone</label>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <input id="step_title" name="step_title" placeholder="Add a milestone" style="flex:1; min-width:220px; padding:12px; border:1px solid #d8ccbb; border-radius:10px; box-sizing:border-box;">
                        <button type="submit" style="padding:12px 16px; border:none; border-radius:10px; background:#67462d; color:#fff; font-weight:600; cursor:pointer;">Add</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
