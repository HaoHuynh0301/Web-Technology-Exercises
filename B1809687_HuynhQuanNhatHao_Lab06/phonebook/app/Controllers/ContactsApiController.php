<?php

namespace App\Controllers;

use App\ApiTokenGuard;
use App\Models\Contact;
use App\Models\User;

class ContactsApiController extends Controller
{

    // Thông tin người dùng được chứng thực
    protected $user;
    public function __construct()
    {
        $apiGuard = new ApiTokenGuard();
        if (!$apiGuard->verifyToken()) {
            send_json_fail(['message' => 'Unauthenticated'], 401);
        }
        $this->user = $apiGuard->getUser();
        parent::__construct();
    }

    public function getContactById($contactId)
    {
        $contact = Contact::find($contactId);
        if (!$contact) {
            $this->notFound();
        }

        send_json_success(['contact' => $contact->toArray()]);
    }

    public function create()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        if (!$data) {
            send_json_fail(['message' => 'Invalid data format']);
        }

        $contact = new Contact();
        if ($contact->validate($data)) {
            $contact->fill($data)->save();
            send_json_success(['contact' => $contact->toArray()]);
        } else {
            send_json_fail($contact->getErrors());
        }
    }

    public function edit($contactId)
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        if (!$data) {
            send_json_fail(['message' => 'Invalid data format']);
        }

        $contact = Contact::find($contactId);
        if ($contact->validate($data)) {
            $contact->fill($data)->save();
            send_json_success(['contact' => $contact->toArray()]);
        } else {
            send_json_fail($contact->getErrors());
        }
    }

    public function delete($contactId)
    {
        $contact = Contact::find($contactId);
        if ($contact) {
            $contact->delete();
            send_json_success(['message' => 'Contact has been deleted successfully.']);
        } else {
            send_json_fail($contact->getErrors());
        }
    }

    public function getListContacts()
    {
        $list_contacts = Contact::where('user_id', $this->user->id)->get();
        send_json_success(['list_contacts' => $list_contacts]);
    }

    public function findContact()
    {
        $name = $_GET['name'];

        $contact = Contact::where('user_id', $this->user->id)
            ->where('name', 'like', '%' . $name . '%')
            ->get();
        send_json_success(['contact' => $contact]);
    }
}