<?php

$conn = mysqli_connect("localhost", "root", "", "backfeed");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Submit feedback
if (isset($_POST['submit'])) {

    $name = trim($_POST['name']);
    $feed = trim($_POST['feed']);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO feedback (name, feed) VALUES (?, ?)"
    );

    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "ss", $name, $feed);

    if (!mysqli_stmt_execute($stmt)) {
        echo "<p style='color:red;'>Error storing feedback: "
            . htmlspecialchars(mysqli_stmt_error($stmt))
            . "</p>";
    }

    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Feedback Form</title>
</head>

<body>

<h2>Feedback Form</h2>

<form method="post">

    Name:
    <input type="text" name="name" required>
    <br><br>

    Feedback:
    <textarea name="feed" required></textarea>
    <br><br>

    <input type="submit" name="submit" value="Submit">

</form>

<br>

<form method="post">
    <input type="submit" name="view" value="View Stored Feedbacks">
</form>

<hr>

<?php

// View feedback
if (isset($_POST['view'])) {

    echo "<h2>Stored Feedbacks</h2>";

    $result = mysqli_query(
        $conn,
        "SELECT name, feed FROM feedback ORDER BY id DESC"
    );

    if (!$result) {
        die("Error retrieving feedback: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {

            echo "<b>Name:</b> "
                . htmlspecialchars($row['name']);

            echo "<br>";

            echo "<b>Feedback:</b> "
                . htmlspecialchars($row['feed']);

            echo "<hr>";
        }

    } else {

        echo "No feedbacks found.";
    }
}

mysqli_close($conn);

?>

</body>
</html>