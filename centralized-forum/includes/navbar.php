<nav class="bg-gray-800 p-4">
    <ul class="flex space-x-4">
        <li><a href="index.php" class="text-white">Home</a></li>
        <li><a href="register.php" class="text-white">Register</a></li>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <li><a href="login.php" class="text-white">Login</a></li>
            <li><a href="dashboard.php" class="text-white">Dashboard</a></li>
        <?php else: ?>
            <li><a href="logout.php" class="text-white">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>
