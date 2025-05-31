<?php 
include '../../modules/pageFunctions.php';
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
    <script src='../../assets/style.js'></script>

</body>
</html>