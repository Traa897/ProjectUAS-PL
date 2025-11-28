<?php
// generate_password.php - Tool untuk generate password hash

echo "<h2>Password Generator</h2>";
echo "<hr>";

// Generate password untuk admin123
$password_admin = 'admin123';
$hash_admin = password_hash($password_admin, PASSWORD_DEFAULT);

echo "<h3>Admin Password:</h3>";
echo "Password: <strong>$password_admin</strong><br>";
echo "Hash: <code>$hash_admin</code><br><br>";

// Generate password untuk user123
$password_user = 'user123';
$hash_user = password_hash($password_user, PASSWORD_DEFAULT);

echo "<h3>User Password:</h3>";
echo "Password: <strong>$password_user</strong><br>";
echo "Hash: <code>$hash_user</code><br><br>";

// Verify test
echo "<hr>";
echo "<h3>Verification Test:</h3>";
echo "Admin password_verify: " . (password_verify('admin123', $hash_admin) ? '✅ TRUE' : '❌ FALSE') . "<br>";
echo "User password_verify: " . (password_verify('user123', $hash_user) ? '✅ TRUE' : '❌ FALSE') . "<br>";
?>