<html>
  <head>
    <title>Remove Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  </head>
  <body>
    <div class="card">
      <div class="card-body">
        <img src="/img/simpson_family.png" alt="simpson family" title="simpson family"/>
        <h1 style="display: inline-block;">Remove Message</h1>
        <?php
            require_once('db_connection.php');
            require_once('imageutil.php');

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or trigger_error('Error connecting to MySQL server for DB_NAME.',
                            E_USER_ERROR);

            if (isset($_POST['delete_message_submission'], $_POST['id'])):

                $id = $_POST['id'];

                // Query image file from DB
                $query = "SELECT image_file FROM text_content WHERE id = $id";

                $result = mysqli_query($dbc, $query)
                        or trigger_error('Error querying database text_content', E_USER_ERROR);

                if (mysqli_num_rows($result) == 1)
                {
                    $row = mysqli_fetch_assoc($result);

                    $image_file = $row['image_file'];

                    if (!empty($image_file))
                    {
                        removeImageFile($image_file);
                    }
                }

                $query = "DELETE FROM text_content WHERE id = $id";

                $result = mysqli_query($dbc, $query)
                        or trigger_error('Error querying database text_content', E_USER_ERROR);

                header("Location: index.php");

            elseif (isset($_POST['do_not_delete_message_submission'])):

                header("Location: index.php");

            elseif (isset($_GET['id_to_delete'])):
        ?>
                <h3 class="text-danger">Confirm Deletion of the Following Message Details:</h3><br/>
        <?php
                $id = $_GET['id_to_delete'];

                $query = "SELECT * FROM text_content WHERE id = $id";

                $result = mysqli_query($dbc, $query)
                        or trigger_error('Error querying database text_content', E_USER_ERROR);

                if (mysqli_num_rows($result) == 1):

                    $row = mysqli_fetch_assoc($result);

                    $image_file = $row['image_file'];

                    if (empty($image_file))
                    {
                        $image = IMG_UPLOAD_PATH;
                    }
            ?>
            <h1>Delete the following message?</h1>
            <div class="row">
              <div class="col-3">
                <img src="<?=$image_file?>" class="img-thumbnail" style="max-height: 400px;" alt="image">
              </div>
              <div class="col"> 
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <th scope="row">Message</th>
                      <td><?=$row['message']?></td>
                    </tr>
                  </tbody>
                </table>
                <form method="POST" action="<?=$_SERVER['PHP_SELF'];?>">
                  <div class="form-group row">
                    <div class="col-sm-4">
                      <button class="btn btn-danger" type="submit" name="delete_message_submission">Delete Message</button>
                    </div>
                    <div class="col-sm-4">
                      <button class="btn btn-success" type="submit" name="do_not_delete_message_submission">Don't Delete</button>
                    </div>
                    <input type="hidden" name="id" value="<?= $id ?>;">
                  </div>
                </form>
              </div>
            </div>
            <?php
                else:
            ?>
        <h3>No Message Details :-(</h3>
            <?php
                endif;

            else: // Unintended page link -  No Message to remove, link back to index

                header("Location: index.php");

            endif;
        ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>