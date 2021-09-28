<?php
    namespace CT275\Lab4;
    use \Illuminate\Database\Eloquent\Model;

    class Author extends Model
    {
        protected $table = "authors";
        protected $fillable = ['first_name', 'last_name', 'email'];
        public $timestamps = false;

        public function books()
        {
            return $this->hasMany('\CT275\Lab4\Book');
        }
    }