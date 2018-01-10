/**
 * Main js page
 **/

var base_url = "http://localhost/p/fr_lefuturiste_panel/public"

/**
 * Loader function
 *
 */
$(window).on('load', function () {
    $("body").removeAttr('style')
    $("#loader").fadeOut(500);
});

/**
 * Semantic
 *
 */
$('#dropdown').dropdown();
$('.checkbox').checkbox();

function onLoad() {
    gapi.load('auth2', function () {
        gapi.auth2.init();
    });
    gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'longtitle': true,
        'height': "50px",
        'width': "100%",
        'onsuccess': onSignIn
    });
    $('.abcRioButton.abcRioButtonLightBlue')
        .removeAttr('style')
        .css(
            'width','100%'
        );
}

/**
 * SlideBare semantic toogle
 */
$("#sidebar-toogle").click(function () {
    $(".ui.sidebar").sidebar('toggle')
});

/**
 * Modal automatiq
 */
$(".modal-button").each(function () {
    console.log(this);
    $(this).click(function () {
        $($(this).data("modalid")).modal('show')
    })
})

/**
 * Fonction called on google signin
 * @param googleUser
 */
function onSignIn(googleUser) {
    $("#loader").fadeIn();
    var id_token = googleUser.getAuthResponse().id_token;

    console.log("AUTH TOKEN: " + id_token);

    $.ajax({
        url : base_url + "/login",
        type : 'POST',
        data : 'id-token=' + id_token,
        dataType : 'json',
        success : function(json, statut){
            if (json.success == true){
                window.location.replace(base_url + "/dashboard");
            }
            else{
                console.log(json)

                $("#loader").fadeOut();

                $("#ajax-alert-danger-error").fadeIn();

                $("#ajax-alert-danger-error").html("Error: " + json.error_code + " - " + json.error);

            }
        },
        error : function(exception){

            console.log(exception);

            $("#loader").fadeOut();
            $("#ajax-alert-danger-internal").fadeIn();
        }
    });


}