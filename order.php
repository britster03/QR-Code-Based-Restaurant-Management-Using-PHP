<!DOCTYPE html>
<html>
<head>
    <title>GoOrder - Food Order Page</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: green; /* Blue color - can be customized */
            --dark-bg: #343a40; /* Dark background color - can be customized */
            --light-bg: #f8f9fa; /* Light background color - can be customized */
            --text-color: #333; /* Text color - can be customized */
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-color);
        }

        .container {
            max-width: 800px;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .item-image {
            width: 100px;
            height: 100px;
            border-radius: 15px;
            object-fit: cover;
            margin-right: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<?php require 'partials/_nav.php' ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Food Order Page</h1>

        <div class="card">
            <div class="card-body">
                <form id="orderForm" action="process_order.php" method="post">
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

                    // Get the latest table number from the database
                    $sql = "SELECT table_number FROM pendingorderss ORDER BY table_number DESC LIMIT 1";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $table_number = $row["table_number"] + 1; // Increment the latest table number
                    } else {
                        $table_number = 1; // If no orders yet, start with table number 1
                    }

                    // Fetch items from the database
                    $sql = "SELECT * FROM itemss";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $item_id = $row["item_id"];
                            $item_name = $row["item_name"];
                            $price = $row["price"];
                            $image_url = "images/" . $row["image"]; // Assuming image file names are stored in the "image" column

                            echo '<div class="form-group">';
                            echo '<img class="item-image" src="' . $image_url . '" alt="' . $item_name . '">';
                            echo '<label>' . $item_name . ' - $' . $price . '</label>';
                            echo '<div class="input-group">';
                            echo '<span class="input-group-btn">';
                            echo '<button type="button" class="btn btn-primary btn-number" data-type="minus" data-field="' . $item_id . '">';
                            echo '-';
                            echo '</button>';
                            echo '</span>';
                            echo '<input type="number" name="' . $item_id . '" class="form-control input-number" min="0" value="0">';
                            echo '<span class="input-group-btn">';
                            echo '<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="' . $item_id . '">';
                            echo '+';
                            echo '</button>';
                            echo '</span>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No items available</p>';
                    }

                    $conn->close();
                    ?>
                    <div class="form-group">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <input type="hidden" name="table_number" value="<?php echo $table_number; ?>"> <!-- Auto-assign table number -->
                    <input type="submit" value="Submit Order" class="btn btn-success">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS, Font Awesome, and Custom JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        // JavaScript code for increment and decrement buttons
        $(document).ready(function () {
            $('.btn-number').click(function (e) {
                e.preventDefault();
                var fieldName = $(this).attr('data-field');
                var type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type === 'minus') {
                        if (currentVal > 0) {
                            input.val(currentVal - 1).change();
                        }
                    } else if (type === 'plus') {
                        input.val(currentVal + 1).change();
                    }
                } else {
                    input.val(1);
                }
            });
        });
    </script>
</body>
</html>
