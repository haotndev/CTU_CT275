<?php $this->layout("layouts/default", ["title" => APPNAME]) ?>

<?php $this->start("page_specific_css") ?>
<link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.css" rel="stylesheet">
<?php $this->stop() ?>

<?php $this->start("page") ?>
<div class="container">

  <!-- SECTION HEADING -->
  <h2 class="text-center animate__animated animate__bounce">Contacts</h2>
  <div class="row">
    <div class="col-md-6 offset-md-3 text-center">
      <p class="animate__animated animate__fadeInLeft">View your all contacs here.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-12">

      <!-- FLASH MESSAGES -->

      <?php if (isset($messages['success'])) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong><?= $this->e($messages['success']) ?></strong>
          <!-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"> -->
          <!--   <span aria-hidden="true">&times;</span> -->
          <!-- </button> -->
        </div>
      <?php endif ?>

      <a href="/contacts/add" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> New Contact</a>

      <!-- Table Starts Here -->
      <table id="contacts" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Phone</th>
            <th scope="col">Date Created</th>
            <th scope="col">Notes</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($contacts as $contact) : ?>
            <tr>
              <td><?= $this->e($contact->name) ?></td>
              <td><?= $this->e($contact->phone) ?></td>
              <td><?= $this->e(date("d-m-Y", strtotime($contact->created_at))) ?></td>
              <td><?= $this->e($contact->notes) ?></td>
              <td class="d-flex justify-content-center">
                <a href="<?= '/contacts/edit/' . $this->e($contact->id) ?>" class="btn btn-xs btn-warning">
                  <i alt="Edit" class="fa fa-pencil"></i> Edit</a>
                <form class="ms-1" action="<?= '/contacts/delete/' . $this->e($contact->id) ?>" method="POST">
                  <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
                  <button type="submit" class="btn btn-xs btn-danger" name="delete-contact">
                    <i alt="Delete" class="fa fa-trash"></i> Delete
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      <!-- Table Ends Here -->
    </div>
  </div>
</div>
<div id="delete-confirm" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmation</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal">
        </button>
      </div>
      <div class="modal-body">Do you want to delete this contact?</div>
      <div class="modal-footer">
        <button type="button" data-bs-dismiss="modal"
          class="btn btn-danger" id="delete">Delete</button>
        <button type="button" data-bs-dismiss="modal"
          class="btn btn-default">Cancel</button>
      </div>
    </div>
  </div>
</div>
<?php $this->stop() ?>

<?php $this->start("page_specific_js") ?>
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.js"></script>
<script>
  let table = new DataTable('#contacts', {
    responsive: true,
    pagingType: 'simple_numbers'
  });
  const deleteButtons = document.querySelectorAll('button[name="delete-contact"]');
  deleteButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const form = button.closest('form');
      const nameTd = button.closest('tr').querySelector('td:first-child');
      if (nameTd) {
        document.querySelector('.modal-body').textContent =
          `Do you want to delete "${nameTd.textContent}"?`;
      }
      const submitForm = function() {
        form.submit();
      };
      document.getElementById('delete').addEventListener('click', submitForm, {
        once: true
      });
      const modalEl = document.getElementById('delete-confirm');
      modalEl.addEventListener('hidden.bs.modal', function() {
        document.getElementById('delete').removeEventListener('click',
          submitForm);
      });
      const confirmModal = new bootstrap.Modal(modalEl, {
        backdrop: 'static',
        keyboard: false
      });
      confirmModal.show();
    });
  });
</script>
<?php $this->stop() ?>
