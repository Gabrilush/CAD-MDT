function setIdentityVerification(str) {
    if (str == "") {
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //hmmm
            }
        };
        xmlhttp.open("GET", "inc/backend/staff/settings/setIdentityVerification.php?q=" + str, true);
        xmlhttp.send();
        toastr.success('Identity Verification Settings Updated.', 'System:', {
            timeOut: 10000
        })
    }
}

function setAccountVerification(str) {
    if (str == "") {
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //hmmm
            }
        };
        xmlhttp.open("GET", "inc/backend/staff/settings/setAccountVerification.php?q=" + str, true);
        xmlhttp.send();
        toastr.success('Account Verification Settings Updated.', 'System:', {
            timeOut: 10000
        })
    }
}

function setSteamLogin(str) {
    if (str == "") {
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //hmmm
            }
        };
        xmlhttp.open("GET", "inc/backend/staff/settings/setSteamLogin.php?q=" + str, true);
        xmlhttp.send();
        alert('Notice: You have enabled Steam Account Linking. Please open steamauth/SteamConfig.php - and set your API Key, and Domain.');
        alert('Notice: You have enabled Steam Account Linking. Please open steamauth/SteamConfig.php - and set your API Key, and Domain.');
        alert('Notice: You have enabled Steam Account Linking. Please open steamauth/SteamConfig.php - and set your API Key, and Domain.');
        alert('Notice: You have enabled Steam Account Linking. Please open steamauth/SteamConfig.php - and set your API Key, and Domain.');
        alert('Notice: You have enabled Steam Account Linking. Please open steamauth/SteamConfig.php - and set your API Key, and Domain.');
        toastr.success('Theme Settings Updated... The page will reload for changes to take effect!', 'System:', {
            timeOut: 10000
        })
        location.reload();
    }
}

function setCivSideWarrants(str) {
    if (str == "") {
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //hmmm
            }
        };
        xmlhttp.open("GET", "inc/backend/staff/settings/setCivSideWarrants.php?q=" + str, true);
        xmlhttp.send();
        toastr.success('Settings Updated.', 'System:', {
            timeOut: 10000
        })
    }
}

function setAddWarrantPerm(str) {
    if (str == "") {
        return;
    } else {
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //hmmm
            }
        };
        xmlhttp.open("GET", "inc/backend/staff/settings/setAddWarrantPerm.php?q=" + str, true);
        xmlhttp.send();
        toastr.success('Settings Updated.', 'System:', {
            timeOut: 10000
        })
    }
}

function getPendingUsers() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/staff/users/getPendingUsers.php',
            success: function(data) {
                $('#getPendingUsers').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(worker, 1000);
            }
        });
    })();
}
getPendingUsers();

function approveUser(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/staff/users/approveUser.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.success('User has been approved.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function rejectUser(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/staff/users/rejectUser.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.error('User has been rejected.', 'System:', {
                timeOut: 10000
            })
        }
    });
}
