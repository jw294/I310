<!DOCTYPE html>
<html>
  <head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  </head>
  <body>
    <div class="card">
      <div class="card-body">
        <img src="/img/simpson_family.png" alt="simpson family" title="simpson family"/>
        <h1 style="display: inline-block;">Edit Message</h1>
        <nav class="nav">
          <a class="nav-link" href="index.php">Home</a>
        </nav>
        <hr/>
        <?php
            require_once('db_connection.php');
            require_once('imageutil.php');

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or trigger_error('Error connecting to MySQL server for DB_NAME.',
                    E_USER_ERROR);

            if (isset($_GET['id_to_edit']))
            {
                $id_to_edit = $_GET['id_to_edit'];

                $query = "SELECT * FROM text_content WHERE id = $id_to_edit";

                $result = mysqli_query($dbc, $query)
                    or trigger_error('Error querying database text_content',
                    E_USER_ERROR);

                if (mysqli_num_rows($result) == 1)
                {
                    $row = mysqli_fetch_assoc($result);

                    $message = $row['message'];

                    if (empty($image_file))
                    {
                        $image_file_displayed = IMG_UPLOAD_PATH;
                    }
                    else
                    {
                        $image_file_displayed = $image_file;
                    }
                }             
            }
            elseif (isset($_POST['edit_message_submission'],
                    $_POST['message'],
                    $_POST['id_to_update'], $_POST['image_file']))
            {
                $message = $_POST['message'];
                $id_to_update = $_POST['id_to_update'];
                $image_file = $_POST['image_file'];

                if (empty($image_file))
                {
                    $image_file_displayed = IMG_UPLOAD_PATH;
                }
                else
                {
                    $image_file_displayed = $image_file;
                }

                /*
                Here is where we will deal with the file by calling validateStudentImageFile().
                This function will validate that the student image file is not greater than 128
                characters, is the right image type (jpg/png/gif), and not greater than 512KB.
                This function will return an empty string ('') if the file validates successfully,
                otherwise, the string will contain error text to be output to the web page before
                redisplaying the form.
                */

                $file_error_message = validateImageFile();

                if (empty($file_error_message))
                {
                    $image_file_path = addImageFileReturnPathLocation();

                    // IF new image selected, set it to be updated in the database.
                    if (!empty($image_file_path))
                    {
                        // IF replacing an image (other than the default), remove it
                        if (!empty($image_file))
                        {
                            removeImageFile($image_file);
                        }

                        $image_file = $image_file_path;
                    }


                    $query = "UPDATE text_content SET message = '$message', "
                            . " image_file = '$image_file' WHERE text_content.id = $id_to_update";

                    mysqli_query($dbc, $query)
                        or trigger_error(
                            'Error querying database text_content: Failed to update messages.',
                            E_USER_ERROR);

                    $nav_link = 'message_details.php?id=' . $id_to_update;

                    header("Location: $nav_link");

                }
                else
                {
                    // echo error message
                    echo "<h5><p class='text-danger'>" . $file_error_message . "</p></h5>";
                }
            }
            else // Unintended page link -  No message to edit, link back to index
            {
                header("Location: index.php");
            }

        ?>
        <div class="row">
          <div class="col">
            <form  enctype="multipart/form-data" class="needs-validation" novalidate method="POST" 
                  action="<?= $_SERVER['PHP_SELF'] ?>">
              <div class="form-group row">
                <label for="message"
                      class="col-sm-3 col-form-label-lg">Enter text here:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="message" 
                        name="message" value= '<?=$message?>'
                        placeholder="message" required>
                  <div class="invalid-feedback">
                    Please enter some text.
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="student_image_file" class="col-sm-3 col-form-label-lg">Image File</label>
                <div class="col-sm-8">
                  <input type="file" class="form-control-file" id="image_file" name="image_file">
                </div>
              </div>
              <button class="btn btn-primary" type="submit" 
                      name="edit_message_submission">Update Message</button>
              <input type="hidden" name="id_to_update" value="<?= $id_to_edit ?>">
              <input type="hidden" name="image_file" value="<?= $image_file ?>">
            </form>
          </div>
          <div class="col-3">
            <img src="<?=$student_image_file_displayed?>" class="img-thumbnail" style="max-height: 400px;" alt="image">
          </div>
        <script>
        // JavaScript for disabling form submissions if there are invalid fields
        (function() {
          'use strict';
          window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
              form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                  event.preventDefault();
                  event.stopPropagation();
                }
                form.classList.add('was-validated');
              }, false);
            });
          }, false);
        })();
        </script>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>