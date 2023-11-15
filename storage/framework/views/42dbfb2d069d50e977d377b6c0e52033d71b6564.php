<header class="header">
    <div class="head-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-12 my-lg-0 my-2">
                    <ul class="head-contact-left">
                        <li>
                            <i class="fa fa-phone"></i>
                            <?php echo e($settings->company_phone); ?>

                        </li>

                    </ul>
                </div>
                <div class="col-lg-8 col-12 my-lg-0 my-2">
                    <ul class="head-contact-right">
                        <li class="location-search mb-3">
                            <span class="mr-2"> <?php echo app('translator')->getFromJson('front.location'); ?></span>
                            <div class="location-dropdown">
                                <div id="scrollable-dropdown-menu" class="input-wrap">
                                    <i class="fa fa-map-marker"></i>
                                    <select id="location" class="select2" name="location">
                                        <option value="0"><?php echo app('translator')->getFromJson('front.allLocations'); ?></option>
                                        <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($location->id); ?>"><?php echo e($location->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li class="language-drop mb-3">
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle text-capitalize" data-toggle="dropdown">
                                    <i class="fa fa-globe"></i> <?php echo app('translator')->getFromJson('front.language'); ?>
                                </a>
                                <div class="dropdown-menu">
                                    <?php $__empty_1 = true; $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <a class="dropdown-item" data-lang-code="<?php echo e($language->language_code); ?>" href="javascript:;"><?php echo e($language->language_name); ?></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <a class="dropdown-item active" href="javascript:;" data-lang-code="en">English</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <?php if($user): ?>
                                <form id="logoutForm" action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="dropdown add-items">
                                        <a href="javascript:;" class="dropdown-toggle"
                                           data-toggle="dropdown" aria-expanded="false"><?php echo e($user->name); ?><span class="fa fa-caret-down"></span>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>">
                                                <i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('front.myAccount'); ?></a>
                                            <a class="dropdown-item" href="javascript:;" onclick="logoutUser(event)">
                                                <i class="fa fa-sign-out mr-2"> </i><?php echo app('translator')->getFromJson('app.logout'); ?></a>
                                        </div>
                                    </div>
                                </form>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>">
                                    <i class="fa fa-user mr-2"> </i><?php echo app('translator')->getFromJson('app.signIn'); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <nav class="topbar">
        <div class="container">
            <div class="row h-center">
                <div class="col-lg-5 col-md-3 col-12">
                    <div class="logo">
                        <a href="<?php echo e(route('front.index')); ?>">
                            <img src="<?php echo e($frontThemeSettings->logo_url); ?>" alt="logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-9 col-12">
                    <ul class="d-flex h-center justify-content-md-end py-3 ml-md-5 ml-0">
                        <li class="search-form">
                            <form id="searchForm" action="<?php echo e(route('front.searchServices')); ?>" method="GET">
                                <span class="input-wrap">
                                    <i class="fa fa-search"></i>
                                    <input type="text" class="form-control" name="search_term" id="search_term"
                                        placeholder="<?php echo app('translator')->getFromJson('front.searchHere'); ?>" autocomplete="none">
                                </span>
                                <button type="submit" class="submit btn btn-custom br-0 btn-dark w-100">
                                    <?php echo app('translator')->getFromJson('front.search'); ?></button>
                            </form>
                        </li>
                        <li title="<?php echo app('translator')->getFromJson('front.cart'); ?>" class="top-cart">
                            <a href="<?php echo e(route('front.cartPage')); ?>">
                                <i class="fa fa-shopping-bag"></i>
                                <span class="cart-badge"><?php echo e($productsCount); ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

<?php $__env->startPush('footer-script'); ?>
    <script>
        var substringMatcher = function(strs) {
            return function findMatches(q, cb) {
                var matches, substringRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        matches.push(str);
                    }
                });

                cb(matches);
            };
        };

        $(function () {

            var countries = <?php echo $locations; ?>.map(location => location.name);

            countries.unshift('<?php echo app('translator')->getFromJson('front.allLocations'); ?>');

            $('.select2').select2();

            if (localStorage.getItem('location')) {
                $('#location.select2').val(localStorage.getItem('location')).trigger('change');
            }
            else {
                $('#location.select2').val(0);
                localStorage.setItem('location', 0);
            }

            $('#location.select2').on('change', function() {
                localStorage.setItem('location', $(this).val());

                if (localStorage.getItem('location') !== '' && location.protocol+'//'+location.hostname+location.pathname == '<?php echo e(route('front.searchServices')); ?>') {
                    $('#searchForm').submit();
                }

                if (localStorage.getItem('location') !== '' && location.href == '<?php echo e(route('front.index').'/'); ?>') {
                    var url = '<?php echo e(route('front.index', ['location' => 'variable'])); ?>';
                    url = url.replace('variable', localStorage.getItem('location'));

                    $.easyAjax({
                        url: url,
                        type: 'GET',
                        success: function (response) {
                            var categories = `
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 mb-30 categories-list">
                                    <div class="ctg-item" style="background-image:url('<?php echo e(asset('assets/img/pl-slide1.jpg')); ?>')">
                                        <a href="javascript:;">
                                            <div class="icon-box">
                                                <i class="flaticon-fork"></i>
                                            </div>
                                            <div class="content-box">
                                                <h5 class="mb-0">
                                                    <?php echo app('translator')->getFromJson('front.all'); ?>
                                                </h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>`;

                            response.categories.forEach(category => {
                                if (category.services.length > 0) {
                                    var defaultAsset = '<?php echo e(asset('assets/img/pl-slide1.jpg')); ?>';
                                    var asset = '<?php echo e(asset('user-uploads/category/').'/'); ?>'+category.image;
                                    var url = category.image ?  asset : defaultAsset;
                                    categories += `
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-12 mb-30 categories-list" data-category-id="${category.id}">
                                    <div class="ctg-item" style="background-image:url('${url}')">
                                        <a href="javascript:;">
                                            <div class="icon-box">
                                                <i class="flaticon-fork"></i>
                                            </div>
                                            <div class="content-box">
                                                <h5 class="mb-0">
                                                    ${category.name}
                                                </h5>
                                            </div>
                                        </a>
                                    </div>
                                </div>`
                                }
                            });

                            $('#categories').html(categories);

                            var services = '';

                            if (response.services.length > 0) {
                                response.services.forEach(service => {
                                    var defaultAsset = '<?php echo e(asset('assets/img/pl-slide1.jpg')); ?>';
                                    var asset = "<?php echo e(asset('user-uploads/service/').'/'); ?>"+service.id+'/'+service.default_image;
                                    var url = service.image ?  asset : defaultAsset;

                                    services += `
                                    <div class="col-lg-4 col-md-6 col-12 mb-30 services-list service-category-${service.category_id}">
                                        <div class="listing-item">
                                            <div class="img-holder">
                                                <img src="${url}" alt="list">
                                                <div class="category-name">
                                                    <a href="#" class="c-black">
                                                        <i class="flaticon-fork mr-1"></i>${service.category.name}
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="list-content">
                                                <h5 class="mb-2">
                                                    <a href="${service.service_detail_url}">${service.name}</a>
                                                </h5>
                                                <ul class="ctg-info centering h-center v-center">
                                                    <li class="mt-1">
                                                        <div class="service-price">
                                                            <span class="unit"><?php echo e($settings->currency->currency_symbol); ?></span>${service.discounted_price}
                                                        </div>
                                                    </li>
                                                    <li class="mt-1">
                                                        <div class="dropdown add-items">
                                                            <a href="javascript:;" class="btn-custom btn-blue dropdown-toggle add-to-cart"
                                                        data-service-price="${service.discounted_price}"
                                                        data-service-id="${service.id}"
                                                        data-service-name="${service.name}"
                                                        aria-expanded="false">
                                                                <?php echo app('translator')->getFromJson('app.add'); ?>
                                                                <span class="fa fa-plus"></span>
                                                            </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>`
                                });

                            services += `
                            <div class="col-12 text-right">
                                <a href="javascript:;" onclick="goToPage('GET', '<?php echo e(route('front.bookingPage')); ?>')" class="btn btn-custom">
                                    <?php echo app('translator')->getFromJson('front.selectBookingTime'); ?>
                                    <i class="fa fa-angle-right ml-1"></i>
                                </a>
                            </div>`;
                            }
                            else {
                                services += `
                                <div class="col-12 text-center mb-5">
                                    <h3 class="no-services">
                                        <i class="fa fa-exclamation-triangle"></i> <?php echo app('translator')->getFromJson('front.noSearchRecordFound'); ?>
                                    </h3>
                                </div>`
                            }

                            $('#services').html(services);

                            $('html, body').animate({
                                scrollTop: $(".categories").offset().top
                            }, 1000);
                        }
                    })
                }
            });

            let searchParams = new URLSearchParams(window.location.search);
            if (searchParams.has('search_term')) {
                $('#search_term').val(searchParams.get('search_term'));
            }

            setActiveClassToLanguage();
        });

        function logoutUser(e) {
            e.preventDefault();
            $('#logoutForm').submit();
        }

        function setActiveClassToLanguage() {
            // language switcher
            if ('<?php echo e(\Cookie::has('appointo_language_code')); ?>') {
                $('.language-drop .dropdown-item').filter(function () {
                    return $(this).data('lang-code') === '<?php echo e(\Cookie::get('appointo_language_code')); ?>'
                }).addClass('active');
            }
            else {
                $('.language-drop .dropdown-item').filter(function () {
                    return $(this).data('lang-code') === '<?php echo e($settings->locale); ?>'
                }).addClass('active');
            }
        }

        $('#searchForm').on('submit', function (e) {
            var searchTerm = $('#search_term').val();

            if (searchTerm.length == 0) {
                return false;
            }

            $("<input />").attr("type", "hidden")
                .attr("name", "location")
                .attr("value", localStorage.getItem('location'))
                .appendTo("#searchForm");
        });

        $('.language-drop .dropdown-item').click(function () {
            let code = $(this).data('lang-code');

            let url = '<?php echo e(route('front.changeLanguage', ':code')); ?>';
            url = url.replace(':code', code);

            if (!$(this).hasClass('active')) {
                $.easyAjax({
                    url: url,
                    type: 'POST',
                    container: 'body',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            location.reload();
                            setActiveClassToLanguage();
                        }
                    }
                })
            }
        })
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH G:\Pekerjaan\Simetris\salon\salon\resources\views/sections/header.blade.php ENDPATH**/ ?>