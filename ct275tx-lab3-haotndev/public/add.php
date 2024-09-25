<?php
require_once __DIR__ . '/../src/bootstrap.php';

use CT275\Labs\Contact;

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["avatar"]["name"]);
  move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);

  $contactData = [
    'name' => filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_SPECIAL_CHARS),
    'phone' => preg_replace('/[^0-9]+/', '', trim($_POST['phone'] ?? '')),
    'notes' => filter_var($_POST['notes'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS),
    'avatar' => $target_file ?? ''
  ];
  $contact = new Contact($PDO);
  $errors = $contact->validate($contactData);
  if (empty($errors)) {
    $contact->fill($contactData);
    $contact->save() && redirect('/');
  }
}

include_once __DIR__ . '/../src/partials/header.php';
?>

<body>
  <?php include_once __DIR__ . '/../src/partials/navbar.php' ?>

  <!-- Main Page Content -->
  <div class="container">

    <?php
    $subtitle = 'Add your contacts here.';
    include_once __DIR__ . '/../src/partials/heading.php';
    ?>

    <div class="row">
      <div class="col-12">

        <form method="post" class="col-md-6 offset-md-3" enctype="multipart/form-data">

          <!-- Avatar -->
          <div class="mb-3 col-md-3 mx-auto mt-5">
            <label for="avatar" class="form-label">Avatar</label>
            <div class="position-relative border d-flex justify-content-center">
              <img src="" alt="Avatar" id="preview-avatar" class="mx-auto" height="100">
              <input type="file" accept="image/*" name="avatar" class="w-100 h-100 opacity-0 position-absolute top-0 left-0 bottom-0 right-0 form-control<?= isset($errors['avatar']) ? ' is-invalid' : '' ?>" id="avatar" placeholder="Select avatar" value="<?= isset($_POST['avatar']) ? html_escape($_POST['avatar']) : '' ?>" />
            </div>
            <?php if (isset($errors['avatar'])) : ?>
              <span class="invalid-feedback">
                <strong><?= $errors['avatar'] ?></strong>
              </span>
            <?php endif ?>
          </div>

          <!-- Name -->
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" maxlen="255" id="name" placeholder="Enter Name" value="<?= isset($_POST['name']) ? html_escape($_POST['name']) : '' ?>" />

            <?php if (isset($errors['name'])) : ?>
              <span class="invalid-feedback">
                <strong><?= $errors['name'] ?></strong>
              </span>
            <?php endif ?>
          </div>

          <!-- Phone -->
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" class="form-control<?= isset($errors['phone']) ? ' is-invalid' : '' ?>" maxlen="255" id="phone" placeholder="Enter Phone" value="<?= isset($_POST['phone']) ? html_escape($_POST['phone']) : '' ?>" />

            <?php if (isset($errors['phone'])) : ?>
              <span class="invalid-feedback">
                <strong><?= $errors['phone'] ?></strong>
              </span>
            <?php endif ?>
          </div>

          <!-- Notes -->
          <div class="mb-3">
            <label for="notes" class="form-label">Notes </label>
            <textarea name="notes" id="notes" class="form-control<?= isset($errors['notes']) ? ' is-invalid' : '' ?>" placeholder="Enter notes (maximum character limit: 255)"><?= isset($_POST['notes']) ? html_escape($_POST['notes']) : '' ?></textarea>

            <?php if (isset($errors['notes'])) : ?>
              <span class="invalid-feedback">
                <strong><?= $errors['notes'] ?></strong>
              </span>
            <?php endif ?>
          </div>

          <!-- Submit -->
          <button type="submit" name="submit" class="btn btn-primary">Add Contact</button>
        </form>

      </div>
    </div>

  </div>
  <script>
    const input = document.getElementById('avatar');
    const img = document.getElementById('preview-avatar');
    input.onchange = function(event) {
      const [file] = input.files;
      img.src = file ? URL.createObjectURL(file) : '';
    }
  </script>
  <?php include_once __DIR__ . '/../src/partials/footer.php' ?>
</body>

</html>