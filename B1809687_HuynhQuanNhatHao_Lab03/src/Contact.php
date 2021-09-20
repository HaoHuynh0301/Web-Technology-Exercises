<?php

namespace CT275\Lab3;

class Contact 
{
    public $id = false;
    public $name;
    public $phone;
    public $notes;
    public $created_at;
    public $updated_at;
    protected $errors = [];

    public function __construct(array $data = [])
    {
        $this->fill($data);
    }   

    public function fill(array $data)
    {
        if (isset($data['name'])) {
            $this->name = filter_var(trim($data['name']), FILTER_SANITIZE_STRING);
        }

        if (isset($data['phone'])) {
            $this->phone = preg_replace('/[^0-9]+/', '', $data['phone']);
        }

        if (isset($data['notes'])) {
            $this->notes = filter_var(trim($data['notes']), FILTER_SANITIZE_STRING);
        }
        
        return $this;
    }    

    public function getValidationErrors()
    {
        return $this->errors;
    }

    public function validate()
    {
        if (! $this->name) {
            $this->errors['name'] = 'Invalid name.';
        }

        if (strlen($this->phone) < 10 || strlen($this->phone) > 11) {
            $this->errors['phone'] = 'Invalid phone number.';
        }

        if (strlen($this->notes) > 255) {
            $this->errors['notes'] = 'Notes must be at most 255 characters.';
        }

        return (count($this->errors) > 0 ? false : true);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    public static function all()
    {
        $contacts = [];
        $db = Db::getInstance();
        $stmt = $db->prepare("select * from contacts");
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $contact = static::createFromDb($row);
            $contacts[] = $contact;
        }
        return $contacts;
    }
    protected static function createFromDb(array $data)
    {
        $contact = new Contact();
        $contact->id = $data['id'];
        $contact->name = $data['name'];
        $contact->phone = $data['phone'];
        $contact->notes = $data['notes'];
        $contact->created_at = $data['created_at'];
        $contact->updated_at = $data['updated_at'];
        return $contact;
    }

    public function save() {
        $result = false;
        $db = Db::getInstance();
        if ($this->id) {
            $stmt = $db->prepare("update contacts set name = :name, phone = :phone,
            notes = :notes, updated_at = now() where id = :id");
            $result = $stmt->execute([
            'name' => $this->name,
            'phone' => $this->phone,
            'notes' => $this->notes,
            'id' => $this->id]);
        } else {
            $stmt = $db->prepare(
            "insert into contacts (name, phone, notes, created_at, updated_at)
            values (:name, :phone, :notes, now(), now())");
            $result = $stmt->execute([
            'name' => $this->name,
            'phone' => $this->phone,
            'notes' => $this->notes]);
            if ($result) {
                $this->id = $db->lastInsertId();
            }
        }
        return $result;
    }
    public static function find($id) {
        $contact = null;
        $db = Db::getInstance();
        $stmt = $db->prepare("select * from contacts where id = :id");
        $stmt->execute(['id' => $id]);
        if ($row = $stmt->fetch()) {
            $contact = static::createFromDb($row);
        }
        return $contact;
    }

    public function update(array $data) {
        $this->fill($data);
        if ($this->validate()) {
            return $this->save();
        }
        return false;
    }

    public function delete() {
        $db = Db::getInstance();
        $stmt = $db->prepare("delete from contacts where id = :id");
        return $stmt->execute(['id' => $this->id]);
    }
}

