<?php
/**
 * EMAIL TEST PAGE
 * ─────────────────────────────────────────────────────────────────
 * Upload this file to your server root (htdocs/) then visit:
 *   https://touchandcarespa.great-site.net/email_test.php
 *
 * DELETE this file after testing for security!
 * ─────────────────────────────────────────────────────────────────
 */

// ── Bootstrap: load .env and autoloader ───────────────────────────
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

Dotenv::createImmutable(__DIR__)->load();

// ── Simple password lock (change if you want) ─────────────────────
$PAGE_PASSWORD = 'test1234';
if (!isset($_GET['key']) || $_GET['key'] !== $PAGE_PASSWORD) {
    http_response_code(403);
    die('<h2>403 Forbidden</h2><p>Add <code>?key=test1234</code> to the URL.</p>');
}

$result   = null;
$log      = [];
$sendTo   = trim($_POST['send_to'] ?? '');
$testType = $_POST['test_type'] ?? 'smtp';
$overridePass = str_replace(' ', '', trim($_POST['override_pass'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($sendTo)) {

    // ── Read .env values ────────────────────────────────────────
    $emailUser  = $_ENV['EMAIL_EMAIL']        ?? '';
    $emailPass  = !empty($overridePass) ? $overridePass : ($_ENV['EMAIL_APP_PASSWORD'] ?? '');

    $log[] = "EMAIL_EMAIL       : " . ($emailUser ?: '❌ NOT SET');
    $log[] = "EMAIL_APP_PASSWORD: " . ($emailPass ? '✅ SET (len=' . strlen($emailPass) . ')' . (!empty($overridePass) ? ' [OVERRIDE]' : ' [from .env]') : '❌ NOT SET');

    if ($testType === 'smtp') {
        // ── Full PHPMailer send test ─────────────────────────────
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug  = SMTP::DEBUG_SERVER;   // full SMTP transcript
            $mail->Debugoutput = function($str, $level) use (&$log) {
                $log[] = htmlspecialchars(trim($str));
            };

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $emailUser;
            $mail->Password   = $emailPass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->Timeout    = 30;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ];

            $appName = $_ENV['APP_NAME'] ?? 'Touch & Care Spa';
            $mail->setFrom($emailUser, $appName);
            $mail->addAddress($sendTo);
            $mail->isHTML(true);
            $mail->Subject = 'Test Email — Touch & Care Spa';
            $mail->Body    = '
                <div style="font-family:Arial,sans-serif;max-width:500px;margin:0 auto;padding:20px;background:#f9f9f9;border-radius:10px;">
                    <h2 style="color:#4CAF50;">✅ Email is Working!</h2>
                    <p>This is a test email from <strong>' . htmlspecialchars($appName) . '</strong>.</p>
                    <p>If you received this, your PHPMailer + Gmail App Password setup is correct.</p>
                    <hr>
                    <small>Sent at: ' . date('Y-m-d H:i:s') . '</small>
                </div>';
            $mail->AltBody = 'Test email from ' . $appName . '. Sent at: ' . date('Y-m-d H:i:s');

            $mail->send();
            $result = ['status' => 'success', 'msg' => '✅ Email sent successfully to ' . htmlspecialchars($sendTo) . '! Check your inbox (and spam folder).'];

        } catch (Exception $e) {
            $result = ['status' => 'error', 'msg' => '❌ Send failed: ' . htmlspecialchars($e->getMessage())];
        }

    } elseif ($testType === 'connection') {
        // ── SMTP connection-only test (no email sent) ────────────
        $log[] = "Testing TCP connection to smtp.gmail.com:465 ...";
        $conn = @fsockopen('smtp.gmail.com', 465, $errno, $errstr, 10);
        if ($conn) {
            fclose($conn);
            $result = ['status' => 'success', 'msg' => '✅ TCP connection to smtp.gmail.com:465 is OPEN — SMTP port is reachable.'];
        } else {
            $result = ['status' => 'error', 'msg' => '❌ Cannot connect to smtp.gmail.com:465. Error: ' . $errstr . ' (' . $errno . ').'];
        }

        $log[] = "Testing TCP connection to smtp.gmail.com:587 ...";
        $conn2 = @fsockopen('smtp.gmail.com', 587, $errno2, $errstr2, 10);
        if ($conn2) {
            fclose($conn2);
            $log[] = "✅ Port 587 is also OPEN";
        } else {
            $log[] = "❌ Port 587 is CLOSED: " . $errstr2;
        }
    }
}

// ── Read latest OTP codes from DB ─────────────────────────────────
$recentCodes = [];
try {
    require_once __DIR__ . '/models/MainModel.php';
    $db = new DatabaseClass();
    $recentCodes = $db->Select(
        "SELECT email, code, user_type, status, updated_at 
         FROM users 
         WHERE code IS NOT NULL AND code != '' 
         ORDER BY updated_at DESC 
         LIMIT 10"
    );
} catch (Exception $e) {
    $recentCodes = [];
    $dbError = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Test — Touch & Care Spa</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #2c3e50; margin-bottom: 5px; }
        .subtitle { color: #e74c3c; font-size: 13px; margin-bottom: 25px; }
        .card { background: #fff; border-radius: 10px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        h2 { font-size: 18px; margin-bottom: 15px; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 8px; }
        label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #555; }
        input[type=email], select { width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; margin-bottom: 12px; }
        .btn { padding: 10px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; }
        .btn-primary { background: #3498db; color: #fff; }
        .btn-secondary { background: #95a5a6; color: #fff; }
        .btn:hover { opacity: 0.9; }
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .log-box { background: #1e1e2e; color: #a6e3a1; font-family: monospace; font-size: 12px; padding: 15px; border-radius: 8px; max-height: 300px; overflow-y: auto; white-space: pre-wrap; word-break: break-all; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { background: #2c3e50; color: #fff; padding: 10px; text-align: left; }
        td { padding: 9px 10px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #f8f9fa; }
        .code-badge { background: #e74c3c; color: #fff; padding: 3px 10px; border-radius: 20px; font-weight: bold; font-family: monospace; font-size: 15px; letter-spacing: 2px; }
        .env-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .env-item { background: #f8f9fa; padding: 10px 14px; border-radius: 6px; font-size: 13px; }
        .env-item span { font-weight: bold; color: #2c3e50; display: block; margin-bottom: 3px; }
        .warn { color: #e67e22; font-size: 13px; padding: 10px; background: #fef9e7; border-radius: 6px; border-left: 4px solid #e67e22; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔧 Email Diagnostics</h1>
    <p class="subtitle">⚠️ DELETE this file after testing! (email_test.php)</p>

    <!-- ENV VALUES CARD -->
    <div class="card">
        <h2>📋 Current .env Values</h2>
        <div class="env-grid">
            <div class="env-item"><span>EMAIL_EMAIL</span><?= htmlspecialchars($_ENV['EMAIL_EMAIL'] ?? '❌ NOT SET') ?></div>
            <div class="env-item"><span>EMAIL_APP_PASSWORD</span><?= isset($_ENV['EMAIL_APP_PASSWORD']) ? '✅ SET (length: ' . strlen($_ENV['EMAIL_APP_PASSWORD']) . ')' : '❌ NOT SET' ?></div>
            <div class="env-item"><span>APP_NAME</span><?= htmlspecialchars($_ENV['APP_NAME'] ?? '❌ NOT SET') ?></div>
            <div class="env-item"><span>URL_HOST</span><?= htmlspecialchars($_ENV['URL_HOST'] ?? '❌ NOT SET') ?></div>
        </div>
    </div>

    <!-- TEST FORM CARD -->
    <div class="card">
        <h2>📨 Send Test Email</h2>

        <?php if ($result): ?>
            <div class="alert alert-<?= $result['status'] === 'success' ? 'success' : 'error' ?>">
                <?= $result['msg'] ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Send test email to:</label>
            <input type="email" name="send_to" value="<?= htmlspecialchars($sendTo) ?>" placeholder="your@email.com" required>

            <label>Test type:</label>
            <select name="test_type">
                <option value="smtp"       <?= $testType === 'smtp'       ? 'selected' : '' ?>>Full SMTP Send (actually sends email)</option>
                <option value="connection" <?= $testType === 'connection' ? 'selected' : '' ?>>Connection Only (checks if ports are open)</option>
            </select>

            <label>Override App Password <span style="font-weight:normal;color:#999;">(optional — paste a NEW App Password here to test without editing .env)</span></label>
            <input type="text" name="override_pass" value="<?= htmlspecialchars($overridePass) ?>" placeholder="e.g. abcd efgh ijkl mnop  (spaces OK, will be stripped)" style="font-family:monospace;">

            <button type="submit" class="btn btn-primary">▶ Run Test</button>
        </form>

        <?php if (!empty($log)): ?>
            <br>
            <label>SMTP Debug Log:</label>
            <div class="log-box"><?= implode("\n", $log) ?></div>
        <?php endif; ?>
    </div>

    <!-- RECENT OTP CODES CARD -->
    <div class="card">
        <h2>🔑 Recent OTP / Verification Codes in Database</h2>
        <p style="font-size:13px;color:#666;margin-bottom:12px;">
            If email isn't working, you can find the code here and enter it manually.
        </p>

        <?php if (!empty($recentCodes)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>OTP Code</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentCodes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><span class="code-badge"><?= htmlspecialchars($row['code']) ?></span></td>
                        <td><?= $row['user_type'] == 5 ? 'Customer' : 'Admin/Staff (' . $row['user_type'] . ')' ?></td>
                        <td><?= htmlspecialchars($row['status'] ?? 'active') ?></td>
                        <td><?= htmlspecialchars($row['updated_at'] ?? 'N/A') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($dbError)): ?>
            <div class="alert alert-error">DB Error: <?= htmlspecialchars($dbError) ?></div>
        <?php else: ?>
            <p style="color:#999;">No pending OTP codes found.</p>
        <?php endif; ?>
    </div>

    <!-- INSTRUCTIONS CARD -->
    <div class="card">
        <h2>📌 How to Use This Page</h2>
        <ol style="padding-left:20px;font-size:14px;line-height:2;">
            <li>First run <strong>Connection Only</strong> test — confirms port 587 is open on InfinityFree.</li>
            <li>If port is open, run <strong>Full SMTP Send</strong> with your email address.</li>
            <li>If email arrives → email system works ✅</li>
            <li>If email fails → check the SMTP debug log above for the exact error.</li>
            <li>
                <strong>While email is broken:</strong> use the OTP codes table above.<br>
                Trigger forgot password / register → come back here → copy the code → paste it on the OTP page.
            </li>
        </ol>
        <br>
        <div class="warn">
            ⚠️ <strong>Delete this file after testing!</strong> It exposes OTP codes and email credentials.
            <br>Remove: <code>htdocs/email_test.php</code> via FileZilla or InfinityFree File Manager.
        </div>
    </div>
</div>
</body>
</html>
