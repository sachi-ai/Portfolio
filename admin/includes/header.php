<!--begin::Head-->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php
  // Change title based on current page
  $currentPage = basename($_SERVER['PHP_SELF']); // e.g., login.php or registration.php
  if ($currentPage === 'login.php') {
    $title = 'Login Page';
    ?>
    <link rel="icon" href="<?= base_url . 'dist/img/login.png' ?>">
    <?php
  } elseif ($currentPage === 'register.php') {
    $title = 'Sign Up Page';
    ?>
    <link rel="icon" href="<?= base_url . 'dist/img/register.png' ?>">
    <?php
  }
  $title = isset($title) ? $title : '';
  ?>
  <title><?= $title ?: $_settings->info('system_name') ?: 'Portfolio Website' ?></title>
  <?php
  $system_logo = $_settings->info('system_logo');
  $img_src = validate_image($system_logo);
  ?>
  <link rel="icon" href="<?= $img_src ?>">
  <!--begin::Primary Meta Tags-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="AdminLTE v4 | Dashboard" />
  <meta name="author" content="ColorlibHQ" />
  <meta name="description"
    content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS." />
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
  <!-- <meta name="csrf-token" content=""> -->
  <!--end::Primary Meta Tags-->

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!--begin::My css here-->
  <!-- Summernote lite CSS for bootstrap 5-->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
  <!--end::Summernote lite CSS for bootstrap 5-->
  <!--begin::SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!--end::SweetAlert2 -->

  <!--begin::Local css files-->
  <!--begin::Fontawesome Icon-->
  <link rel="stylesheet" href="<?= base_url ?>fontawesome/css/all.min.css" />
  <!--end::Fontawesome Icon-->
  <!--begin::custom css files-->
  <link rel="stylesheet" href="<?= base_url ?>dist/css/style.css" />
  <link rel="stylesheet" href="<?= base_url ?>dist/css/mediaquery.css" />
  <!--end::custom css files-->
  <!--end::Local css files-->

  <!--end::My css here-->

  <!--begin::Fonts-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
  <!--end::Fonts-->
  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
    integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->
  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->
  <!--begin::Required Plugin(AdminLTE)-->
  <link rel="stylesheet" href="../dist/css/adminlte.css" />
  <!--end::Required Plugin(AdminLTE)-->
  <!-- apexcharts -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
    integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
  <!-- jsvectormap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
    integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />

  <!--begin::DataTables CSS -->
  <link
    href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.3.3/b-3.2.4/b-colvis-3.2.4/b-html5-3.2.4/b-print-3.2.4/r-3.0.6/sl-3.1.0/datatables.min.css"
    rel="stylesheet" integrity="sha384-iqHRZpmlOcV7n/r6Kml7kdXbY1foX/nYlSCHUmOrYjSnOVMUnwJ9IsqFJly1R2RF"
    crossorigin="anonymous">
  <!--end::DataTables CSS-->

  <!--begin:: Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!--end:: Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

  <!--begin:: Bootstrap DatePicker CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css"
    rel="stylesheet">
  <style>
    .datepicker td,
    .datepicker th {
      width: 2.5rem;
      height: 2.5rem;
    }

    #map {
      height: 300px;
      width: 100%;
      border-radius: 8px;
      margin-top: 10px;
    }
  </style>
  <!--end:: Bootstrap DatePicker CSS -->

  <script src="<?= base_url ?>dist/js/script.js"></script>
  <script src="<?= base_url ?>dist/js/datatables-init.js"></script>
  <script>
    var _base_url_ = '<?= base_url ?>';
  </script>

</head>
<!--end::Head-->
