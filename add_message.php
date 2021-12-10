<!DOCTYPE html>
<html>
  <head>
    <title>Add Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  </head>
  <body>
    <div class="card">
      <div class="card-body">
      <img src="/img/simpson_family.png" alt="simpson family" title="simpson family"/>
        <h1 style="display: inline-block;">Add a Message:</h1>
        <?php
            $display_add_message_form = true;

            $message = "";

            // pro-tip: you can test multiple variables within a single isset() call
            if (isset($_POST['add_message_submission'], $_POST['message']))
            {
                require_once('db_connection.php');
                require_once('imageutil.php');

                $message = $_POST['message'];
                
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

                    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                            or trigger_error(
                                    'Error connecting to MySQL server for' . DB_NAME, 
                                    E_USER_ERROR);

                    $image_file_path = addImageFileReturnPathLocation();

                    $query = "INSERT INTO text_content (message, image_file) " 
                          . " VALUES ('$message', '$image_file_path')";

                    mysqli_query($dbc, $query)
                        or trigger_error(
                            'Error querying database text_content: Failed to insert message data',
                            E_USER_ERROR);

                    $display_add_message_form = false;
            ?>
                <h3 class="text-info">The Following Details were Added:</h3><br/>
                <div class="row">
                  <div class="col-2">
                    <img src="<?= $image_file_path ?>" class="img-thumbnail" style="max-height: 400px;" alt="image">
                  </div>
                  <div class="col">
                    <table class="table table-striped">
                      <tbody>
                      <tr>
                          <th scope="row">Message</th>
                          <td><?= $message ?></td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <hr/>
                <p>Would you like to <a href='<?= $_SERVER['PHP_SELF'] ?>'> add another message</a> or would you like to go <a href='index.php'> home</a>?</p>
            <?php
                }
                else
                {
                    // echo error message
                    echo "<h5><p class='text-danger'>" . $file_error_message . "</p></h5>";
                }
            }

            if ($display_add_message_form)
            {
        ?>
        <form enctype="multipart/form-data" class="needs-validation"
              novalidate method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
          <div class="form-group row">
            <label for="message"
                   class="col-sm-3 col-form-label-lg">Enter text here:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="message" 
                     name="message" value= '<?=$message?>'
                     placeholder="Hello" required>
              <div class="invalid-feedback">
                Please enter some text.
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="image_file" class="col-sm-3 col-form-label-lg">
                    Student Image File</label>
            <div class="col-sm-8">
              <input type="file" class="form-control-file"
                      id="image_file" name="image_file">
            </div>
          </div>
          <button class="btn btn-primary" type="submit" 
                  name="add_message_submission">Add Message</button>
        </form>
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
        <?php
            } // Display add message form
        ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>