<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success" role="alert">
            Order Success! Your order has been placed successfully.
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h4>Order Summary</h4>
            </div>
            <div class="card-body">
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

                // Get the latest order from the pending_orders table
                $sql_order = "SELECT * FROM pendingorderss ORDER BY order_id DESC LIMIT 1";
                $result_order = $conn->query($sql_order);

                if ($result_order->num_rows > 0) {
                    $order = $result_order->fetch_assoc();
                    $order_id = $order['order_id'];
                    $customer_name = $order['customer_name'];
                    $table_number = $order['table_number'];
                    $total_amount = $order['total_amount'];

                    echo '<p>Order ID: ' . $order_id . '</p>';
                    echo '<p>Customer Name: ' . $customer_name . '</p>';
                    echo '<p>Table Number: ' . $table_number . '</p>';

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
                        echo '<a href="order.php" class="btn btn-primary">Order More</a>';
                    } else {
                        echo '<p>No ordered items found for this order ID.</p>';
                    }
                } else {
                    echo '<p>No pending order found.</p>';
                }

                $conn->close();
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Pay Bill</h4>
            </div>
            <div class="card-body text-center">
                <p>Use the QR code below to pay your bill through Google Pay.</p>
                <!-- Replace "your_google_pay_link" with your actual Google Pay payment link -->
                <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=your_google_pay_link&choe=UTF-8" alt="Google Pay QR Code">
                <p><small>Scan this QR code with Google Pay to complete your payment.</small></p>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
