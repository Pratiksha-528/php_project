<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: reset_login.php"); // Redirect to login page if not logged in or not an admin
    exit();
}

$conn = new mysqli("localhost", "root", "", "employee");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database
$query = "SELECT id, username, role, status FROM info"; 
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions a {
            margin-right: 10px;
        }

        .logout {
            margin-top: 20px;
            background-color: #ff5c5c;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .logout:hover {
            background-color: #e94c4c;
        }

        .register-link {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .register-link:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>

<!-- Display the list of users -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo ucfirst($row['role']); ?></td>
                <td>
                    <?php echo $row['status'] == 1 ? 'Active' : 'Blocked'; ?>
                </td>
                <td class="actions">
                    <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Link to reset_register.php for registering new users -->
<a href="reset_register.php" class="register-link">Register New User</a>

<!-- Logout link -->
<a href="logout.php" class="logout">Logout</a>

</body>
</html>

<?php
$conn->close();
?>
