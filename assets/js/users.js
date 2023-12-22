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
    getAllData('getuser', element, Form);
};

getData();

showModal = function()
{
    loading();
    $.ajax({
        type: "POST",
        url: base_url + "users/new",
        success: function(res){
            clearLoading();
            $("#tampilModal").html(res.data);
            $("#staticBackdrop").modal("show");
            $("#staticBackdrop").appendTo("body");
        }
    });
}