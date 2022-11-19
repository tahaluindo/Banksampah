
$(window).scroll(function () {
	var scroll = $(window).scrollTop();
	var box = $('.header-text').height();
	var header = $('header').height();

	if (scroll >= box - header) {
		$("header").addClass("background-header");
	} 
	else {
		$("header").removeClass("background-header");
	}
});

// Window Resize Mobile Menu Fix
mobileNav();


// Scroll animation init
window.sr = new scrollReveal();


// Menu Dropdown Toggle
if ($('.menu-trigger').length) {
	$(".menu-trigger").on('click', function () {
		$(this).toggleClass('active');
		$('.header-area .nav').slideToggle(200);
	});
}

// Menu elevator animation
$('a[href*=\\#]:not([href=\\#])').on('click', function () {
	if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
		var targetHash = this.hash;
		var target = $(this.hash);
		target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
		if (target.length) {
			var width = $(window).width();
			if (width < 991) {
				$('.menu-trigger').removeClass('active');
				$('.header-area .nav').slideUp(200);
			}
			$('html,body').animate({
				scrollTop: (target.offset().top)
			}, 700, 'swing', function () {
				window.location.hash = targetHash;
			});
			return false;
		}
	}
});

$(document).ready(function () {
	$('a[href^="#welcome"]').addClass('active');

	//smoothscroll
	$('.menu-item').on('click', function (e) {
		if ($(this).html().toLowerCase() !== "penghargaan") {
			e.preventDefault();
			var athis = this;
			var target = this.hash,
			menu = target;
			var $target = $(target);

			$('html, body').stop().animate({
				'scrollTop': $target.offset().top
			}, 500, 'swing', function () {
				window.location.hash = target;
				$('.menu-item').removeClass('active');
				$(athis).addClass('active');
			});
		}
	});

	$(window).scroll(function (event) {
		var scrollPos = $(document).scrollTop() + 80;

		if (scrollPos === 0) {
			$('a[href^="#welcome"]').addClass('active');
			return;
		}
		$('.menu-item').not('[href=""]').not('[href="javascript:;"]').each(function () {
			var currLink = $(this);
			var refElement = $(currLink.attr("href"));

			if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
				$('.menu-item').removeClass("active");
				currLink.addClass("active");
			} else {
				currLink.removeClass("active");
			}
		});
	})
});

const Accordion = {
	settings: {
		// Expand the first item by default
		first_expanded: false,
		// Allow items to be toggled independently
		toggle: false
	},

	openAccordion: function (toggle, content) {
		if (content.children.length) {
			toggle.classList.add("is-open");
			let final_height = Math.floor(content.children[0].offsetHeight);
			content.style.height = final_height + "px";
		}
	},

	closeAccordion: function (toggle, content) {
		toggle.classList.remove("is-open");
		content.style.height = 0;
	},

	init: function (el) {
		const _this = this;

		// Override default settings with classes
		let is_first_expanded = _this.settings.first_expanded;
		if (el.classList.contains("is-first-expanded")) is_first_expanded = true;
		let is_toggle = _this.settings.toggle;
		if (el.classList.contains("is-toggle")) is_toggle = true;

		// Loop through the accordion's sections and set up the click behavior
		const sections = el.getElementsByClassName("accordion");
		const all_toggles = el.getElementsByClassName("accordion-head");
		const all_contents = el.getElementsByClassName("accordion-body");
		for (let i = 0; i < sections.length; i++) {
			const section = sections[i];
			const toggle = all_toggles[i];
			const content = all_contents[i];

			// Click behavior
			toggle.addEventListener("click", function (e) {
				if (!is_toggle) {
					// Hide all content areas first
					for (let a = 0; a < all_contents.length; a++) {
						_this.closeAccordion(all_toggles[a], all_contents[a]);
					}

					// Expand the clicked item
					_this.openAccordion(toggle, content);
				} else {
					// Toggle the clicked item
					if (toggle.classList.contains("is-open")) {
						_this.closeAccordion(toggle, content);
					} else {
						_this.openAccordion(toggle, content);
					}
				}
			});

			// Expand the first item
			if (i === 0 && is_first_expanded) {
				_this.openAccordion(toggle, content);
			}
		}
	}
};

(function () {
	// Initiate all instances on the page
	const accordions = document.getElementsByClassName("accordions");
	for (let i = 0; i < accordions.length; i++) {
		Accordion.init(accordions[i]);
	}
})();



// Home seperator
if ($('.home-seperator').length) {
	$('.home-seperator .left-item, .home-seperator .right-item').imgfix();
}


// Home number counterup
if ($('.count-item').length) {
	$('.count-item strong').counterUp({
		delay: 10,
		time: 1000
	});
}


// Page loading animation
$(window).on('load', function () {
	if ($('.cover').length) {
		$('.cover').parallax({
			imageSrc: $('.cover').data('image'),
			zIndex: '1'
		});
	}

	$("#preloader").animate({
		'opacity': '0'
	}, 600, function () {
		setTimeout(function () {
			$("#preloader").css("visibility", "hidden").fadeOut();
		}, 300);
	});
});


// Window Resize Mobile Menu Fix
$(window).on('resize', function () {
	mobileNav();
});


// Window Resize Mobile Menu Fix
function mobileNav() {
	var width = $(window).width();
	$('.submenu').on('click', function () {
		if (width < 992) {
			$('.submenu ul').removeClass('active');
			$(this).find('ul').toggleClass('active');
		}
	});
}

//SIGN UP
$(function () {
	$('input, select').on('focus', function () {
		$(this).parent().find('.input-group-text').css('border-color', '#80bdff');
	});
	$('input, select').on('blur', function () {
		$(this).parent().find('.input-group-text').css('border-color', '#ced4da');
	});
});

/* 
-------------- 
get total sampah
--------------
*/
axios.get(APIURL+'/transaksi/sampahmasuk')
.then(res => {
	let totalSampah = res.data.data;

	for (const key in totalSampah) {
		$(`#sampah-${key.replace(/\s/g,'-')}`).html(parseFloat(totalSampah[key].total).toFixed(1));
	}

	counterUp();
}) 
.catch(res => {
})

// Counter Up Data Rubbish
let counterUp = () => {
	$('.counter-value').each(function () {
		$(this).prop('Counter', 0).animate({
			Counter: $(this).text()
		}, {
			duration: 3500,
			easing: 'swing',
			step: function (now) {
				$(this).text(Math.ceil(now));
			}
		});
	});
}

/**
 * Get Kategori Artikel
 */
 function getKategoriArtikel() {
	$('#activity').removeClass("none");

	axios.get(`${APIURL}/artikel/getkategori`)
	 .then(res => {
		let kategoriUtama = res.data.data.filter(e => e.kategori_utama == "1");
		
		if (kategoriUtama.length < 3) {
			$('#activity').addClass("none");
		} 
		else {
			$('.card-activity').each(function (e){
				$('.features-item').addClass('card-shadow');
				$('.features-item').removeClass('skeleton');
				$(this).find('.icon').attr('src',kategoriUtama[e].icon);
				$(this).find('.name').html(kategoriUtama[e].name);
				$(this).find('.description').html(kategoriUtama[e].description);
				$(this).find('.button').html(`
					<a href="${BASEURL}/homepage/${kategoriUtama[e].name}" class="main-button">
						Read More
					</a>
				`);
			})
		}
	})
	.catch(err => {
		$('#activity').addClass("none");
	 });
}
getKategoriArtikel();

/**
 * Get Mitra
 */
function getMitra() {
	$('#mitra').removeClass("node");

	axios.get(`${APIURL}/mitra/getmitra`)
	.then(res => {
		let elMitra = ''
		res.data.data.forEach(e => {
			elMitra += `<div class="col-12 col-sm-6 col-md-4 mb-5 h-100">
				<div class="card h-100" style="display:flex;flex-direction:column;justify-content:center;align-items:center;border:none;">
					<img src="${e.icon}" style="width:60%;"/>
				</div>
			</div>`;
		});

		$('#container-mitra').html(elMitra);
	})
	.catch(err => {
		$('#mitra').addClass("none");
	});
}
getMitra();

/**
 * Remove IG widget button
 */
setTimeout(() => {
	let xx = document.querySelectorAll(".eapps-link");

	xx.forEach(e => {
		e.remove();
	})
}, 3000);

/* 
-------------- 
send kritik 
--------------
*/
$('#contact').on('submit', function(e) {
	e.preventDefault();
	
	if (doValidate()) {
		showLoadingSpinner();

		let form = new FormData(e.target);

		axios
		.post(`${APIURL}/nasabah/sendkritik`,form, {
			headers: {
				// header options 
			}
		})
		.then((response) => {
			hideLoadingSpinner();
			$('#contact-name').val('');
			$('#contact-email').val('');
			$('#contact-message').val('')

			setTimeout(() => {
				Swal.fire({
					icon : 'success',
					title : 'Success',
					text : 'Pesan Telah Terkirim',
					showConfirmButton: false, 
					timer: 2000
				});
			}, 500);
		})
		.catch((error) => {
			hideLoadingSpinner();

			// error server
			if (error.response.status == 500) {
				showAlert({
					message: `<strong>Ups . . .</strong> terjadi kesalahan pada server, coba sekali lagi`,
					autohide: true,
					type:'danger'
				})
			}
		})
	}
});

// form validation
function doValidate() {
	let status     = true;
	let emailRules = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;

	// clear error message first
	$('.form-control').removeClass('is-invalid');
	$('.error-message').html('');

	// name validation
	if ($('#contact-name').val() == '') {
		$('#contact-name').addClass('is-invalid');
		$('#contact-name-error').html('*nama harus di isi');
		status = false;
	}
	else if ($('#contact-name').val().length > 20) {
		$('#contact-name').addClass('is-invalid');
		$('#contact-name-error').html('*lebih dari 20 huruf');
		status = false;
	}
	// email validation
	if ($('#contact-email').val() == '') {
		$('#contact-email').addClass('is-invalid');
		$('#contact-email-error').html('*email harus di isi');
		status = false;
	}
	// else if ($('#contact-email').val().length > 40) {
	// 	$('#contact-email').addClass('is-invalid');
	// 	$('#contact-email-error').html('*lebih dari 40 huruf');
	// 	status = false;
	// }
	else if (!emailRules.test(String($('#contact-email').val()).toLowerCase())) {
		$('#contact-email').addClass('is-invalid');
		$('#contact-email-error').html('*email tidak valid');
		status = false;
	}
	// message validation
	if ($('#contact-message').val() == '') {
		$('#contact-message').addClass('is-invalid');
		$('#contact-message-error').html('*pesan harus di isi');
		status = false;
	}

	return status;
}

/* 
-------------- 
Link Download On Click 
--------------
*/
document.querySelectorAll('#badges-wraper a').forEach(function (e) {
	e.addEventListener('click', function (event) {
		event.preventDefault();
	
		let href = event.target.parentElement.getAttribute('href');
		
		if (href != '') {
			window.open(href,'_blank');
		} else {
			Swal.fire({
				title:`Maaf, link belum tersedia`,
				iconHtml: `<img src="${BASEURL}/assets/images/cukupbaik.svg" style="width: 120px;">`,
				showCloseButton: false,
				showCancelButton: true,
				showConfirmButton: false,
				focusConfirm: false,
				cancelButtonText:
				  'tutup',
			});
		}
	})
})