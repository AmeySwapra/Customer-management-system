<?php
include 'config.php';

// Fetch the customer data based on ID
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->execute([$id]);
$customer = $stmt->fetch();

// Initialize errors array
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Server-side validation
    if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $errors['name'] = 'Please enter a valid name with letters and spaces only.';
    }

    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors['phone'] = 'Please enter a valid 10-digit phone number.';
    }

    // If no validation errors, update the customer record
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $address, $id]);

        // Redirect to index.php
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Client-side validation
        function validateForm() {
            let name = document.getElementById('name').value;
            let phone = document.getElementById('phone').value;
            let namePattern = /^[a-zA-Z\s]+$/;
            let phonePattern = /^[0-9]{10}$/;
            let error = '';

            if (!namePattern.test(name)) {
                error += 'Name should only contain letters and spaces.\n';
            }

            if (!phonePattern.test(phone)) {
                error += 'Phone number should be 10 digits long.\n';
            }

            if (error) {
                alert(error);
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Edit Customer</h1>
        <form method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <small class="text-danger"><?php echo $errors['name']; ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
                <?php if (isset($errors['phone'])): ?>
                    <small class="text-danger"><?php echo $errors['phone']; ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" name="address" id="address"><?php echo htmlspecialchars($customer['address']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Update Customer</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

