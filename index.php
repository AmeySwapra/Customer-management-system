<?php
include 'config.php';

// Handle search functionality
$search = $_GET['search'] ?? '';
$query = "SELECT * FROM customers WHERE name LIKE ? OR email LIKE ? ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute(['%' . $search . '%', '%' . $search . '%']);
$customers = $stmt->fetchAll();

// Function to generate CSV data
function generateCSV($customers) {
    $csv = "ID,Name,Email,Phone,Address\n";
    foreach ($customers as $customer) {
        $csv .= "{$customer['id']},{$customer['name']},{$customer['email']},{$customer['phone']},{$customer['address']}\n";
    }
    return $csv;
}

// Download CSV if the button is clicked
if (isset($_POST['download_csv'])) {
    $csvData = generateCSV($customers);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="customers.csv"');
    echo $csvData;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        // Print selected customer details
        function printCustomer(id) {
            var printContent = document.getElementById('customer-' + id).innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = "<html><head><title>Print Customer</title></head><body>" + printContent + "</body></html>";
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Customer Management System</h1>

        <!-- Search Form -->
        <form method="GET" class="mb-3">
            <input type="text" name="search" placeholder="Search by name or email" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-info mt-2">Search</button>
        </form>

        <!-- Download CSV Button -->
        <form method="POST">
            <button type="submit" name="download_csv" class="btn btn-primary mb-3">Download</button>
        </form>

        <a href="create.php" class="btn btn-success mb-3">Add New Customer</a>

        <!-- Customer Table -->
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
                <tr id="customer-<?php echo $customer['id']; ?>">
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                    <td><?php echo htmlspecialchars($customer['address']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $customer['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        <button onclick="printCustomer(<?php echo $customer['id']; ?>);" class="btn btn-secondary">Print</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


