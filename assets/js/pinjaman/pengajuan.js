var Form = new FormData();
var filter = 0;
if(!Form)
{
    Form.append('pageStart', 0);
}

getData = function(pageStart = 0)
{
    $("#pageStart").val(pageStart);
    if(filter == 1){
        setForm();
    }
    let element = $('#loadElement');
    getAllData(base_url + 'pengajuanpinjamanview', element, Form);
};

getData();

editData = function(){
    let cek = cekJumlahData();
    if(cek != 1){
        alert_error('Harus Pilih Satu Data');
    }else{
        ubahData(idPilih);
    }
}

ubahData = function(idPilih){
    window.location.href = base_url + 'Pinjaman/pengajuan/edit/' + idPilih;
}

accData =  function(){
    let cek = cekJumlahData();
    if(cek != 1){
        alert_error('Harus Pilih Satu Data');
    }
        loading();
        var formData = new FormData();
        formData.append('id', idPilih);
        console.log(idPilih);
        $.ajax({
            type: "POST",
            url: base_url + "Pinjaman/pengajuan/setujui",
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false,
            success: function(res){
                clearLoading();
                $("#tampilModal").html(res);
                $("#staticBackdrop").modal("show");
                $("#staticBackdrop").appendTo("body");
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(
                    xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
                    );
            },
        });

}

hapusData = function()
{
    let cek = cekJumlahData();
    var formData = new FormData();
        formData.append('id', idPilih);
    if(cek < 0){
        alert_error('Data belum dipilih');
    }else{
        Swal.fire({
        title: "Hapus Data",
        text: "Yakin hapus " + cek + " data ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Tidak',
        allowOutsideClick: false,
        allowEscapeKey: false
        }).then((result) => {
            if(result.isConfirmed){
                loading();
                $.ajax({
                    type: "POST",
                    url: base_url + "Pinjaman/pengajuan/delete",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "JSON",
                    success: function(res){
                        if(res.success === true) {
                            clearLoading();
                            alert_success(res.messages);
                            idPilih = "";
                            DataPilih = 0;
                            getData();
                        }else{
                            alert_error(res.messages);
                            clearLoading();
                            idPilih = "";
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(
                            xhr.status + "\n" + xhr.responseText + "\n" + thrownError,
                        );
                    },
                });
            }else{
                idPilih = "";
                DataPilih = cek;
            }
        });
    }
}