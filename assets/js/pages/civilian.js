$(document).ready(function() {
    function getUserCharacters() {
        (function worker() {
            $.ajax({
                url: 'inc/backend/user/civ/getCharacters.php',
                success: function(data) {
                    $('#listCharacters').html(data);
                },
                complete: function() {
                    // Schedule the next request when the current one's complete
                    setTimeout(worker, 5000);
                }
            });
        })();
    }
    getUserCharacters();
});

function updateDriversLicense(selectObject) {
    var str = selectObject.value;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //hmmmzz
        }
    };
    xmlhttp.open("GET", "inc/backend/user/civ/updateDriverLicense.php?license=" + str, true);
    xmlhttp.send();
    toastr.success('Drivers License Updated!', 'System');
}

function updateFirearmLicense(selectObject) {
    var str = selectObject.value;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //hmmmzz
        }
    };
    xmlhttp.open("GET", "inc/backend/user/civ/updateFirearmLicense.php?license=" + str, true);
    xmlhttp.send();
    toastr.success('Firearm License Updated!', 'System');
}

function deleteVehicle(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/civ/deleteVehicle.php?id=" + i,
        cache: false,
        success: function(error) {
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                toastr.success('Vehicle Deleted!', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        }
    });
}

function deleteFirearm(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/civ/deleteFirearm.php?id=" + i,
        cache: false,
        success: function(error) {
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                toastr.success('Firearm Deleted!', 'System:', {
                    timeOut: 10000
                });
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                });
            }
        }
    });
}
