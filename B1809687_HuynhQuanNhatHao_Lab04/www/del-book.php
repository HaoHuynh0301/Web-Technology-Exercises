<?php
    require "../bootstrap.php";
    use CT275\Lab4\Book;
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET'
        && isset($_GET['id'])
        && ($book = Book::find($_GET['id'])) !== null
    ) {
        $book->delete();
    } 
    header('Location: books.php');