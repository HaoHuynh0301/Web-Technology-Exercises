<?php
    require "../bootstrap.php";
    use CT275\Lab4\Book;

    if ($_SERVER['REQUEST_METHOD'] == 'GET'
        && isset($_GET['id'])
        && ($book = Book::find($_GET['id'])) !== null
    ) {
        
    }
    else {
        header('Location: books.php');
    }

    
    $authors = CT275\Lab4\Author::all();
    
    $errors = [
        "title" => "", 
        "pages_count" => "", 
        "price" => "", 
        "description" => "", 
        "author_id" => "", 
    ];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = CT275\Lab4\Book::validate($_POST);
        
        if (!array_filter($errors)) {
            $book = Book::find($_GET['id']);
            $book->title = $_POST["title"];
            $book->pages_count = $_POST["pages_count"];
            $book->price = $_POST["price"];
            $book->description = $_POST["description"];
            $book->author_id = $_POST["author_id"];
            $book->save();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Books</title>

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

        <!-- Add books form -->
        <form method="post">
            <h4 class="form-title">Edit book <b><?=htmlspecialchars($book->title)?></b> </h4>
            <!-- Title -->
            <div class="form-group">
                <label for="title">Book title</label>
                <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp"
                    placeholder="Enter book title" value="<?php echo $book->title;?>">
                <div class="error"><?=htmlspecialchars($errors["title"])?></div>
            </div>
            <!-- Author -->
            <div class="form-group">
                <label for="author_id">Author</label>
                <select class="form-control" id="author_id" name="author_id" value="<?php echo $book->auhtor_id;?>">
                    <?php foreach($authors as $author): ?>
                    <option value="<?php echo $author['id'];?>">
                        <?=htmlspecialchars("{$author->first_name} {$author->last_name}")?>
                    </option>
                    <?php endforeach ?>
                </select>
                <div class="error"><?=htmlspecialchars($errors["author_id"])?></div>
            </div>
            <!-- Pages count -->
            <div class="form-group">
                <label for="pages_count">Pages of the book</label>
                <input type="number" class="form-control" id="pages_count" name="pages_count" min=10
                    placeholder="How many pages in the book" value="<?php echo $book->pages_count;?>">
                <div class="error"><?=htmlspecialchars($errors["pages_count"])?></div>
            </div>
            <!-- Price -->
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" min=10
                    placeholder="Enter book price (unit: .000 VND)" value="<?php echo $book->price;?>">
                <div class="error"><?=htmlspecialchars($errors["price"])?></div>
            </div>
            <!-- Description -->
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description"
                    class="form-control"><?php echo $book->description;?></textarea>
                <div class="error"><?=htmlspecialchars($errors["description"])?></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <!-- End of book form -->
    </div>
</body>

</html>