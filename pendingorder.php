<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .view-btn {
            background-color: black;
            color: white;
            padding: 4px 8px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            border-radius: 4px;
            cursor: pointer;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 16px;
            margin-bottom: 20px;
        }

        .table-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .table-container .card {
            flex: 0 0 calc(33.3333% - 20px);
            max-width: calc(33.3333% - 20px);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Orders</h1>

    <div class="table-container">
        <?php
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "orders";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch pending orders from the database
        $sql = "SELECT * FROM pendingorderss WHERE status = 'pending' ORDER BY table_number DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card">';
                echo '<table>';
                echo '<tr><th>Order ID</th><th>Table Number</th><th>Customer Name</th><th>Total Amount</th><th>Status</th></tr>';
                echo '<tr>';
                echo '<td>' . $row["order_id"] . '</td>';
                echo '<td>' . $row["table_number"] . '</td>';
                echo '<td>' . $row["customer_name"] . '</td>';
                echo '<td>$' . $row["total_amount"] . '</td>';
                echo '<td>' . $row["status"] . '</td>';
                echo '<td><a class="view-btn" href="view_order.php?order_id=' . $row["order_id"] . '">View Order</a></td>'; 
                echo '</tr>';
                echo '</table>';
                echo '</div>';
            }
        } else {
            echo "No pending orders.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>

</body>
</html>
