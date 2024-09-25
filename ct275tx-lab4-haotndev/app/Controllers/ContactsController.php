<?php

namespace App\Controllers;

use \App\Models\Contact;

class ContactsController extends Controller
{
  public function __construct()
  {
    if (!AUTHGUARD()->isUserLoggedIn()) {
      redirect('/login');
    }

    parent::__construct();
  }

  public function index()
  {
    $this->sendPage(
      'contacts/index',
      [
        'contacts' => AUTHGUARD()->user()?->contacts() ?? [],
        'messages' => session_get_once('messages')
      ]
    );
  }

  public function create()
  {
    $this->sendPage('contacts/create', [
      'errors' => session_get_once('errors'),
      'old' => $this->getSavedFormValues()
    ]);
  }

  public function store()
  {
    if (!$this->checkCSRFToken()) {
      redirect('/contacts/add', ['errors' => [
        "security" => "Your form submit is invalid!"
      ]]);
    }
    $data = $this->filterContactData($_POST);
    $newContact = new Contact(PDO());
    $modelErrors = $newContact->validate($data);
    if (empty($modelErrors)) {
      $newContact->fill($data)
        ->setUser(AUTHGUARD()->user())
        ->save();
      $messages =  ['success' => 'Contact has been created successfully!'];
      redirect('/', ['messages' => $messages]);
    }

    $this->saveFormValues($_POST);
    redirect('/contacts/add', ['errors' => $modelErrors]);
  }

  protected function filterContactData(array $data)
  {
    return [
      'name' => $data['name'] ?? '',
      'phone' => $data['phone'] ?? '',
      'notes' => $data['notes'] ?? ''
    ];
  }

  public function edit($contactId)
  {
    $contactId = filter_var($contactId, FILTER_SANITIZE_NUMBER_INT);
    $contact = AUTHGUARD()->user()->findContact($contactId);
    if (!$contact) {
      $this->sendNotFound();
    }
    $formValues = $this->getSavedFormValues();
    $data = [
      'errors' => session_get_once('errors'),
      'contact' => (!empty($formValues)) ?
        array_merge($formValues, ['id' => $contact->id]) :
        (array) $contact
    ];
    $this->sendPage('contacts/edit', $data);
  }

  public function update($contactId)
  {
    if (!$this->checkCSRFToken()) {
      redirect(
        '/contacts/edit/' . $contactId,
        ['errors' => "Your form submit is invalid!"]
      );
    }
    $contact = AUTHGUARD()->user()->findContact($contactId);
    if (!$contact) {
      $this->sendNotFound();
    }

    $data = $this->filterContactData($_POST);
    $modelErrors = $contact->validate($data);
    if (empty($modelErrors)) {
      $contact->fill($data);
      $contact->save();
      $messages =  ['success' => 'Contact has been updated successfully!'];
      redirect('/', ['messages' => $messages]);
    }
    $this->saveFormValues($_POST);
    redirect(
      '/contacts/edit/' . $contactId,
      ['errors' => $modelErrors]
    );
  }

  public function destroy($contactId)
  {
    if (!$this->checkCSRFToken()) {
      redirect('/', ['errors' => "Your form submit is invalid!"]);
    }
    $contact = AUTHGUARD()->user()->findContact($contactId);
    if (!$contact) {
      $this->sendNotFound();
    }
    $contact->delete();
    $messages =  ['success' => 'Contact has been deleted successfully!'];
    redirect('/', ['messages' => $messages]);
  }
}
