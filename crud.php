<?php
$servername = "localhost";
$username = "root";
$password = "";  
$dbname = "vnce_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insertProduct($conn, $name, $description, $price, $quantity) {
    $stmt = $conn->prepare("INSERT INTO products (name, Description, Price, Quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $name, $description, $price, $quantity);
    $stmt->execute();
    $stmt->close();
}

function deleteProduct($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

function updateProduct($conn, $id, $name, $description, $price, $quantity) {
    $stmt = $conn->prepare("UPDATE products SET name = ?, Description = ?, Price = ?, Quantity = ? WHERE id = ?");
    $stmt->bind_param("ssdii", $name, $description, $price, $quantity, $id);
    $stmt->execute();
    $stmt->close();
}

function getProducts($conn) {
    return $conn->query("SELECT * FROM products");
}

if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $quantity = $_POST['Quantity'];
    insertProduct($conn, $name, $description, $price, $quantity);
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    deleteProduct($conn, $id);
}

if (isset($_POST['edit'])) {
    $id = $_POST['ID'];
    $name = $_POST['name'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $quantity = $_POST['Quantity'];
    updateProduct($conn, $id, $name, $description, $price, $quantity);
}

$result = getProducts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple CRUD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f7f7f7;
        }
        .action-links {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Management</h1>
        <form action="crud.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="Name" required>
            </div>
            <div class="form-group">
                <label for="Description">Description:</label>
                <input type="text" name="Description" id="Description" placeholder="Description">
            </div>
            <div class="form-group">
                <label for="Price">Price:</label>
                <input type="text" name="Price" id="Price" placeholder="Price">
            </div>
            <div class="form-group">
                <label for="Quantity">Quantity:</label>
                <input type="text" name="Quantity" id="Quantity" placeholder="Quantity">
            </div>
            <button type="submit" name="create">Create Product</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['ID']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['Description']); ?></td>
                <td><?php echo htmlspecialchars($row['Price']); ?></td>
                <td><?php echo htmlspecialchars($row['Quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['Created_at']); ?></td>
                <td><?php echo htmlspecialchars($row['Updated_at']); ?></td>
                <td class="action-links">
                    <a href="crud.php?delete=<?php echo htmlspecialchars($row['ID']); ?>">Delete</a>
                    <form action="crud.php" method="POST" style="display:inline;">
                        <input type="hidden" name="ID" value="<?php echo htmlspecialchars($row['ID']); ?>">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                        <input type="text" name="Description" value="<?php echo htmlspecialchars($row['Description']); ?>">
                        <input type="text" name="Price" value="<?php echo htmlspecialchars($row['Price']); ?>">
                        <input type="text" name="Quantity" value="<?php echo htmlspecialchars($row['Quantity']); ?>">
                        <button type="submit" name="edit">Edit</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
