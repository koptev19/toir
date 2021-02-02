<div class="modal fade" id="addCrashHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-md">
        <div class="modal-content">
            <form action="add_history.php" target=_blank method="get">
            <div class="modal-header">
                <h5 class="modal-title">Выберите службу</h5>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Добавить операцию</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="crashModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body" id="crashModalContent">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addFilesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
    <div class="modal-content">
      <form  method="post" action="crash_edit.php" enctype="multipart/form-data">
      <input type="hidden" name="save_files" value="1" id='addFilesAction'>
      <input type="hidden" name="crash" value="" id='addFilesCrash'>
      <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body" id="addFilesContent">
        <input type="file" multiple name="DOCUMENTS[]" class="form-control" />
      </div>
      <div class="modal-footer">
        <input value="Сохранить " type="submit" class='btn btn-primary'>
      </div>
      </form>
    </div>
  </div>
</div>
