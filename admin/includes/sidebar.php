<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="./index.html" class="brand-link">
      <!--begin::Brand Image-->
      <img src="../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">AdminLTE 4</span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
        <li class="nav-item dropdown">
          <a href="./" class="nav-link nav-home">
            <i class="nav-icon fa-solid fa-house"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-header">
          Contents
        </li>
        <li class="nav-item dropdown">
          <a href="<?= base_url ?>admin/?page=about" class="nav-link nav-about">
            <i class="nav-icon fa-solid fa-circle-info"></i>
            <p>
              About
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="<?= base_url ?>admin/?page=education" class="nav-link nav-education">
            <i class="nav-icon fa-solid fa-graduation-cap"></i>
            <p>
              Educational Attainment
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="<?= base_url ?>admin/?page=work" class="nav-link nav-work">
            <i class="nav-icon fa-solid fa-user-tie"></i>
            <p>
              Work
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="<?= base_url ?>admin/?page=projects" class="nav-link nav-projects">
            <i class="nav-icon fa-solid fa-list-check"></i>
            <p>
              Projects
            </p>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a href="<?= base_url ?>admin/?page=contact" class="nav-link nav-contact">
            <i class="nav-icon fa-solid fa-id-card"></i>
            <p>
              Contact
            </p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<script>
  $(function () {
    var page = '<?= isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      page = page.split('/');
      page = page[0];
      // console.log(page); 

    if($('.nav-link.nav-'+page).length > 0) {
      $('.nav-link.nav-'+page).addClass('active');
    }
  });
</script>
