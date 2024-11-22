function showProgress(progressbar_id) {
    var spinnerVisible = false;
    if (!spinnerVisible) {
        $(progressbar_id).fadeIn("fast");
        spinnerVisible = true;
    }
};

function hideProgress(progressbar_id) {
    var spinnerVisible = true;
    if (spinnerVisible) {
        var spinner = $(progressbar_id);
        spinner.stop();
        spinner.fadeOut("fast");
        spinnerVisible = false;
    }
};