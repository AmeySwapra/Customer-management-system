<?php
include 'config.php';


$search = $_GET['search'] ?? '';
$query = "SELECT * FROM customers WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['%' . $search . '%', '%' . $search . '%']);
$customers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Customer Management System</h1>
        
        <form method="GET" class="mb-3">
            <input type="text" name="search" placeholder="Search by name or email" class="form-control">
            <button type="submit" class="btn btn-info mt-2">Search</button>
        </form>

        <a href="create.php" class="btn btn-success mb-3">Add New Customer</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo $customer['name']; ?></td>
                    <td><?php echo $customer['email']; ?></td>
                    <td><?php echo $customer['phone']; ?></td>
                    <td><?php echo $customer['address']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $customer['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

