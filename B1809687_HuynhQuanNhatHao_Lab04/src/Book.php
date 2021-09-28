<?php
    namespace CT275\Lab4;
    use \Illuminate\Database\Eloquent\Model;

    class Book extends Model
    {
        protected $table = "books";
        protected $fillable = ['title', 'price', 'pages_count', 'description'];
        public $timestamps = false;

        public function author()
        {
            return $this->belongsTo('\CT275\Lab4\Author', 'author_id');
        }

        public static function validate(array $data)
        {   
            $errors = [
                "title" => "", 
                "pages_count" => "", 
                "price" => "", 
                "description" => "", 
                "author_id" => "", 
            ];
            
            if (!$data["title"]) {
                $errors['title'] = 'Invalid title.';
            }
            if ($data["author_id"] === "") {
                $errors['author_id'] = 'Please select author.';
            }
            if (!$data["pages_count"] || $data["pages_count"] < 0) {
                $errors['pages_count'] = 'Invalid pages. Pages must be bigger than 0.';
            }
            if (!$data["price"] || $data["price"] < 0) {
                $errors['price'] = 'Invalid price. Price must be bigger than 0.';
            }
            if (!$data["description"]) {
                $errors['description'] = "Please enter book's description";
            }

            return $errors;
        }
    }