<?php

namespace App\Models;

use PDO;

class Contact
{
  private PDO $db;

  public int $id = -1;
  public int $user_id;
  public string $name;
  public string $phone;
  public string $notes;
  public string $created_at;
  public string $updated_at;

  public function __construct(PDO $pdo)
  {
    $this->db = $pdo;
  }

  public function setUser(User $user): Contact
  {
    $this->user_id = $user->id;
    return $this;
  }

  public function contactsForUser(User $user): array
  {
    $contacts = [];

    $statement = $this->db->prepare(
      'select * from contacts where user_id = :user_id'
    );
    $statement->execute(['user_id' => $user->id]);
    while ($row = $statement->fetch()) {
      $contact = new Contact($this->db);
      $contact->fillFromDbRow($row);
      $contacts[] = $contact;
    }

    return $contacts;
  }

  public function save(): bool
  {
    $result = false;

    if ($this->id >= 0) {
      $statement = $this->db->prepare(
        'update contacts set name = :name, phone = :phone, 
					notes = :notes, user_id = :user_id, updated_at = now() where id = :id'
      );
      $result = $statement->execute([
        'name' => $this->name,
        'phone' => $this->phone,
        'notes' => $this->notes,
        'id' => $this->id,
        'user_id' => $this->user_id
      ]);
    } else {
      $statement = $this->db->prepare(
        'insert into contacts
				(name, phone, notes, user_id, created_at, updated_at)
				values (:name, :phone, :notes, :user_id, now(), now())'
      );
      $result = $statement->execute(
        [
          'name' => $this->name,
          'phone' => $this->phone,
          'notes' => $this->notes,
          'user_id' => $this->user_id
        ]
      );
      if ($result) {
        $this->id = $this->db->lastInsertId();
      }
    }

    return $result;
  }

  public function find(int $id): ?Contact
  {
    $statement = $this->db->prepare(
      'select * from contacts where id = :id'
    );
    $statement->execute(['id' => $id]);

    if ($row = $statement->fetch()) {
      $this->fillFromDbRow($row);
      return $this;
    }

    return null;
  }

  public function delete(): bool
  {
    $statement = $this->db->prepare(
      'delete from contacts where id = :id'
    );
    return $statement->execute(['id' => $this->id]);
  }

  public function fill(array $data): Contact
  {
    $this->name = $data['name'] ?? '';
    $this->phone = $data['phone'] ?? '';
    $this->notes = $data['notes'] ?? '';
    return $this;
  }

  public function validate(array $data): array
  {
    $errors = [];

    $name = trim($data['name'] ?? '');
    if (!$name) {
      $errors['name'] = 'Invalid name.';
    }

    $validPhone = preg_match(
      '/^(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})\b$/',
      $data['phone'] ?? ''
    );
    if (!$validPhone) {
      $errors['phone'] = 'Invalid phone number.';
    }

    $notes = trim($data['notes'] ?? '');
    if (strlen($notes) > 255) {
      $errors['notes'] = 'Notes must be at most 255 characters.';
    }

    return $errors;
  }

  private function fillFromDbRow(array $row): Contact
  {
    [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'name' => $this->name,
      'phone' => $this->phone,
      'notes' => $this->notes,
      'created_at' => $this->created_at,
      'updated_at' => $this->updated_at
    ] = $row;
    return $this;
  }
}
