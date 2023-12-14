$(document).ready(function () {
	// Toggle the side navigation
	const sidebarToggle = $("#sidebarToggle");
	if (sidebarToggle.length) {
		sidebarToggle.on("click", function (event) {
			event.preventDefault();
			$("body").toggleClass("sb-sidenav-toggled");
			localStorage.setItem(
				"sb|sidebar-toggle",
				$("body").hasClass("sb-sidenav-toggled")
				);
		});
	}

	$('.tgl_datepicker').datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		yearRange: "-30:+00",
		showOn: "button",
		buttonImage: "/assets/vendors/jquery-ui/images/calendar.gif",
		buttonImageOnly: true,
	});

	$('#kode_kecamatan').select2();
	$('#kode_kelurahan').select2();

});


pilihKecamatan =  function() {
	let kode_kecamatan = $('#kode_kecamatan').val();
	let url = "anggota/pilihKecamatan";
	loading();
	$.ajax({
		type:"POST",
		data:{kode_kecamatan: kode_kecamatan},
		url: base_url + url,
		success: function(data) {
			var resp = eval("(" + data + ")");
			clearLoading();
			if(resp.error === ""){
				$('#tukCmbKel').html(resp.content);
				$('#kode_kelurahan').select2();
			}else{
				alert(resp.error);
			}
		}
	});
}

loading = function () {
	$("#loadingContent").html(
		'<div class="position-fixed t-0 l-0 w-100 h-100 d-flex justify-content-center align-items-center" style="z-index: 1060; background: rgba(0,0,0,0.1);"><div class="spinner-grow spinner-grow-sm text-danger mx-1" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow spinner-grow-sm text-danger mx-1" role="status"><span class="sr-only">Loading...</span></div><div class="spinner-grow spinner-grow-sm text-danger mx-1" role="status"><span class="sr-only">Loading...</span></div></div>'
		);
};

clearLoading = function () {
	$("#loadingContent").html("");
};

getAllData = function (url, contentElement, data) {
	loading();
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		processData: false, // tell jQuery not to process the data
		contentType: false, // tell jQuery not to set contentType
		success: function (data) {
			var res = JSON.parse(data);
			clearLoading();
			if (res.error === "") {
				$(contentElement).html(res.content);
			} else {
				console.log(res.error);
			}
		},
	});
};

/**
 * start handle checkbox global
 */

var DataPilih = 0,
idPilih = "";

setChecklist = function (data) {
	if (data.check == true) {
		DataPilih++;
	} else {
		DataPilih--;
	}
};

cekJumlahData = function () {
	var jmlData = 0;
	cb = document.getElementsByName("check");
	for (let i = 0; i < cb.length; i++) {
		if (cb[i].checked == true) {
			if (i != 0 && jmlData > 0) {
				idPilih += ",";
			}
			jmlData++;
			idPilih += cb[i].value;
		}
	}
	return jmlData;
};

AfterChekedAll = function (data) {
	setChecklist(data);
};

checkedAll = function () {
	checkAll = document.getElementById("check-all");
	checkboxes = document.getElementsByName("check");
	if (checkAll) {
		isChecked = checkAll.checked;
		for (let i = 0; i < checkboxes.length; i++) {
			setCB = true;
			if (isChecked && checkboxes[i].checked) {
				setCB = false;
			}
			if (!isChecked && !checkboxes[i].checked) {
				setCB = false;
			}
			checkboxes[i].checked = isChecked;
			if (setCB) {
				AfterChekedAll(checkboxes[i]);
			}
		}
	}
};

/**
 * end handle checkbox global
 */

/**
 * preview image
 */
readUrl = function (image) {
	if (image.files && image.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$("#imagePreview").css(
				"background-image",
				"url(" + e.target.result + ")"
				);
			$("#imagePreview").html("");
			$("#imagePreview").hide();
			$("#imagePreview").fadeIn(650);
		};
		reader.readAsDataURL(image.files[0]);
	}
};

/**
 * sweet alert
 * */
alert_error = function (err){
	Swal.fire({
		icon: 'error',
		text: err,
	});
}

alert_confirm = function (title, text, icon , urlDirect){
	Swal.fire({
		title: title,
		text: text,
		icon: icon,
		showCancelButton: false,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "OKE",
		allowOutsideClick: false,
		allowEscapeKey: false
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = base_url + urlDirect;
		}
	});
}

console_form = function (entries){
	for(var pair of entries.entries()){
        console.log(pair[0], pair[1]);
    }
}