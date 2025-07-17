<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Hang</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo2.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Datatable -->
    <link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
</head>

<body style="margin: 0; height: 100%; overflow: hidden" class="bg-white">
    <div class="row">
        <div class="col-8">
            <img src="{{ asset('images/img-login2.png') }}" alt="Image Sidebar" height="100%" width="100%" class="m-0" />
        </div>
        <div class="col-sm ">
            <div class="text-center mb-3 mt-5">
                <img src="{{ asset('images/logo-text2.png') }}" style="max-width: 150px;" alt="">
            </div>
            <div class="ml-5">
                <br>
                <h1 class="mb-3 mt-5"><strong>MASUK</strong></h1>
                <form action="{{ url('auth') }}" method="post">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-9 mb-4">
                            <label class="mb-1 ">Username</label>
                            {{-- Change is here --}}
                            <input type="text" onkeypress="return preventSpace(event)" name="username" id="input_username" class="form-control" placeholder="Username" required style="border: 1px solid #017025;">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-9 mb-4">
                            <label class="mb-1">Password</label>
                            {{-- Change is here --}}
                            <input type="password" name="password" id="input_password" class="form-control" placeholder="Password" required style="border: 1px solid #017025;">
                        </div>
                    </div>
                    
                    @if (session('err_msg'))
                    <br>
                    <div class="alert alert-danger mr-5">{!! session('err_msg') !!}</div>
                    @endif
                    
                    {{-- Change is here --}}
                    <button type="submit" class="btn btn-md mt-4" style="background-color: #017025; border-color: #017025; color: white;">Masuk</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="copyright">
            {{-- <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> 2021</p> --}}
        </div>
    </div>

    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
    <script src="{{ asset('js/deznav-init.js') }}"></script>
    <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>

    <script src="{{ asset('vendor/peity/jquery.peity.min.js') }}"></script>

    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>

    <script>
        function carouselReview() {
            /*  event-bx one function by = owl.carousel.js */
            jQuery('.event-bx').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                center: true,
                autoplaySpeed: 3000,
                navSpeed: 3000,
                paginationSpeed: 3000,
                slideSpeed: 3000,
                smartSpeed: 3000,
                autoplay: false,
                navText: ['<i class="fa fa-caret-left" aria-hidden="true"></i>',
                    '<i class="fa fa-caret-right" aria-hidden="true"></i>'
                ],
                dots: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    720: {
                        items: 2
                    },

                    1150: {
                        items: 3
                    },

                    1200: {
                        items: 2
                    },
                    1749: {
                        items: 3
                    }
                }
            })
        }
        jQuery(window).on('load', function() {
            setTimeout(function() {
                carouselReview();
            }, 1000);
        });
        function preventSpace(evt){
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if(charCode == 32){
            return false
            }
            return true
        }
    </script>
</body>

</html>
