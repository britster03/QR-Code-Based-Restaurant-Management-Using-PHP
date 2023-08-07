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

// Process the form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $table_number = $_POST['table_number'];

    // Insert into pending_orders table
    $sql_pending_order = "INSERT INTO pendingorderss (customer_name, table_number) VALUES ('$customer_name', '$table_number')";
    if ($conn->query($sql_pending_order) !== TRUE) {
        echo "Error inserting into pending_orders table: " . $conn->error;
        exit;
    }

    // Get the last inserted order_id
    $order_id = $conn->insert_id;

    // Fetch items from the database to update ordered_items
    $sql_items = "SELECT * FROM itemss";
    $result_items = $conn->query($sql_items);

    if ($result_items->num_rows > 0) {
        $total_amount = 0; // Initialize total amount for the order

        while ($item = $result_items->fetch_assoc()) {
            $item_id = $item['item_id'];
            $quantity = $_POST[$item_id];

            if ($quantity > 0) {
                $item_name = $item['item_name'];
                $price = $item['price'];
                $subtotal = $price * $quantity;
                $total_amount += $subtotal; // Add to the total amount

                // Insert into ordered_items table
                $sql_ordered_item = "INSERT INTO ordered_itemss (order_id, item_name, quantity, price, subtotal) 
                                    VALUES ($order_id, '$item_name', $quantity, $price, $subtotal)";
                if ($conn->query($sql_ordered_item) !== TRUE) {
                    echo "Error inserting into ordered_items table: " . $conn->error;
                    exit;
                }
            }
        }

        // Update the total amount in the pending_orders table
        $sql_update_pending_order = "UPDATE pendingorderss SET total_amount = $total_amount WHERE order_id = $order_id";
        if ($conn->query($sql_update_pending_order) !== TRUE) {
            echo "Error updating pending_orders table: " . $conn->error;
            exit;
        }

        echo "Order placed successfully!";
    } else {
        echo "No items available to place an order.";
    }
}

$conn->close();

