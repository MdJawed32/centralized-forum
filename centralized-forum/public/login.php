<?php
// public/login.php
include('../includes/header.php');
include('../includes/navbar.php');
include('../includes/authentication.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<p class='text-red-500'>Invalid credentials</p>";
    }
}
?>

<div class="container mx-auto p-4 flex-1">
    <h1 class="text-xl mb-4">Login</h1>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" class="border p-2 mb-4 w-full" required>
        <input type="password" name="password" placeholder="Password" class="border p-2 mb-4 w-full" required>
        <button type="submit" class="bg-blue-500 text-white p-2 w-full">Login</button>
    </form>
</div>

<?php include('../includes/footer.php'); ?>

