 <!--**********************************
            Footer start
        ***********************************-->
 <div class="footer">
 	<div class="copyright">
 		{{-- <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> 2021</p> --}}
 	</div>
 </div>
 <!--**********************************
            Footer end
        ***********************************-->

 <!--**********************************
           Support ticket button start
        ***********************************-->

 <!--**********************************
           Support ticket button end
        ***********************************-->


 </div>
 <!--**********************************
        Main wrapper end
    ***********************************-->

 <!--**********************************
        Scripts
    ***********************************-->
 <!-- Required vendors -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="{{ asset('vendor/global/global.min.js') }}"></script>
 <script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
 <script src="{{ asset('vendor/chart.js/Chart.bundle.min.js') }}"></script>
 <script src="{{ asset('js/custom.min.js') }}"></script>
 <script src="{{ asset('js/deznav-init.js') }}"></script>
 <script src="{{ asset('vendor/owl-carousel/owl.carousel.js') }}"></script>
 <!-- <script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script> -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <!-- Chart piety plugin files -->
 <script src="{{ asset('vendor/peity/jquery.peity.min.js') }}"></script>


 {{-- ApexCharts --}}
 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

 {{-- FlatPicker --}}
 <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
 <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

 <!-- Apex Chart -->
 {{-- <script src="{{ asset('vendor/apexchart/apexchart.js') }}"></script> --}}

 <!-- Dashboard 1 -->
 {{-- <script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script> --}}
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 <!-- pickdate -->
 <script src="{{ asset('vendor/pickadate/picker.js') }}"></script>
 <script src="{{ asset('vendor/pickadate/picker.time.js') }}"></script>
 <script src="{{ asset('vendor/pickadate/picker.date.js') }}"></script>

 <script src="{{ asset('vendor/toastr/js/toastr.min.js') }}"></script>

 <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-fixedcolumns/4.3.1/dataTables.fixedColumns.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-fixedheader/4.0.1/dataTables.fixedHeader.min.js"></script>

 <script>
 	$(document).ready(function() {
 		$('.select2').select2();

 		if ($('.select2-toko-rute').length > 0) {
 			const url = '<?= url('master/rute/search-shops') ?>';
 			$('.select2-toko-rute').select2({
 				ajax: {
 					url: url,
 					dataType: 'json',
 					delay: 250,
 					processResults: function(data) {
 						return {
 							results: $.map(data, function(item) {
 								return {
 									text: item.NAME_SHOP + ' - ' + item.NAME_AREA,
 									id: item.ID_SHOP
 								}
 							})
 						};
 					},
 					cache: true
 				},
 				minimumInputLength: 1
 			});
 			<?php if (!empty($user_route)) { ?>
 				<?php foreach (explode(';', $user_route->ID_SHOP) as $key => $ID_SHOP) { ?>
 					$('.select2-toko-rute').append(new Option(`<?= explode(';', $user_route->NAME_SHOP)[$key] . '-' . explode(';', $user_route->NAME_AREA)[$key] ?>`, '<?= $ID_SHOP ?>', true, true)).trigger('change');
 				<?php } ?>
 			<?php } ?>
 		}
 	})

 	function carouselReview() {
 		/*  event-bx one function by = owl.carousel.js */
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
 			navText: ['<i class="fa fa-caret-left" aria-hidden="true"></i>', '<i class="fa fa-caret-right" aria-hidden="true"></i>'],
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

 	function num(evt) {
 		evt = (evt) ? evt : window.event;
 		var charCode = (evt.which) ? evt.which : evt.keyCode;
 		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
 			return false;
 		}
 		return true;
 	}

 	function alpha(e) {
 		var k;
 		document.all ? k = e.keyCode : k = e.which;
 		return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32);
 	}

 	function alphaNum(e) {
 		var k;
 		document.all ? k = e.keyCode : k = e.which;
 		return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
 	}

 	function alphaNumSpace(e) {
 		var charCode = (e.which) ? e.which : e.keyCode;
 		if (charCode == 32) {
 			return false
 		}

 		var k;
 		document.all ? k = e.keyCode : k = e.which;
 		return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57));
 	}

 	function preventSpace(evt) {
 		evt = (evt) ? evt : window.event;
 		var charCode = (evt.which) ? evt.which : evt.keyCode;
 		if (charCode == 32) {
 			return false
 		}
 		return true
 	}

 	const Toast = Swal.mixin({
 		toast: true,
 		position: "top-end",
 		showConfirmButton: false,
 		timer: 3000,
 		timerProgressBar: true,
 		didOpen: (toast) => {
 			toast.onmouseenter = Swal.stopTimer;
 			toast.onmouseleave = Swal.resumeTimer;
 		}
 	});
 </script>
 </body>

 </html>