<?php
    require "../bootstrap.php";
    use CT275\Lab4\Book;

    $author = "";
    $title = "";

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['author']) && isset($_GET['title'])) {
        $author = $_GET['author'];
        $title = $_GET['title'];
    }

    $books = CT275\Lab4\Book::whereHas("author", function($q) use ($author) {
        $q->where('first_name', "LIKE", "%{$author}%")
          ->orWhere('last_name', "LIKE", "%{$author}%");
    })->where("title", 'LIKE', "%{$title}%")->get();

    // echo $_SESSION['phrase'];
    require_once __DIR__.'/../vendor/autoload.php';
    use Gregwar\Captcha\CaptchaBuilder;

    $captcha = new CaptchaBuilder;
    $_SESSION['phrase'] = $captcha->getPhrase();

    $captcha
        ->build()
        ->save("out.jpg");
    
    $phrase = $_SESSION['phrase'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <title>Seach Books</title>
    <style>
    .container-fluid {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    form {
        margin-top: 20px;
        min-width: 500px;
    }

    form .error {
        color: red;
        font-size: 12px
    }

    .navbar {
        width: 100%;
    }

    .table {
        width: 70%;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Navbar content -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="./books.php">Book</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="./search-books.php">Search Book</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Navbar end -->

        <!-- Search form -->
        <!-- Add books form -->
        <form method="get" id="myForm">
            <!-- Title -->
            <div class="form-group">
                <label for="title">Book's title</label>
                <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp"
                    placeholder="Enter book's title">
            </div>
            <!-- Author -->
            <div class="form-group">
                <label for="author">Book's author</label>
                <input type="text" class="form-control" name="author" id="author" aria-describedby="emailHelp"
                    placeholder="Enter book's author name">
            </div>
            <div id="capchaBtn" class="btn btn-primary" style="cursor: pointer;">Search</div>
        </form>
        <!-- End of book form -->

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Pages Count</th>
                    <th scope="col">Price</th>
                    <th scope="col">Description</th>
                    <th scope="col">Author</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($books as $book): ?>
                <tr>
                    <td><?=htmlspecialchars($book->title)?></td>
                    <td><?=htmlspecialchars($book->pages_count)?></td>
                    <td><?=htmlspecialchars($book->price)?></td>
                    <td><?=htmlspecialchars($book->description)?></td>
                    <td>
                        <?=htmlspecialchars("{$book->author->first_name} {$book->author->last_name}")?>
                    </td>
                    <td>
                        <a href="/del-book.php?id=<?=htmlspecialchars($book->id)?>" class="btn btn-xs btn-danger">
                            Delete
                        </a>
                        <a href="/edit-book.php?id=<?=htmlspecialchars($book->id)?>" class="btn btn-xs btn-warning">
                            Edit
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <!-- Capcha modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Captcha</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img src="./out.jpg" alt="captcha">
                    <input type="text" name="captcha" id="captcha" placeholder="Enter captcha ...">
                    <div class="error"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="captchaSubmit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
    $("#capchaBtn").click(function() {
        $("#exampleModalCenter").modal("show");
    });

    $("#captchaSubmit").click(function() {
        const captcha = "<?php echo $phrase ?>"
        const input = $("#captcha").val();


        if (input === captcha) $("#myForm").submit();
        else alert("Incorrect captcha");
    });
    </script>
</body>

</html>