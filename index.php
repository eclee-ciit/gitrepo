
<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "root"; // Your MariaDB password
$database = "stdinfo";
$port = 3307;

$conn = mysqli_connect($host, $user, $password, $database, $port);

if (!$conn) {
    echo json_encode(["error" => "Connection failed: " . mysqli_connect_error()]);
    exit;
}


// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn'])) {

    $stdname = trim($_POST['stdname'] ?? '');
    $stdreg = $_POST['stdreg'] ?? '';

    if ($stdname !== '' && $stdreg !== '') {

        $query = "INSERT INTO student (stdname, stdreg) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            echo json_encode(['error' => mysqli_error($conn)]);
            exit;
        }

        mysqli_stmt_bind_param($stmt, "si", $stdname, $stdreg);

        if(mysqli_stmt_execute($stmt)){
            echo json_encode([
                'success' => true,
                'message' => 'Student inserted successfully'
            ]);
        }
        else{
            echo json_encode([
                'success' => false,
                'error' => mysqli_stmt_error($stmt)
            ]);
        }

    } else {
        echo json_encode(['error' => 'Fields should not be empty']);
    }

    exit;
}

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-btn'])) {

    $stdid = (int) $_POST['stdid'];
    $stdname = trim($_POST['stdname'] ?? '');
    $stdreg = $_POST['stdreg'] ?? '';

    if ($stdname !== '' && $stdreg !== '') {

        $query = "UPDATE student SET stdname = ?, stdreg = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param($stmt, "sii", $stdname, $stdreg, $stdid);

        echo json_encode([
            'success' => mysqli_stmt_execute($stmt),
            'message' => 'Student updated successfully'
        ]);

    } else {
        echo json_encode(['error' => 'Fields should not be empty']);
    }

    exit;
}

// DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {

    $stdid = (int) $_POST['delete'];

    $query = "DELETE FROM student WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $stdid);

    echo json_encode([
        'success' => mysqli_stmt_execute($stmt),
        'message' => 'Student deleted successfully'
    ]);

    exit;
}

// READ
if (isset($_GET['read'])) {

    $query = "SELECT * FROM student";
    $readQuery = mysqli_query($conn, $query);

    $students = [];

    while ($rd = mysqli_fetch_assoc($readQuery)) {
        $students[] = $rd;
    }

    echo json_encode($students);
    exit;
}

// GET ONE STUDENT FOR UPDATE
if (isset($_GET['update'])) {

    $stdid = (int) $_GET['update'];

    $query = "SELECT * FROM student WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "i", $stdid);
    mysqli_stmt_execute($stmt);

    $getData = mysqli_stmt_get_result($stmt);
    $rx = mysqli_fetch_assoc($getData);

    echo json_encode($rx);
    exit;
}

// FALLBACK: Runs if no GET or POST condition matched
echo json_encode([
    'success' => false,
    'error' => 'Invalid request parameters',
    'received_get' => $_GET,
    'received_post' => $_POST
]);

mysqli_close($conn);
?>