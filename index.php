<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<?php
$system_info = [];
$u_qry = $conn->query("SELECT * FROM `system_info`");
while ($row = $u_qry->fetch_assoc()) {
    $system_info[$row['meta_field']] = $row['meta_value'];
}
//   👉 Echo the full array to see its values
// echo '<pre>';
// print_r($system_info);
// echo '</pre>';
// $c_qry = $conn->query("SELECT * FROM contacts");
// while ($row = $c_qry->fetch_assoc()) {
//     $contact[$row['meta_field']] = $row['meta_value'];
// }
// var_dump($contact['facebook']);
?>

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title><?= $system_info['system_name'] ?: 'Portfolio Website' ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="vendors/flare/css/styles.css">
    <link rel="stylesheet" href="vendors/flare/css/vendor.css">
    <link rel="stylesheet" href="vendors/flare/css/custom.css">

    <!-- script
    ================================================== -->
    <script src="vendors/flare/js/modernizr.js"></script>
    <script defer src="vendors/flare/js/fontawesome/all.min.js"></script>

    <!-- favicons
    ================================================== -->
    <?php
    $system_logo = $system_info['system_logo'] ?? '';
    $img_src = validate_image($system_logo);
    ?>
    <link rel="vendors/flare/apple-touch-icon" sizes="180x180" href="vendors/flare/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $img_src ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $img_src ?>">
    <link rel="manifest" href="vendors/flare/site.webmanifest">

    <style>
        #hero-intro small {
            display: block;
            /* keep it on new line */
            margin-top: -0.3rem;
            /* pull it up closer to the first line */
            line-height: 1.2;
            /* optional: tighten text inside small */
        }

        .portfolio_img {
            width: 100%;
            height: 100%;
            /* adjust height to your preferred size */
            overflow: hidden;
        }

        .portfolio_img img {
            width: 100%;
            height: 400px;
            object-fit: fill;
        }
    </style>
</head>

<?php
$user = [];
$u_qry = $conn->query("SELECT * FROM `user` where id = 1");
foreach ($u_qry->fetch_array() as $k => $v) {
    if (!is_numeric($k)) {
        $user[$k] = $v;
    }
}
//   👉 Echo the full array to see its values
// echo '<pre>';
// print_r($user);
// echo '</pre>';
$c_qry = $conn->query("SELECT * FROM contacts");
while ($row = $c_qry->fetch_assoc()) {
    $contact[$row['meta_field']] = $row['meta_value'];
}
// var_dump($contact['facebook']);
?>

<body id="top">


    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader" class="dots-fade">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>


    <!-- header
    ================================================== -->
    <header class="s-header">

        <div class="s-header__logo">
            <a href="index.php">
                <img src="vendors/flare/images/logo.svg" alt="Homepage">
            </a>
        </div>

        <div class="s-header__content">

            <nav class="s-header__nav-wrap">
                <ul class="s-header__nav">
                    <li><a class="smoothscroll" href="#hero" title="Intro">Home</a></li>
                    <li><a class="smoothscroll" href="#about" title="About">About</a></li>
                    <li><a class="smoothscroll" href="#resume" title="Resume">Resume</a></li>
                    <li><a class="smoothscroll" href="#portfolio" title="Projects">Projects</a></li>
                </ul>
            </nav> <!-- end s-header__nav-wrap -->

            <a href="mailto:#0" class="btn btn--primary btn--small">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M24 0l-6 22-8.129-7.239 7.802-8.234-10.458 7.227-7.215-1.754 24-12zm-15 16.668v7.332l3.258-4.431-3.258-2.901z" />
                </svg>
                Get In Touch
            </a>

        </div> <!-- end header-content -->

        <a class="s-header__menu-toggle" href="#0"><span>Menu</span></a>

    </header> <!-- end s-header -->


    <!-- hero
    ================================================== -->
    <section id="hero" class="s-hero target-section">

        <div class="s-hero__bg">
            <div class="gradient-overlay"></div>
        </div>

        <div class="row s-hero__content">
            <div class="column">

                <h1>Hello.</h1>
                <div class="s-hero__content-about">

                    <p id="hero-intro">
                        I'm <?= isset($user['firstname']) ? $user['firstname'] : 'Firstname' ?>
                        <?= isset($user['lastname']) ? $user['lastname'] : 'lastname' ?>
                        <small><?= $user['short_desc'] ?: 'Short description here...' ?></small> <br> <br>
                    </p>

                    <footer>
                        <div class="s-hero__content-social">
                            <a href="#0"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
                            <a href="#0"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                            <a href="#0"><i class="fab fa-instagram" aria-hidden="true"></i></a>
                            <a href="#0"><i class="fab fa-dribbble" aria-hidden="true"></i></a>
                        </div>
                    </footer>
                </div>

            </div>
        </div>

        <div class="s-hero__video">
            <a class="s-hero__video-link"
                href="https://player.vimeo.com/video/117310401?color=01aef0&amp;title=0&amp;byline=0&amp;portrait=0"
                data-lity="">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M21 12l-18 12v-24z" />
                </svg>
                <span class="s-hero__video-text">Play Video</span>
            </a>
        </div>

        <div class="s-hero__scroll">
            <a href="#about" class="s-hero__scroll-link smoothscroll">
                Scroll Down
            </a>
        </div>

    </section> <!-- end s-hero -->


    <!-- about
    ================================================== -->
    <section id="about" class="s-about">

        <div class="horizontal-line"></div>

        <div class="row">
            <div class="column large-12">

                <div class="section-title" data-num="01" data-aos="fade-up">
                    <h3 class="h6">About Me</h3>
                </div>

            </div>

            <div class="column large-6 w-900-stack s-about__intro-text">
                <!-- <h1 class="display-1" data-aos="fade-up">
                    
                </h1> -->

                <p class="lead" data-aos="fade-up">
                    <?php include 'about.html' ?>
                </p>
            </div>

            <div class="column large-6 w-900-stack " data-aos="fade-up">
                <h3 class="item-title">Contact Details</h3>
                <p>
                    <strong>Address:</strong> <?= isset($contact['address']) ? $contact['address'] : 'N/A' ?><br>
                    <strong>Phone:</strong> <a
                        href="tel:<?= isset($contact['mobile']) ? $contact['mobile'] : 'N/A' ?>"><?= isset($contact['mobile']) ? $contact['mobile'] : 'N/A' ?></a><br>
                    <strong>Email:</strong> <a
                        href="mailto:<?= isset($contact['email']) ? $contact['email'] : 'N/A' ?>"><?= isset($contact['email']) ? $contact['email'] : 'N/A' ?></a><br>
                    <strong>Website:</strong> <a href="<?= isset($contact['website']) ? $contact['website'] : '#' ?>"
                        target="_blank"
                        rel="noopener noreferrer"><?= isset($contact['website']) ? $contact['website'] : 'N/A' ?></a>
            </div>
            <!-- <div class="column large-6 w-900-stack s-about__photo-block">
                <div class="s-about__photo" data-aos="fade-up"></div>
            </div> -->
        </div>

        <!-- <div class="row block-large-1-2 block-tab-full s-about__process item-list">
            <div class="column item item-process" data-aos="fade-up">
                <h3 class="item-title">Define</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-process" data-aos="fade-up">
                <h3 class="item-title">Design</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-process" data-aos="fade-up">
                <h3 class="item-title">Build</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-process" data-aos="fade-up">
                <h3 class="item-title">Launch</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
        </div> -->

    </section> <!-- end s-about -->


    <!-- resume
    ================================================== -->
    <section id="resume" class="s-services">

        <div class="row">
            <div class="column large-3 w-900-stack">
                <div class="section-title" data-num="02" data-aos="fade-up">
                    <h3 class="h6 mb-1">Resume</h3>
                </div>
            </div>

            <div class="column large-9 w-900-stack " data-aos="fade-up"
                style="display: flex; align-items: flex-end; align-items: flex-start; justify-content: center; min-height: 120px;">
                <p class="lead download">You can download my résumé in PDF format by clicking &nbsp; <a href="">
                        here...</a></p>
            </div>
            <div class="column large-3 w-900-stack">
                <h1 class="display-1" data-aos="fade-up">
                    <span>Education</span>
                </h1>
            </div>
            <div class="column large-9 w-900-stack education" data-aos="fade-up">
                <?php
                $educ_qry = $conn->query("SELECT * FROM `education` order by `year` desc, `month` desc");
                while ($row = $educ_qry->fetch_assoc()) {
                    echo '<h3 class="display-1" data-aos="fade-up">' . htmlspecialchars($row['school']) . '</h3>';
                    echo '<p class="info" data-aos="fade-up">' . htmlspecialchars($row['degree']) . ' <span>&bull;</span> <em class="date">' . htmlspecialchars($row['month']) . ' ' . htmlspecialchars($row['year']) . '</em></p>';
                    if (!empty($row['description'])) {
                        echo '<p data-aos="fade-up">' . $row['description'] . '</p>';
                    }
                }
                ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="column large-3 w-900-stack">
                <h1 class="display-1" data-aos="fade-up">
                    <span>Work</span>
                </h1>
            </div>
            <div class="column large-9 w-900-stack work" data-aos="fade-up">
                <?php
                $work_qry = $conn->query("SELECT * FROM `work`");
                while ($row = $work_qry->fetch_assoc()) {
                    echo '<h3 class="display-1" data-aos="fade-up">' . htmlspecialchars($row['company']) . '</h3>';
                    echo '<p class="info" data-aos="fade-up">' . htmlspecialchars($row['position']) . ' <span>&bull;</span> <em class="date">' . str_replace("_", " ", htmlspecialchars($row['start_date'])) . '-' . str_replace("_", " ", htmlspecialchars($row['end_date'])) . '</em></p>';
                    if (!empty($row['description'])) {
                        echo '<p data-aos="fade-up">' . $row['description'] . '</p>';
                    }
                }
                ?>
                </p>
            </div>
        </div>

        <!-- <h3><span>Education</span></h3> -->

        <!-- <div class="row block-large-1-2 block-tab-full s-services__services item-list">
            <div class="column item item-service" data-aos="fade-up">
                <span class="service-icon service-icon--product-design"></span>
                <h3 class="item-title">Product Design</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-service" data-aos="fade-up">
                <span class="service-icon service-icon--branding"></span>
                <h3 class="item-title">Branding</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-service" data-aos="fade-up">
                <span class="service-icon service-icon--frontend"></span>
                <h3 class="item-title">Frontend Development</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-service" data-aos="fade-up">
                <span class="service-icon service-icon--research"></span>
                <h3 class="item-title">UX Research</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-service" data-aos="fade-up">
                <span class="service-icon service-icon--illustration"></span>
                <h3 class="item-title">Illustration</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
            <div class="column item item-service" data-aos="fade-up">
                <span class="service-icon service-icon--ecommerce"></span>
                <h3 class="item-title">E-Commerce</h3>
                <p>
                    Deserunt rerum perspiciatis quaerat quam numquam assumenda neque.
                    Quis dolores totam voluptatibus molestiae non. Quae exercitationem
                    cum numquam repudiandae. Beatae eum quae. Ut ex unde rem quod
                    ipsum consequatur. blanditiis temporibus pariatur voluptatibus molestiae.
                </p>
            </div>
        </div> -->

        <section id="portfolio" class="s-portfolio">

            <div class="row s-porfolio__top">
                <div class="column large-6 w-900-stack">
                    <div class="section-title" data-num="03" data-aos="fade-up">
                        <h3 class="h6">Recent Works</h3>
                    </div>
                </div>
                <div class="column large-6 w-900-stack">
                    <h1 class="display-1" data-aos="fade-up">
                        Here are some of my projects I’ve done lately. Feel free to check them out.
                    </h1>
                </div>
            </div>

            <div class="row s-portfolio__list block-large-1-2 block-tab-full collapse">

                <?php
                $work_qry = $conn->query("SELECT * FROM `projects`");
                while ($row = $work_qry->fetch_assoc()):
                    ?>
                    <div class="column" data-aos="fade-up">
                        <div class="folio-item">

                            <div class="folio-item__thumb portfolio_img">
                                <a class="folio-item__thumb-link" href="<?= validate_image($row['project_banner']) ?>"
                                    title="<?= htmlspecialchars($row['project_name']) ?>" data-size="1050x700">
                                    <img src="<?= validate_image($row['project_banner']) ?>"
                                        srcset="<?= validate_image($row['project_banner']) ?> 1x, <?= validate_image($row['project_banner']) ?> 2x"
                                        alt="<?= htmlspecialchars($row['project_name']) ?>">
                                </a>
                            </div>

                            <div class="folio-item__info">
                                <h4 class="folio-item__title"><?= htmlspecialchars($row['project_name']) ?></h4>
                            </div>

                            <!--  use normalize_link when rendering project link -->
                            <a href="<?= htmlspecialchars(normalize_link($row['project_link'] ?? '')) ?>"
                                title="Project Link" class="folio-item__project-link" target="_blank"
                                rel="noopener noreferrer">Project Link</a>

                            <div class="folio-item__caption">
                                <p><?= $row['description'] ?? 'No description available.' ?></p>
                                <div>
                                    <a href="<?= htmlspecialchars(normalize_link($row['project_link'] ?? '')) ?>"
                                       class="project_link" target="_blank" rel="noopener noreferrer">Click here to redirect to website...</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div> <!-- end s-portfolio__list -->

        </section>

        <!-- footer
    ================================================== -->
        <footer class="s-footer">
            <div class="row row-y-top">

                <div class="column large-8 medium-12">
                    <div class="row">
                        <div class="column large-7 tab-12 s-footer__block">
                            <h4 class="h6">Address</h4>

                            <p>
                                Subangdaku <br>
                                Mandaue City, Cebu <br>
                                6000 <br>
                                <a href="tel:<?= isset($contact['mobile']) ? $contact['mobile'] : 'N/A' ?>"><?= isset($contact['mobile']) ? $contact['mobile'] : 'N/A' ?></a>
                            </p>
                        </div>

                        <div class="column large-5 tab-12 s-footer__block">
                            <h4 class="h6">Follow Us</h4 class="h6">

                            <ul class="s-footer__list">
                                <li><a href="#0">Facebook</a></li>
                                <li><a href="#0">Twitter</a></li>
                                <li><a href="#0">Instagram</a></li>
                                <li><a href="#0">Dribbble</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="column large-4 medium-12 s-footer__block--end">
                    <a href="mailto:#0" class="btn h-full-width">Let's Talk</a>

                    <div class="ss-copyright">
                        <span>Copyright Flare 2020</span>
                        <span>Design by <a href="https://www.styleshout.com/">StyleShout</a></span>
                    </div>
                </div>

                <div class="ss-go-top">
                    <a class="smoothscroll" title="Back to Top" href="#top">
                        top
                    </a>
                </div> <!-- end ss-go-top -->

            </div>
        </footer>


        <!-- photoswipe background
    ================================================== -->
        <div aria-hidden="true" class="pswp" role="dialog" tabindex="-1">

            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">

                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>

                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div><button class="pswp__button pswp__button--close"
                            title="Close (Esc)"></button> <button class="pswp__button pswp__button--share"
                            title="Share"></button> <button class="pswp__button pswp__button--fs"
                            title="Toggle fullscreen"></button> <button class="pswp__button pswp__button--zoom"
                            title="Zoom in/out"></button>
                        <div class="pswp__preloader">
                            <div class="pswp__preloader__icn">
                                <div class="pswp__preloader__cut">
                                    <div class="pswp__preloader__donut"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div><button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>

            </div>

        </div> <!-- end photoSwipe background -->


        <!-- Java Script
    ================================================== -->
        <script src="vendors/flare/js/jquery-3.2.1.min.js"></script>
        <script src="vendors/flare/js/plugins.js"></script>
        <script src="vendors/flare/js/main.js"></script>

</body>

</html>
