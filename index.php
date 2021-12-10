<html>
  <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <title>Message Board</title>
  </head>
  <body>
    <div class="card mx-3">

      <div class="card-body">
        <img src="/img/simpson_family.png" alt="simpson family" title="simpson family"/>
        <h1 style="display: inline-block;">IU Club Message Board</h1>
        <p class='nav-link'>Add a new<a href='add_message.php'> message</a>.</p>
        <hr />
        <?php
            require_once('db_connection.php');
            require_once('fileconstants.php');

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or trigger_error('Error connecting to MySQL server for' .  DB_NAME, E_USER_ERROR);

            $query = "SELECT * FROM text_content";

            $result = mysqli_query($dbc, $query)
                    or trigger_error('Error querying database text_content', E_USER_ERROR);

            if (mysqli_num_rows($result) > 0):
        ?>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Messages</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
            <?php
                while($row = mysqli_fetch_assoc($result))
                {
                    $image_file = $row['image_file'];

                    if (empty($image_file))
                    {
                        $image_file = IMG_UPLOAD_PATH;
                    }

                    echo "<tr><td>"
                        . "<img src=" . $image_file . " class='img-thumbnail'"
                        . " style='max-height: 75px;' alt='image'></td>"
                        . "<td><a class='nav-link' href='message_details.php?id=" . $row['id'] . "'>"
                        . $row['message'] . "</a></td>"
                        . "<td><a class='nav-link' href='delete_message.php?id_to_delete="
                        . $row['id'] ."'><i class='fas fa-trash-alt'>Delete</i></a></td></tr>";
                }
            ?>
                    </tbody>
                </table>        
        <?php
            else:
        ?>
                <h3>No Message Content Found :-(</h3>
        <?php
            endif;           
        ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>