//add headers to all the ajax requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});

//show success toaster
function showSuccess(message) {
    toastr.success(message);
}

//show warning toaster
function showInfo(message) {
    toastr.info(message);
}

//show error toaster
function showError(message) {
    toastr.error(message || languages.error_occurred);
}

//set the initial theme icon
if (currentTheme) {
    if (currentTheme === 'dark') {
        $('#themeSwitch').removeClass('fas fa-moon').addClass('fas fa-sun');
    }
}

//change the theme on button click
$('.dark-theme-setting').on('click', function() {
    if (document.documentElement.getAttribute('data-theme') === 'light') {
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        $('#themeSwitch').removeClass('fas fa-moon').addClass('fas fa-sun');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
        $('#themeSwitch').removeClass('fas fa-sun').addClass('fas fa-moon');
    }
});

//set href into the social links
$('#fbShare').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + location.hostname + '&quote=' + socialInvitation);
$('#twitterShare').attr('href', 'https://twitter.com/share?url=' + location.hostname + '&text=' + socialInvitation);
$('#waShare').attr('href', 'https://api.whatsapp.com/send?text=' + socialInvitation + ' \n ' + location.hostname);

//stripe payment handler
var $form = $(".validation");
$("form.validation").bind("submit", function(e) {
    var $form = $(".validation"),
        inputVal = ["input[type=email]", "input[type=password]", "input[type=text]", "input[type=file]", "textarea"].join(", "),
        $inputs = $form.find(".required").find(inputVal),
        $errorStatus = $form.find("div.error"),
        valid = true;
    $errorStatus.addClass("hide");
    $("#payNow").attr('disabled', true);

    $(".has-error").removeClass("has-error");
    $inputs.each(function(i, el) {
        var $input = $(el);
        if ($input.val() === "") {
            $input.parent().addClass("has-error");
            $errorStatus.removeClass("hide");
            e.preventDefault();
        }
    });

    if (!$form.data("cc-on-file")) {
        e.preventDefault();
        Stripe.setPublishableKey($form.data("stripe-publishable-key"));
        Stripe.createToken({
                number: $(".card-number").val(),
                cvc: $(".card-cvc").val(),
                exp_month: $(".card-expiry-month").val(),
                exp_year: $(".card-expiry-year").val(),
            },
            stripeHandleResponse
        );
    }
});

function stripeHandleResponse(status, response) {
    if (response.error) {
        $('.error').removeClass('hide').find('.alert').text(response.error.message);
        $('#payNow').attr('disabled', false);
    } else {
        var token = response['id'];
        $form.find('input[type=text]').empty();
        $form.append('<input type="hidden" name="stripeToken" value="' + token + '"/>');
        $form.get(0).submit();
    }
}

//ajax call to update the password
$('#changePasswordEdit').on('submit', function(e) {
    e.preventDefault();

    $('#save').attr('disabled', true);

    $.ajax({
            url: 'update-password',
            data: $(this).serialize(),
            type: 'post',
        })
        .done(function(data) {
            data = JSON.parse(data);
            $('#save').attr('disabled', false);

            if (data.success) {
                showSuccess(languages.data_updated);
                $('#changePasswordEdit')[0].reset();
            } else {
                showError();
            }
        })
        .catch(function() {
            showError();
            $('#save').attr('disabled', false);
        });
});

//switch monthly and yearly packages
$('input[name=period]').on('change', function() {
    if (this.value == 'monthly') {
        $("#yearlyPrice").attr('hidden', 'true');
        $("#montlyPrice").removeAttr('hidden');
        $("#type").val('monthly');
    } else {
        $("#montlyPrice").attr('hidden', 'true');
        $("#yearlyPrice").removeAttr('hidden');
        $("#type").val('yearly');
    }
});

//dynamically add google analytics tracking ID
if (googleAnalyticsTrackingId !== 'null' && googleAnalyticsTrackingId) {
    let script = document.createElement('script');
    script.src = 'https://www.googletagmanager.com/gtag/js?id=' + googleAnalyticsTrackingId;
    document.body.appendChild(script);

    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', googleAnalyticsTrackingId);
}

//check if the cookie is accepted 
if (cookieConsent == "enabled" && !localStorage.getItem('cookieAccepted')) {
    setTimeout(function() {
        $(".cookie").addClass('show-cookie');
    }, 3000);
}

$(document).on('click', '.confirm-cookie', function() {
    localStorage.setItem('cookieAccepted', true);
    $(".cookie").removeClass('show-cookie');
});


//set footer
var plen = $('.static-content-data').height();
if (plen > 450) {
    $('footer').addClass('footerSet');
}