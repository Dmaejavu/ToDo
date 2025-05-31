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