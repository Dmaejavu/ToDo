<?php


session_start();
include_once '../../modules/db_connection.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../loginPages/login.php");
    exit();
}
// GETTING USER NAME, TITLE AND CONTENT FROM DATABASE
$get_name = $connection->prepare("SELECT userName AS fullName FROM user WHERE userID = ?");
$get_name->bind_param("i", $_SESSION['userID']);
$get_name->execute();
$name_result = $get_name->get_result();
$user_name = $name_result->fetch_assoc()['fullName'];

$get_name->close();

$get_title = $connection->prepare("SELECT title AS todoTitle FROM todo WHERE userID = ?");
$get_title->bind_param("i", $_SESSION['userID']);
$get_title->execute();
$title_result = $get_title->get_result();
$title = $title_result->fetch_assoc()['todoTitle'];

$get_title->close();

$get_content = $connection->prepare("SELECT content as todoContent FROM todo WHERE userID = ?");
$get_content->bind_param("i", $_SESSION['userID']);
$get_content->execute();
$content_result = $get_content->get_result();
$content = $content_result->fetch_assoc()['todoContent'];

$get_content->close();

// ADDING A NEW TODO TO THE DATABASE
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $priority = $_POST['priority'];

    $stmt = $connection->prepare("INSERT INTO todo (userID, title, content, priority) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['userID'], $title, $content, $priority);

    if ($stmt->execute()) {
        header("Location: homepage.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
// FETCHING TODOS FROM THE DATABASE
$get_lowTodos = $connection->prepare("SELECT tdID, title, content, time_created FROM todo WHERE userID = ? AND priority = 'Low' AND status = 'Ongoing'");
$get_lowTodos->bind_param("i", $_SESSION['userID']);
$get_lowTodos->execute();
$low_todo_list = $get_lowTodos->get_result();
$get_lowTodos->close();

$get_highTodos = $connection->prepare("SELECT tdID, title, content, time_created FROM todo WHERE userID = ? AND priority = 'High' AND status = 'Ongoing'");
$get_highTodos->bind_param("i", $_SESSION['userID']);
$get_highTodos->execute();
$high_todo_list = $get_highTodos->get_result();
$get_highTodos->close();

$get_doneTodos = $connection->prepare("SELECT tdID, title, content, time_created FROM todo WHERE userID = ? AND status = 'Done'");
$get_doneTodos->bind_param("i", $_SESSION['userID']);
$get_doneTodos->execute();
$done_todo_list = $get_doneTodos->get_result();
$get_doneTodos->close();

// UPDATING TODOS TO DONE STATUS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    if (!empty($_POST['tdID'])) {
        foreach ($_POST['tdID'] as $todoID) {
            $todoID = intval($todoID); 
            $update_stmt = $connection->prepare("UPDATE todo SET status = 'Done'  WHERE tdID = ? AND userID = ?");
            $update_stmt->bind_param("ii", $todoID, $_SESSION['userID']);
            $update_stmt->execute();
            $update_stmt->close();
            echo "<script> alert('ToDo list updated'); window.location.href ='homepage.php' </script>";
        }
        exit();
    } else {
        echo "<script> alert('No ToDo selected'); </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!empty($_POST['tdID'])) {
        foreach ($_POST['tdID'] as $todoID) {
            $todoID = intval($todoID); 
            $delete_stmt = $connection->prepare("DELETE FROM todo WHERE tdID = ? AND userID = ?");
            $delete_stmt->bind_param("ii", $todoID, $_SESSION['userID']);
            $delete_stmt->execute();
            $delete_stmt->close();
            echo "<script> alert('ToDo/s Deleted'); window.location.href ='homepage.php' </script>";
        }
        exit();
    } else {
        echo "<script> alert('No ToDo selected'); </script>";
    }
}


?>