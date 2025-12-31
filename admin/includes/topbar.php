<?php
// Log the contents of $_SESSION['userdata'] to the error log
// error_log(print_r(isset($_SESSION['userdata']) ? $_SESSION['userdata'] : 'No userdata found.', true));
?>
<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
          <i class="bi bi-list"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">
          <?php
          $name = $_settings->info('system_name');
          $short_name = $_settings->info('short_name');
          if (!isMobileDevice()) {
            $name_to_display = !empty($name) ? $name : (!empty($short_name) ? $short_name : 'PortfolioName');
            echo $name_to_display;
            // error_log($name_to_display); // Log the actual output name
          } else {
            $short_name_to_display = !empty($short_name) ? $short_name : (!empty($name) ? $name : 'PortfolioName');
            echo $short_name_to_display;
            // error_log($short_name_to_display); // Log the system name or short name based on device type
          }
          ?>
        </a></li>
    </ul>
    <!--end::Start Navbar Links-->
    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto">
      <!--begin::Navbar Search-->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="bi bi-search"></i>
        </a>
      </li>
      <!--end::Navbar Search-->
      <!--begin::System Settings-->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url . 'admin/?page=system_info' ?>" role="button">
          <i class="bi bi-gear"></i>
        </a>
      </li>
      <!--end::System Settings-->
      <!--begin::Fullscreen Toggle-->
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
      </li>
      <!--end::Fullscreen Toggle-->
      <!--begin::User Menu Dropdown-->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
          <!-- <img src="../dist/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="User Image" /> -->
          <?php
          $userImg = validate_image($_settings->userdata('avatar'));
          // error_log("User image URL: $userImg"); // Log the user image URL for debugging
          ?>
          <img src="<?= $userImg ?>" class="user-image rounded-circle shadow" alt="User Image" />
          <span class="d-none d-md-inline">
            <?php
            $firstname = $_settings->userdata('firstname');
            $lastname = $_settings->userdata('lastname');
            $fullname_to_display = !empty($firstname) && !empty($lastname) ? ucwords($firstname . ' ' . $lastname) : 'Guest';
            // Display the full name or a default message if not available
            echo $fullname_to_display;
            // error_log($fullname_to_display); // Log the full name for debugging purposes
            ?>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
          <!--begin::User Image-->
          <li class="user-header text-bg-primary">
            <img src="<?= $userImg ?>" class="rounded-circle shadow" alt="User Image" />
            <p>
              <?php
              echo $fullname_to_display;
              ?>
              <small><?= $_settings->userdata('short_desc') ?: 'Short Description' ?></small>
            </p>
          </li>
          <!--end::User Image-->
          <!--begin::Menu Body-->
          <li class="user-body">
            <!--begin::Row-->
            <div class="row">
              <div class="col-4 text-center"><a href="#">Followers</a></div>
              <div class="col-4 text-center"><a href="#">Sales</a></div>
              <div class="col-4 text-center"><a href="#">Friends</a></div>
            </div>
            <!--end::Row-->
          </li>
          <!--end::Menu Body-->
          <!--begin::Menu Footer-->
          <li class="user-footer">
            <a href="<?= base_url ?>admin/?page=user_profile" class="btn btn-outline-primary btn-flat">Profile</a>
            <a href="#" id="logoutBtn" class="btn btn-outline-danger btn-sm float-end d-flex align-items-center">
              <i class="fa fa-power-off me-2"></i>Sign out</a>
          </li>
          <!--end::Menu Footer-->
        </ul>
      </li>
      <!--end::User Menu Dropdown-->
    </ul>
    <!--end::End Navbar Links-->
  </div>
  <!--end::Container-->
</nav>
<!--end::Header-->

<script>
  $('#logoutBtn').click(function (e) {
    e.preventDefault();
    start_loader(); // show loader
    $.ajax({
      url: _base_url_ + 'classes/Login.php?f=logout',
      method: 'POST',
      dataType: 'json',
      // data: {
      //     csrf_token: $('meta[name="csrf-token"]').attr('content')
      // },
      success: function (resp) {
        // console.log("AJAX response:", resp); // 👈 DEBUG output
        if (resp.status == 'success') {
          window.location.href = _base_url_ + 'admin/login.php';
        } else {
          alert_toast("Logout failed", 'error');
        }
        end_loader(); // hide loader
      },
      error: function () {
        alert_toast("An error occurred while logging out.", 'error');
        end_loader(); // hide loader
      }
      // success: function(){
      //     window.location.href = _base_url_ + 'admin/login.php';
      // },
      // error: function(){
      //     alert_toast("Logout failed", 'error');
      //     end_loader();
      // }
    });
  });
</script>
