

jQuery(".optionSubmit").live('click', function () {
    validOpt = new Validation('optForm', {useTitles: true, onSubmit: false});
    var result = validOpt.validate();
    if (!result) {
        return false;

    }


    document.forms['optForm'].submit();


});


