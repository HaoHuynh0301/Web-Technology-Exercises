<?php require "../bootstrap.php"; ?>
<?php
	use CT275\Lab4\Book;
	use CT275\Lab4\Author;
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$author = new Author($_POST);
		$author->save();
		
		$book = new Book($_POST);
		$author->books()->save($book);
	}
	
	$books = Book::all();
?>
<style>
	table, th, td {
		border: 1px solid black;
	}
	label{
		display:inline-block;
		width:150px;
	}	
</style>
<h2>Add new book</h2>
<form method="POST">
	<label>Title:</label> <input type="text" name="title" /> <br><br>
	<label>Num of Pages:</label> <input type="number" name="pages_count" /> <br><br>
	<label>Price:</label> <input type="number" name="price" /> <br><br>
	<label>Description:</label> <input type="text" name="description" /> <br><br>
	<label>Author's First Name:</label> <input type="text" name="first_name" /> <br><br>
	<label>Author's Last Name:</label> <input type="text" name="last_name" /> <br><br>
	<label>Author Email:</label> <input type="text" name="email" /> <br><br>
	<input type="submit" name="submit" value="Save"/>
</form>
<hr>
<h2>List of books: </h2>
<table style="width:100%">
	<tr>
		<th>Title</th>
		<th>Num of Pages</th>
		<th>Price</th>
		<th>Description</th>
		<th>Author</th>
	</tr>
<?php
	foreach ($books as $book)
	{
		echo "<tr>";
		echo "<td>" . $book->title . "</td>";
		echo "<td>" . $book->pages_count . "</td>";
		echo "<td>" . $book->price . "</td>";
		echo "<td>" . $book->description . "</td>";
		echo "<td>" . $book->author->first_name . " " . $book->author->last_name 
					. " (" . $book->author->email . ")" . "</td>";
		echo "</tr>";
	}
?>
</table>