<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Message Details</title>
</head>
<body>
  <div class="card">
    <div class="card-body">
      <nav class="nav">
        <a class="nav-link" href="index.php">Home</a>
      </nav>
      <?php
          if (isset($_GET['id'])):

              require_once('db_connection.php');
              require_once('fileconstants.php');

              $id = $_GET['id'];

              $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                      or trigger_error('Error connecting to MySQL server for '
                      . DB_NAME, E_USER_ERROR);

              $query = "SELECT * FROM text_content WHERE id = $id";

              $result = mysqli_query($dbc, $query)
                      or trigger_error('Error querying database studentListing',
                      E_USER_ERROR);

              if (mysqli_num_rows($result) == 1):

                  $row = mysqli_fetch_assoc($result);

                  $image_file = $row['image_file'];

                  if (empty($image_file)):
                      $image_file = IMG_UPLOAD_PATH;

                  endif;

          ?>
      <h1>ID: <?= $row['id'] ?></h1>
      <div class="row">
        <div class="col-4">
          <img src="<?=$image_file?>" class="img-thumbnail"
              style="max-height: 200px;" alt="Student image">
        </div>
        <div class="col">
          <table class="table table-striped">
            <tbody>
              <tr>
                <th scope="row">Message</th>
                <td><?= $row['message'] ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <hr/>
      <p>If you would like to change any of the details of this message, feel free to <a href='edit_message.php?id_to_edit=<?=$row['id']?>'> edit it</a></p>
          <?php
              else:
          ?>
      <h3>No Message Details :-(</h3>
      <?php
              endif;           
          else:
      ?>
      <h3>No Message Details :-(</h3>
      <?php
          endif;           
      ?>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>