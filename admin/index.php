<?php require_once '../config.php';
debug_toggle(false, 'userdata'); // Set to true to enable debugging
?>
<!doctype html>
<html lang="en">
<?php require_once './includes/header.php'; ?>
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <!--begin::App Wrapper-->
  <div class="app-wrapper">
    <?php
    include_once './includes/topbar.php';
    include_once './includes/sidebar.php';
    ?>
    <?php $page = $_GET['page'] ?? 'home'; ?>
    <!--begin::App Main-->
    <main class="app-main">
      <!--begin::App Content Header-->
      <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Row-->
          <div class="row">
            <div class="col-sm-6">
              <h3><?= ucwords(str_replace("_", " ", $page)) ?></h3>
            </div>
          </div>
          <!--end::Row-->
        </div>
        <!--end::Container-->
      </div>
      <!--end::App Content Header-->
      <!--begin::App Content-->
      <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
          <div class="row">
            <div class="col">
              <?php
              // Include the page content based on the 'page' parameter
              if (!file_exists($page . '.php') && !is_dir($page)) {
                include '404.html';
              } else {
                if (is_dir($page)) {
                  include $page . '/index.php';
                } else {
                  include $page . '.php';
                }
              }
              ?>
            </div>
          </div>
        </div>
        <!--end::Container-->
      </div>
      <!--begin::Modal for confirmation-->
      <!-- Confirmation Modal -->
      <div class="modal fade" id="confirm_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5">Confirmation</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="confirm">Continue</button>
            </div>
          </div>
        </div>
      </div>
      <!--end::Modal for confirmation-->
      <!--end::App Content-->
    </main>
    <!--end::App Main-->
    <?php include './includes/footer_text.php'; ?>
    <?php require_once './includes/footer.php'; ?>
  </div>
  <!--end::App Wrapper-->
  <!--begin::Script-->
</body>
<!--end::Body-->

</html>
