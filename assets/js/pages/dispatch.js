$(document).ready(function() {
    function getUserIdentitys() {
        (function worker() {
            $.ajax({
                url: 'inc/backend/user/dispatch/getUserIdentitys.php',
                success: function(data) {
                    $('#listIdentitys').html(data);
                },
                complete: function() {
                    // Schedule the next request when the current one's complete
                    setTimeout(worker, 5000);
                }
            });
        })();
    }

    getUserIdentitys();

    $('.select2').select2({
        minimumInputLength: 3
    });
    $('.select2multi').select2();
    $('.select2_assignUnit').select2();
});

function getAllCharacters() {
    (function getAllCharacters() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllCharacters.php',
            success: function(data) {
                $('#getAllCharacters').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllCharacters, 5000);
            }
        });
    })();
}
getAllCharacters();

function getAllVehicles() {
    (function getAllVehicles() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllVehicles.php',
            success: function(data) {
                $('#getAllVehicles').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllVehicles, 5000);
            }
        });
    })();
}
getAllVehicles();

function getAllFirearms() {
    (function getAllFirearms() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllFirearms.php',
            success: function(data) {
                $('#getAllFirearms').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllFirearms, 5000);
            }
        });
    })();
}
getAllFirearms();

function setUnitStatus(selectObject) {
    var i = selectObject.value;
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
    xmlhttp.open("GET", "inc/backend/user/leo/setStatus.php?status=" + i, true);
    xmlhttp.send();
    toastr.success('Status Updated', 'System');
}

function showName(str) {
    if (str == "") {
        document.getElementById("showPersonInfo").innerHTML = "";
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
                document.getElementById("showPersonInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/searchNameDB.php?id=" + str, true);
        xmlhttp.send();
    }
}

function showVehicle(str) {
    if (str == "") {
        document.getElementById("showVehicleInfo").innerHTML = "";
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
                document.getElementById("showVehicleInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/searchVehicleDB.php?id=" + str, true);
        xmlhttp.send();
    }
}

function showFirearm(str) {
    if (str == "") {
        document.getElementById("showFirearmInfo").innerHTML = "";
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
                document.getElementById("showFirearmInfo").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "inc/backend/user/leo/searchWeaponDB.php?id=" + str, true);
        xmlhttp.send();
    }
}

function updateNotepad(str) {
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
        xmlhttp.open("GET", "inc/backend/user/leo/updateNotepad.php?txt=" + str, true);
        xmlhttp.send();
    }
}

function suspendDriversLicense(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/leo/suspendDriversLicense.php?character=" + i,
        cache: false,
        success: function(result) {
            toastr.info('Drivers License Suspended - Changes will take effect in a moment.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function suspendFirearmsLicense(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/leo/suspendFirearmsLicense.php?character=" + i,
        cache: false,
        success: function(result) {
            toastr.info('Firearms License Suspended - Changes will take effect in a moment.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function getActiveUnits() {
    var isFocusedDispatch = false;
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/dispatch/getActiveUnits.php',
            success: function(data) {
                $(document).ajaxComplete(function() {
                    $('.select-units').focus(function() {
                        isFocusedDispatch = true;
                    });
                    $('.select-units').blur(function() {
                        isFocusedDispatch = false;
                    });
                });
                if (!isFocusedDispatch) {
                    $('#getActiveUnits').html(data);
                }
            },
            complete: function() {
                setTimeout(worker, 1000);
            }
        });
    })();
}

getActiveUnits();

function get911Calls() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/dispatch/get911Calls.php',
            success: function(data) {
                $('#get911Calls').html(data);
            },
            complete: function() {
                setTimeout(worker, 1000);
            }
        });
    })();
}

get911Calls();

function updateUnitStatus(selectObject) {
    var i = selectObject.id;
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
    xmlhttp.open("GET", "inc/backend/user/dispatch/updateUnitStatus.php?unit=" + i + "&status=" + str, true);
    xmlhttp.send();
    // alert(str + " " + uid);
    $(".select-units").blur();
    isFocused = false;
}

function getAllActiveUnitsForCall() {
    (function getAllActiveUnitsForCall() {
        $.ajax({
            url: 'inc/backend/user/dispatch/getAllActiveUnitsForCall.php?opt=1',
            success: function(data) {
                $('#getAllActiveUnitsForCall').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllActiveUnitsForCall, 2000);
            }
        });
    })();
}
getAllActiveUnitsForCall();

function getAllActiveUnitsForNewCall() {
    (function getAllActiveUnitsForNewCall() {
        $.ajax({
            url: 'inc/backend/user/dispatch/getAllActiveUnitsForCall.php?opt=2',
            success: function(data) {
                $('#attachUnits').html(data);
            },
            complete: function() {
                if ('#attachUnits' === "") {
                    setTimeout(getAllActiveUnitsForNewCall, 2000);
                } else {
                    setTimeout(getAllActiveUnitsForNewCall, 60000);
                }
            }
        });
    })();
}
getAllActiveUnitsForNewCall();

function getAttchedUnits() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/dispatch/getAttchedUnits.php',
            success: function(data) {
                $('#getAttchedUnits').html(data);
            },
            complete: function() {
                setTimeout(worker, 1000);
            }
        });
    })();
}
getAttchedUnits();

function assignUnit(str) {
    toastr.warning('Please Wait...')
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
                toastr.success('Unit Assigned To Call.')
            }
        };
        xmlhttp.open("GET", "inc/backend/user/dispatch/assignUnit.php?unit=" + str, true);
        xmlhttp.send();
    }
}

function unassignUnit(str) {
    toastr.warning('Please Wait...')
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
                toastr.success('Unit Detached From Call.')
            }
        };
        xmlhttp.open("GET", "inc/backend/user/dispatch/unassignUnit.php?unit=" + str, true);
        xmlhttp.send();
    }
}

function clear911Call() {
    toastr.warning('Please Wait...')
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('#callInfoModal').modal('hide');
            toastr.success('Call Archived.')
        }
    };
    xmlhttp.open("GET", "inc/backend/user/dispatch/archiveCall.php", true);
    xmlhttp.send();
}

function getBolos() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/dispatch/getBolos.php',
            success: function(data) {
                $('#getBolos').html(data);
            },
            complete: function() {
                setTimeout(worker, 2000);
            }
        });
    })();
}

getBolos();

function clearBOLO() {
    toastr.warning('Please Wait...')
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $('#boloInfoModal').modal('hide');
            toastr.success('BOLO Cleared.')
        }
    };
    xmlhttp.open("GET", "inc/backend/user/dispatch/clearBolo.php", true);
    xmlhttp.send();
}

function getPendingIds() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/dispatch/getPendingIds.php',
            success: function(data) {
                $('#getPendingIds').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(worker, 1000);
            }
        });
    })();
}
getPendingIds();

function approveID(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/dispatch/approveID.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.success('ID Approved.', 'System:', {
                timeOut: 10000
            })
        }
    });
}

function rejectID(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/dispatch/rejectID.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.error('ID Rejected.', 'System:', {
                timeOut: 10000
            })
        }
    });
}
