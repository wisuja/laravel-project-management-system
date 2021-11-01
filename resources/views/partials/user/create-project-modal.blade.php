<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createProjectModalLabel">Create a new project</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('projects.store') }}" method="POST" id="form-create-project">
          @csrf
          <div class="form-group">
            <label for='name'>Name</label>
            <input type='text' name='name' id='name' class='form-control text-capitalize' required minlength="3">
          </div>
          <div class="form-group">
            <label for='duration'>Duration</label>
            <input type='text' name='duration' id='duration' class='form-control' required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="form-create-project">Create</button>
      </div>
    </div>
  </div>
</div>