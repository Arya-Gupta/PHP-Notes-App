<?php
    $insert=false;
    $update=false;
    $delete=false;
    //Creating connection with database
    $servername="localhost";
    $username="root";
    $password="";
    $database="notes";
    $conn=mysqli_connect($servername,$username,$password,$database);
    if(!$conn)
    {
        die("Connection failed.<br>ERROR-".mysqli_connect_error());
    }

    if(isset($_GET['delete']))
    {
        $sno=$_GET['delete'];
        $sql="DELETE FROM `notes` WHERE `sno`=$sno";
        $result=mysqli_query($conn,$sql);
    }

    if ($_SERVER['REQUEST_METHOD']=='POST')
    {
        if(isset($_POST['snoEdit']))
        {
            //update the record
            $sno=$_POST['snoEdit'];
            $title=$_POST['titleEdit'];
            $description=$_POST['descriptionEdit'];
            $sql="UPDATE `notes` SET `title` = '$title', `description` = '$description' WHERE `notes`.`sno` = $sno";
            $result=mysqli_query($conn,$sql);
            if($result)
            {
                $update=true;
            }
            else
            {
                echo "Error! We are currently facing some issues.<br>";
            }
        }
        else
        {
            //insert record
            $title=$_POST['title'];
            $description=$_POST['description'];
            $sql="INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
            $result=mysqli_query($conn,$sql);
            if($result)
            {
                $insert=true;
            }
            else
            {
                echo "Error! We are currently facing some issues.<br>";
            }
        }
    }         
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
        <title>PHP Notes App</title>
        <link rel="stylesheet" href="//cdn.datatables.net/1.11.1/css/jquery.dataTables.min.css">
    </head>

    <body>
        <!----------Edit modal----------> 
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="/notesapp/notes.php" method="post">
                            <!-- If hidden input is set, we understand that we want to perform updation, else submission. -->
                            <input type="hidden" name="snoEdit" id="snoEdit">          
                            <div class="mb-3">
                                <label for="title" class="form-label">Note title</label>
                                <input type="text" id="titleEdit" name="titleEdit" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                            </div>
                            <div class="form-group">
                                <label for="desc">Note description</label>
                                <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary my-3">Update note</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!----------Navbar---------->
        <nav class="navbar navbar-dark bg-dark">
            <a class="navbar-brand mx-3" href="#">PHP Notes App</a>
        </nav>

        <?php
            if($insert)
            {
                echo 
                '<div class="alert alert-success" role="alert">
                    Your note has been saved.
                </div>';
            }
            else if($update)
            {
                echo 
                '<div class="alert alert-success" role="alert">
                    Your note has been updated.
                </div>';
            }
            else if($delete)
            {
                echo 
                '<div class="alert alert-success" role="alert">
                    Your note has been deleted.
                </div>';
            }
        ?>

        <!----------Add a note---------->
        <div class="container my-5">
            <form action="/notesapp/notes.php" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Note title</label>
                    <input type="text" id="title" name="title" class="form-control" id="title" name="title" aria-describedby="emailHelp">
                </div>
                <div class="form-group">
                    <label for="desc">Note description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-secondary my-3">Add note</button>
            </form>
        </div>
        
        <!----------My Notes---------->
        <div class="container">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">S. No</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
            
                <?php
                    //Displaying notes
                    $sql="SELECT * FROM `notes`";
                    $result=mysqli_query($conn,$sql);
                    $sno=0;
                    while($row = mysqli_fetch_assoc($result))
                    {
                        $sno = $sno + 1;
                        echo 
                        "<tr>
                            <th scope='row'>". $sno . "</th>
                            <td>". $row['title'] . "</td>
                            <td>". $row['description'] . "</td>
                            <td> <button class='edit btn btn-sm btn-secondary' id=".$row['sno'].">Edit</button> <button class='delete btn btn-sm btn-secondary' id=d".$row['sno'].">Delete</button>  </td>
                      </tr>";
                    }
                ?>
            </table>
        </div>
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready( function () {
                $('#myTable').DataTable();
            } );
        </script>
        <script>
            edits=document.getElementsByClassName('edit');
            Array.from(edits).forEach((element)=>{
                element.addEventListener('click',(e)=>{
                    tr=e.target.parentNode.parentNode;
                    title=tr.getElementsByTagName("td")[0].innerText;
                    description=tr.getElementsByTagName("td")[1].innerText;
                    titleEdit.value=title;
                    descriptionEdit.value=description;
                    snoEdit.value=e.target.id;
                    console.log(snoEdit.value);
                    $('#editModal').modal('toggle');
                })
            })

            deletes=document.getElementsByClassName('delete');
            Array.from(deletes).forEach((element)=>{
                element.addEventListener('click',(e)=>{
                    sno=e.target.id.substr(1,);
                    if(confirm("Do you want to delete this message?"))
                    {
                        console.log("Message deleted!");
                        window.location=`/notesapp/notes.php?delete=${sno}`;
                        // $delete=true; WHY DOES IT NOT WORK HERE!!!
                    }
                })
            })
        </script>
    </body>

</html>
