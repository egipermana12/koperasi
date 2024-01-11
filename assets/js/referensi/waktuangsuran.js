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
    getAllData(base_url + 'waktuangsuranview', element, Form);
};

getData();