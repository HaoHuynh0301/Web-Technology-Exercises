<?php

namespace App\Controllers;

use App\Models\Contact;
use App\Models\User;
use App\SessionGuard as Guard;

class ContactsController extends Controller
{
    public function __construct()
    {
        // Nếu người dùng chưa đăng nhập thì chuyển hướng đến đăng nhập
        if (! Guard::checkLogin()) {
            redirect('/login');
        }

        parent::__construct();
    }

    public function index()
    {
        // Thu thập dữ liệu cho view
        $data = [
            'messages' => session_get_once('messages'),
            'contacts' => Contact::where('user_id', Guard::user()->id)->get()
        ];

        // Tạo và hiển thị view
        echo $this->view->render('contacts/home', $data);
    }

    public function add()
    {
        // Thu thập dữ liệu cho view
        $data = [
            'old' => session_get_once('form'),
            'errors' => session_get_once('errors')
        ];            

        // Tạo và hiển thị view
        echo $this->view->render('contacts/add', $data);
    }

    public function create()
    {
        // Ngăn ngừa tấn công CSRF
        $this->invokeCsrfGuard();

        // Thu thập và kiểm tra dữ liệu trong form
        $data = $this->getContactData();
        $contact = new Contact();
        if ($contact->validate($data)) {
            // Dữ liệu hợp lệ...
            $contact->fill($data);
            $contact->user_id = Guard::user()->id;
            $contact->save();
            $messages = ['success' => 'Contact has been created successfully.'];
            redirect('/', ['messages' => $messages]);
        }
        
        // Dữ liệu bị lỗi...
        $this->saveFormValues();
        redirect('/contacts/add', ['errors' => $contact->getErrors()]);
    }

    public function edit($contactId)
    {
        // Kiểm tra xem id có tồn tại hay không?
        $contact = Contact::find($contactId);
        if (! $contact || ($contact->user_id !== Guard::user()->id)) {
            $this->notFound(); 
        }

        // Thu thập giá trị trước đó của form (nếu có)
        $formValues = session_get_once('form');

        // Thu thập dữ liệu cho view
        $data = [
            'errors' => session_get_once('errors'),
            'contact' => (count($formValues) > 0) ? 
                array_merge($formValues, ['id' => $contact->id]) : $contact->toArray()         
        ];        

        // Tạo và hiển thị view
        echo $this->view->render('contacts/edit', $data);
    }

    public function update($contactId)
    { 
        // Ngăn ngừa tấn công CSRF
        $this->invokeCsrfGuard();

        // Kiểm tra xem id có tồn tại hay không?
        $contact = Contact::find($contactId);
        if (! $contact || ($contact->user_id !== Guard::user()->id)) {
            $this->notFound();   
        }

        // Thu thập và kiểm tra dữ liệu trong form
        $data = $this->getContactData();
        if ($contact->validate($data)) {
            // Dữ liệu hợp lệ...
            $contact->fill($data);
            $contact->save();
            $messages = ['success' => 'Contact has been updated successfully.'];
            redirect('/', ['messages' => $messages]);
        }

        // Dữ liệu bị lỗi...
        $this->saveFormValues();
        redirect('/contacts/edit/'.$contactId, ['errors' => $contact->getErrors()]);
    }

    public function delete($contactId)
    {
        // Ngăn ngừa tấn công CSRF
        $this->invokeCsrfGuard();

        // Kiểm tra xem id có tồn tại hay không?
        $contact = Contact::find($contactId);
        if (! $contact || ($contact->user_id !== Guard::user()->id)) {
            $this->notFound();                       
        }

        // Thực hiện xóa contact...
        $contact->delete();
        $messages = ['success' => 'Contact has been deleted successfully.'];
        redirect('/', ['messages' => $messages]);
    }    

    protected function getContactData()
    {
        return [
            'name' => filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING),
            'phone' => preg_replace('/[^0-9]+/', '', $_POST['phone']),
            'notes' => filter_var(trim($_POST['notes']), FILTER_SANITIZE_STRING)
        ];        
    }
}
