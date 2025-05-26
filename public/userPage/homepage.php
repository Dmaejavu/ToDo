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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body>
    <div class="modalBG" id="modalBG">
        <div class="modal-container">
            <h2>Add a ToDo</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="add"> <!-- For Database adding -->
                <div class="add-container">
                    <label for="title">Title:</label>
                    <input type="hidden" name="action" value="add">
                    <input type="text" placeholder="Title" name="title" id="title" required>
                </div>
                <div class="add-container">
                    <label for="content">Content:</label>
                    <textarea placeholder="Enter content here" id="content" name="content" row="5" required></textarea>
                </div>
                <div class="add-container">
                    <label for="priority" >Priority:</label>
                    <select name="priority" id="priority">
                        <option value="High">High</option>
                        <option value="Low">Low</option>
                    </select>
                </div>
                <button type="submit">
                    SUBMIT
                </button>
            </form>
        </div>
    </div>
    <div class="mainDIV">
        <header>
            <h1>Welcome <?php echo htmlspecialchars($user_name)?></h1>
            <img src="../../assets/icons/icons8-shutdown-96.png" alt="Logout" onClick="window.location.href='../loginPages/logout.php'">
        </header>

        <div class="wrap-wrapper">
            <form method="POST" action="" onsubmit="return confirmationMsg();"> 
                <div class="wrapper-sections">
                    <div class="sectionHeader">
                        <h1>High Priority</h1>
                        <div class="right-sectionHeader">
                            <div class="doneBtn">
                                <input type="hidden" name="action" value="update"> <!-- For Database updating -->
                                <button id="doneBtn" type="submit" onClick="">
                                    MARK AS DONE
                                </button>
                            </div>
                            <img id="getIcon" src="../../assets/icons/icons8-edit-64.png" alt="Edit" onClick="showEdit(), changeIcon()"> 
                        </div>
                    </div>
                    <div class="wrapper">
                        <?php while ($row = $high_todo_list->fetch_assoc()):  ?>
                        <div id="container" class="container">
                            <div class="title-container">
                                <div class="titleDetails">
                                    <h1><?php echo htmlspecialchars($row['title'])?></h1>
                                    <small><?php echo htmlspecialchars($row['time_created']) ?></small>
                                </div>
                                <div class="editDIV">
                                    <label for="editLabel" name="editLabel" class="editLabel">Edit:</label>
                                    <input class="editCheckbox" type="checkbox" name="tdID[]" value="<?php echo $row['tdID'] ?>">
                                </div>
                            </div>
                            <div class="content-container">
                                <p><?php echo htmlspecialchars($row['content']) ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <div style="margin-bottom: 32px;" class="wrapper-sections">
                    <div style="border-bottom: none;" class="sectionHeader">
                        <h1>Low Priority</h1>
                    </div>
                    <div class="wrapper">
                        <?php while ($row = $low_todo_list->fetch_assoc()):?>
                        <div id="container" class="container">
                            <div class="title-container">
                                <div class="titleDetails">
                                    <h1><?php echo htmlspecialchars($row['title']) ?></h1>
                                    <small><?php echo htmlspecialchars($row['time_created']) ?></small>
                                </div>
                                <div class="editDIV">
                                    <label for="editLabel" name="editLabel" class="editLabel">Edit:</label>
                                    <input class="editCheckbox" type="checkbox" name="tdID[]" value="<?php echo $row['tdID'] ?>">
                                </div>
                            </div>
                            <div class="content-container">
                                <p><?php echo htmlspecialchars($row['content']) ?></p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </form>

            <form method="POST" action="" onsubmit="return confirmationMsgDel();">
                <div style="margin-bottom: 32px;" class="wrapper-sections">
                    <div class="sectionHeader">
                        <h1>Already Finished</h1>
                        <div class="right-sectionHeader">
                            <div class="deleteBtn">
                                <input type="hidden" name="action" value="delete"> <!-- For Database deletion -->
                                <button id="delBtn" type="submit" onClick="">
                                    DELETE
                                </button>
                            </div>
                            <img id="getDelIcon" src="../../assets/icons/icons8-trash-96.png" alt="Edit" onClick="showDone(), changeIconDel()">
                        </div>
                    </div>
                    <div class="wrapper">
                        <?php while ($row = $done_todo_list->fetch_assoc()):?>
                        <div class="done-container">
                            <div class="title-container">
                                <h1><?php echo htmlspecialchars($row['title']) ?></h1>
                                <div class="editDIV">
                                    <label for="delLabel" name="delLabel" class="delLabel">Edit:</label>
                                    <input class="checkbox" type="checkbox" name="tdID[]" value="<?php echo $row['tdID'] ?>">
                                </div>
                            </div>
                            <div class="content-container">
                                <p><?php echo htmlspecialchars($row['content']) ?></p>
                            </div>
                            </div>
                        <?php endwhile; ?>
                        </div>
                    </div>
                </div> 
            </form>

        </div> <!-- wrap-wrapper -->
    </div> <!-- mainDIV -->
                            
    <div class="addTodo">
        <button id="shwoosh" onClick="showModal()">
            <img src="../../assets/icons/icons8-plus-24.png" alt="Add Todo">
        </button>
    </div>
    <script>
        function confirmationMsg() {
            return confirm("Are you sure you want to mark the selected ToDo/s as done?");
        }

        function confirmationMsgDel() {
            return confirm("Are you sure you want to delete the selected ToDo/s?");
        }

        function showModal(){
            const modal = document.getElementById("modalBG");
            
            if(modal.style.display === "flex"){
                modal.style.display = "none";
            } else {
                modal.style.display = "flex";
                
            }

            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            }
        }

        function showEdit() {
            const editChckBoxes = document.querySelectorAll(".editCheckbox");
            const doneBtn = document.getElementById("doneBtn");
            const conts = document.querySelectorAll(".container");
            const editLabels = document.querySelectorAll(".editLabel");
            
            editChckBoxes.forEach(editChckBox => {
                editChckBox.checked = false; 
                if (editChckBox.style.visibility === "visible") {
                    editChckBox.style.visibility = "hidden";
                    editChckBox.style.transition = "visibility 0.15s ease-in-out";
                    editChckBox.style.animation = "fadeOut-fLeft-tRight 0.15s ease-in-out";

                } else {
                    editChckBox.style.visibility = "visible";
                    editChckBox.style.animation = "fadeIn-fRight-tLeft 0.15s ease-in-out";
                }
            }); 

                doneBtn.style.animation = "none"; 
                if (doneBtn.style.visibility === "visible") {
                    doneBtn.style.visibility = "hidden";
                    doneBtn.style.transition = "visibility 0.15s ease-in-out";
                    doneBtn.style.animation = "fadeOut-fLeft-tRight 0.15s ease-in-out";
                } else {
                    doneBtn.style.visibility = "visible";
                    doneBtn.style.animation = "fadeIn-fRight-tLeft 0.15s ease-in-out";
                }

                conts.forEach(cont => {
                    if (cont.classList.contains("glow")) {
                        cont.classList.remove("glow");
                    } else {
                        cont.classList.add("glow");
                    }
                });

                editLabels.forEach(editLabel => {
                    if (editLabel.style.visibility === "visible") {
                        editLabel.style.visibility = "hidden";
                        editLabel.style.transition = "visibility 0.15s ease-in-out";
                        editLabel.style.animation = "fadeOut-fLeft-tRight 0.15s ease-in-out";
                    } else {
                        editLabel.style.visibility = "visible";
                        editLabel.style.animation = "fadeIn-fRight-tLeft 0.15s ease-in-out";
                    }
                });
            
        }

        function showDone() {
            const checkboxes = document.querySelectorAll(".checkbox");
            const delBtn = document.getElementById("delBtn");
            const conts = document.querySelectorAll(".done-container");
            const delLabels = document.querySelectorAll(".delLabel");

            checkboxes.forEach(checkbox => {
                checkbox.checked = false; 
                if (checkbox.style.visibility === "visible") {
                    checkbox.style.visibility = "hidden";
                    checkbox.style.transition = "visibility 0.15s ease-in-out";
                    checkbox.style.animation = "fadeOut-fLeft-tRight 0.15s ease-in-out";
                } else {
                    checkbox.style.visibility = "visible";
                    checkbox.style.animation = "fadeIn-fRight-tLeft 0.15s ease-in-out";
                }
            });

            delBtn.style.animation = "none"; 
            if (delBtn.style.visibility === "visible") {
                delBtn.style.visibility = "hidden";
                delBtn.style.transition = "visibility 0.15s ease-in-out";
                delBtn.style.animation = "fadeOut-fLeft-tRight 0.15s ease-in-out";
            } else {
                delBtn.style.visibility = "visible";
                delBtn.style.animation = "fadeIn-fRight-tLeft 0.15s ease-in-out";
            }

            conts.forEach(cont => {
                if (cont.classList.contains("glow")) {
                    cont.classList.remove("glow");
                } else {
                    cont.classList.add("glow");
                }
            });

            delLabels.forEach(delLabel => {
                if (delLabel.style.visibility === "visible") {
                    delLabel.style.visibility = "hidden";
                    delLabel.style.transition = "visibility 0.15s ease-in-out";
                    delLabel.style.animation = "fadeOut-fLeft-tRight 0.15s ease-in-out";
                } else {
                    delLabel.style.visibility = "visible";
                    delLabel.style.animation = "fadeIn-fRight-tLeft 0.15s ease-in-out";
                }
            });
        }

        function changeIcon(){
            const icon = document.getElementById("getIcon");
            icon.style.animation = "none"; 

            if (icon.src.includes("edit")) {
                icon.src = "../../assets/icons/icons8-close-100.png";
                icon.style.animation = "rotateCW 0.15s ease-in-out";
            } else {
                icon.src = "../../assets/icons/icons8-edit-64.png";
                icon.style.animation = "rotateCCW 0.15s ease-in-out";
            }
        }

        function changeIconDel(){
            const icon = document.getElementById("getDelIcon");
            icon.style.animation = "none";

            if(icon.src.includes("trash")) {
                icon.src = "../../assets/icons/icons8-close-100.png";
                icon.style.animation = "rotateCW 0.15s ease-in-out";
            } else {
                icon.src = "../../assets/icons/icons8-trash-96.png";
                icon.style.animation = "rotateCCW 0.15s ease-in-out";
            }
        }
    </script>

</body>
</html>