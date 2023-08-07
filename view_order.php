<!DOCTYPE html>
<html>
<head>
    <title>View Order Details</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Order Summary</h1>

        <form method="GET" class="mb-4">
            <div class="form-group">
                <label for="order_id">Enter Order ID:</label>
                <input type="number" name="order_id" class="form-control" required>
            </div>
            <input type="submit" value="View Order" class="btn btn-primary">
        </form>

        <?php
        // Database connection details (Replace with your own credentials)
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "orders";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch order details for the selected order ID
        if (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];

            $sql_pending_orders = "SELECT * FROM pendingorderss WHERE order_id = $order_id";
            $result_pending_orders = $conn->query($sql_pending_orders);

            if ($result_pending_orders->num_rows > 0) {
                $order = $result_pending_orders->fetch_assoc();
                $customer_name = $order['customer_name'];
                $table_number = $order['table_number'];
                $total_amount = $order['total_amount'];

                echo '<div class="card mb-3">';
                echo '<div class="card-header">Order ID: ' . $order_id . '</div>';
                echo '<div class="card-body">';
                echo '<p>Customer Name: ' . $customer_name . '</p>';
                echo '<p>Table Number: ' . $table_number . '</p>';
                echo '<h5 class="card-title">Ordered Items</h5>';

                // Fetch ordered items for the current order_id from ordered_items table
                $sql_ordered_items = "SELECT * FROM ordered_itemss WHERE order_id = $order_id";
                $result_ordered_items = $conn->query($sql_ordered_items);

                if ($result_ordered_items->num_rows > 0) {
                    echo '<table class="table table-bordered">';
                    echo '<thead class="thead-light">';
                    echo '<tr><th>Item Name</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($item = $result_ordered_items->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $item["item_name"] . '</td>';
                        echo '<td>' . $item["quantity"] . '</td>';
                        echo '<td>$' . $item["price"] . '</td>';
                        echo '<td>$' . $item["subtotal"] . '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '<p class="font-weight-bold">Total Amount: $' . $total_amount . '</p>';
                } else {
                    echo '<p>No ordered items found for this order ID.</p>';
                }

                echo '</div>';
                echo '</div>';
            } else {
                echo '<p>No pending order found for this order ID.</p>';
            }
        }

        $conn->close();
        ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Add custom CSS for order view -->
    <style>
        .card {
            margin-bottom: 20px;
        }
    </style>
</body>
</html>
