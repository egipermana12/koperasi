$('.settings').each(function(){
    var el = $(this);
    let btnEdit = el.siblings('.edit-setting');
    let btnSave = el.siblings('.edit-save');
    let btnBatal = el.siblings('.edit-batal');

    btnSave.hide();
    btnBatal.hide();

    btnEdit.click(function(e){
        btnSave.show();
        btnBatal.show();
        btnEdit.hide();

        el.removeAttr('disabled');
    });

    btnSave.click(function(e){
        let id = el.data('id');
        let value = el.val();
        var formData = new FormData();
        formData.append('id', id);
        formData.append('value', value);
        loading();
        $.ajax({
            type:"POST",
            data:formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            url: "settings/store",
            success: function(data) {
                var data = eval("(" + data + ")");
                clearLoading();
                if(data.err === ""){
                    alert_confirm("Sukes","Data Berhasil Disimpan","success","settings");
                }else{
                    alert_error(data.err);
                }
            }
        });
    });

    btnBatal.click(function(e){
        btnSave.hide();
        btnBatal.hide();
        btnEdit.show();

        el.prop('disabled',true);
    });
})


DetailImages = function(id)
{
    alert('Hallo' + id);
}
