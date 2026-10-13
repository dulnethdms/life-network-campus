<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Life Network Campus'); ?></title>
    <link rel="stylesheet" href="/life-network-campus/public/assets/css/styles.css">
</head>
<body>
    <main style="max-width:1100px; margin:0 auto; padding:40px 20px;">
        <section class="card" style="padding:48px; display:grid; grid-template-columns:1.3fr 0.9fr; gap:32px; align-items:center;">
            <div>
                <span style="display:inline-block; background:#efe0d1; color:#6d3b12; border-radius:999px; padding:8px 14px; font-size:12px; font-weight:bold; letter-spacing:.08em; text-transform:uppercase;">Student growth community</span>
                <h1 style="font-size:3rem; margin:20px 0 12px;">Build a meaningful life, not just a profile.</h1>
                <p style="font-size:1.08rem; line-height:1.7; color:#4f4f4f; margin-bottom:24px;">
                    Life Network Campus helps students connect goals, skills, people, communities, and opportunities in one growth-focused platform.
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a class="card" href="/life-network-campus/public/register" style="padding:12px 18px; text-decoration:none; color:#fff; background:#67462d; border-radius:12px; font-weight:600;">Create account</a>
                    <a class="card" href="/life-network-campus/public/login" style="padding:12px 18px; text-decoration:none; color:#1b1b1b; background:#f4efe9; border-radius:12px; font-weight:600;">Log in</a>
                </div>
            </div>
            <div style="background:#f5efe8; border:1px solid #e8dfd2; border-radius:20px; padding:24px;">
                <h3 style="margin-top:0;">The core journey</h3>
                <ul style="padding-left:18px; line-height:2; color:#3f3f3f;">
                    <li>Identify a goal</li>
                    <li>Find relevant people</li>
                    <li>Join a community</li>
                    <li>Build with a project</li>
                    <li>Document achievements</li>
                </ul>
            </div>
        </section>
    </main>
</body>
</html>
