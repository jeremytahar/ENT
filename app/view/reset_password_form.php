<?php
if (!isset($_GET['token'])) {
    die('Invalid token.');
}
$token = htmlspecialchars($_GET['token']);
?>

<h2>Reset Password</h2>
<form method="POST" action="index.php?action=update_password">
    <input type="hidden" name="token" value="<?= $token ?>">
    <label for="password">Enter new password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Update Password</button>
</form>
