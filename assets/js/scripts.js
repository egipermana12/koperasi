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

	$(".jqueryui-marker-datepicker")
	  .wrap('<div class="input-group">')
	  .datepicker({
	    dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		yearRange: "-30:+00",
		showOn: "button",
		buttonImage: "/assets/vendors/jquery-ui/images/calendar.gif",
		buttonImageOnly: true,
	  })
	  .next("button").button({
	    icons: { primary: "ui-icon-calendar" },
	    label: "Select a date",
	    text: false
	  })
	  .addClass("btn btn-default")
	  .wrap('<span class="input-group-btn">')
	  .find('.ui-button-text')
	  .css({
	    'visibility': 'hidden',
	    'display': 'inline'
	  });

	$('#kode_kecamatan').select2();
	$('#kode_kelurahan').select2();

});

/**
 * for input type number
 * */

isNumberKey = function(evt) {
	var charCode = evt.which ? evt.which : event.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode != 44){

		return false;
	}
	return true;
}

formatCurrency = function(num){
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num)) num = '0';
    sign = num == (num = Math.abs(num));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10) cents = '0' + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num =
    num.substring(0, num.length - (4 * i + 3)) +
    '.' +
    num.substring(num.length - (4 * i + 3));
    return (sign ? '' : '-') + '' + num + ',' + cents;
}

inputCurrency = function(name) {
    var angka = document.getElementById(name + '_Uang').value;
    var res = angka.split(',');
    var harga = res[0].replace(/\./g, '');

    harga_new = parseInt(harga);
  //harga_new = harga.split(",");

    belakangkoma1 = res[1] != undefined ? ',' + res[1] : '';
    belakangkoma2 = res[1] != undefined ? '.' + res[1] : '';

    hrg_tmpl = formatCurrency(harga_new);
    hrg_tmpl = hrg_tmpl.split(',');

  //alert(hrg_tmpl[0]);

    $('#' + name + '_Uang').val(hrg_tmpl[0] + '' + belakangkoma1);
    $('#' + name).val(harga + '' + belakangkoma2);
}


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

DataPilih = 0;
idPilih = "";

setChecklist = function (data) {
	if (data.checked == true) {
		DataPilih++;
	} else {
		if(DataPilih > 0){
			DataPilih--;
		}
	}
	setBtnEdit();
	setBtnDelete();
	setBtnPrint();
	setBtnAcc();
};

setBtnEdit = function(){
	let btn = $('#btnEdit');
	if(DataPilih == 1){
		btn.removeAttr('disabled');
		btn.addClass('btn-warning');
		btn.removeClass('btn-secondary');
	}else{
		btn.attr('disabled','disabled');
		btn.removeClass('btn-warning');
		btn.addClass('btn-secondary');
	}
}

setBtnAcc = function(){
	let btn = $('#btnAcc');
	if(DataPilih == 1){
		btn.removeAttr('disabled');
		btn.addClass('btn-warning');
		btn.removeClass('btn-secondary');
	}else{
		btn.attr('disabled','disabled');
		btn.removeClass('btn-warning');
		btn.addClass('btn-secondary');
	}
}

setBtnDelete = function(){
	let btn = $('#btnDelete');
	if(DataPilih > 0){
		btn.removeAttr('disabled');
		btn.addClass('btn-danger');
		btn.removeClass('btn-secondary');
	}else{
		btn.attr('disabled','disabled');
		btn.removeClass('btn-danger');
		btn.addClass('btn-secondary');
	}
}

setBtnPrint = function(){
	let btn = $('#btnPrint');
	if(DataPilih > 0){
		btn.removeAttr('disabled');
		btn.addClass('btn-info');
		btn.removeClass('btn-secondary');
	}else{
		btn.attr('disabled','disabled');
		btn.removeClass('btn-info');
		btn.addClass('btn-secondary');
	}
}


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
			setData = true;
			if (isChecked && checkboxes[i].checked) {
				setData = false;
			}
			if (!isChecked && !checkboxes[i].checked) {
				setData = false;
			}
			checkboxes[i].checked = isChecked;
			if (setData) {
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

alert_success = function (err){
	Swal.fire({
		icon: 'success',
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