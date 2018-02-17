jQuery(document).ready(function () {

    jQuery(".linksubmit").live('click', function () {
        validOpt = new Validation('linkform', {useTitles: true, onSubmit: false});
        var result = validOpt.validate();
        if (!result) {
            return false;

        }
        document.forms['linkform'].submit();

    });

});