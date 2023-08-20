<!DOCTYPE html>
<html>

<head>
    <title>User-Service Predictions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .header {
        background-color: #fd7e14;
        color: #fff;
        padding: 40px 0;
        text-align: center;
    }

    .container {
        margin-top: 20px;
    }

    .profile-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .profile-card h2 {
        margin-bottom: 20px;
        color: #343a40;
        font-weight: bold;
        font-size: 24px;
    }

    .table {
        background-color: #f8f9fa;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 100%;
        overflow: hidden;
    }

    .table th,
    .table td {
        padding: 15px;
        text-align: center;
        vertical-align: middle;
        border-top: none;
        border-bottom: 1px solid #dee2e6;
        color: #343a40;
    }

    .table thead th {
        background-color: #e9ecef;
        border-bottom: 2px solid #dee2e6;
        font-weight: bold;
        color: #343a40;
    }

    .table tbody tr:hover {
        background-color: #f2f2f2;
    }
    </style>
</head>

<body>
    <div class="header">
        <h1>Service Predictions</h1>

    </div>
    <div class="container">
        <div class="profile-card">
            <h2>Prediction Results</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Most Visited Service</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $db_connection = mysqli_connect("localhost", "root", "", "smartsociety1");
                    if (!$db_connection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    $query = "SELECT * FROM users";
                    $result = mysqli_query($db_connection, $query);

                    while ($user = mysqli_fetch_assoc($result)) {
                        $user_id = $user['u_id'];
                        $user_name = $user['name'];

                        $log_query = "SELECT service_id, COUNT(*) as count FROM user_service_log WHERE user_id = $user_id GROUP BY service_id ORDER BY count DESC LIMIT 1";
                        $log_result = mysqli_query($db_connection, $log_query);

                        if ($log_result && mysqli_num_rows($log_result) > 0) {
                            $log_row = mysqli_fetch_assoc($log_result);
                            $most_visited_service_id = $log_row['service_id'];

                            $service_query = "SELECT type FROM services WHERE s_id = $most_visited_service_id";
                            $service_result = mysqli_query($db_connection, $service_query);

                            if ($service_result && mysqli_num_rows($service_result) > 0) {
                                $service_row = mysqli_fetch_assoc($service_result);
                                $most_visited_service = $service_row['type'];

                                $total_visits = $log_row['count'];

                                $total_entries_query = "SELECT COUNT(*) as total_entries FROM user_service_log WHERE user_id = $user_id";
                                $total_entries_result = mysqli_query($db_connection, $total_entries_query);

                                if ($total_entries_result) {
                                    $total_entries_row = mysqli_fetch_assoc($total_entries_result);
                                    $total_entries = $total_entries_row['total_entries'];

                                    $percentage = ($total_visits / $total_entries) * 100;
                                    $rounded_percentage = round($percentage); // Round the percentage value

                                    echo '<tr>';
                                    echo '<td>' . $user_id . '</td>';
                                    echo '<td>' . $user_name . '</td>';
                                    echo '<td>' . $most_visited_service . '</td>';
                                    echo '<td>' . $rounded_percentage . '%</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                    }

                    mysqli_close($db_connection);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>