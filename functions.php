<?php
function connectDB() {
    $host = "MySQL-8.2";
    $username = "root";
    $password = "";
    $database = "pizzadb";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    return $conn;
}

function getPizzas($conn) {
    $sql = "SELECT * FROM pizzas";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getContacts($conn) {
    $sql = "SELECT * FROM contacts LIMIT 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getSectionTitle($conn, $section_name) {
    $sql = "SELECT value FROM sections WHERE name = '$section_name'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['value'];
    } else {
        return "Заголовок не найден";
    }
}

function updateSectionTitle($conn, $section_name, $new_value) {
    $new_value = $conn->real_escape_string($new_value);
    $sql = "UPDATE sections SET value = '$new_value' WHERE name = '$section_name'";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


function getReviews($conn) {
    $sql = "SELECT * FROM reviews ORDER BY created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addReview($conn, $name, $comment) {
    $name = $conn->real_escape_string($name);
    $comment = $conn->real_escape_string($comment);
    $sql = "INSERT INTO reviews (name, comment) VALUES ('$name', '$comment')";
    return $conn->query($sql);
}

function updateReview($conn, $id, $name, $comment) {
    $name = $conn->real_escape_string($name);
    $comment = $conn->real_escape_string($comment);
    $sql = "UPDATE reviews SET name='$name', comment='$comment' WHERE id=$id";
    return $conn->query($sql);
}
?>

