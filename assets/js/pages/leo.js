$(document).ready(function() {
    function getLEODivisions() {
        (function worker() {
            $.ajax({
                url: 'inc/backend/user/leo/getLeoDivisions.php',
                success: function(data) {
                    $('#listLeoDivisions').html(data);
                },
                complete: function() {
                    // Schedule the next request when the current one's complete
                    setTimeout(worker, 5000);
                }
            });
        })();
    }

    function getUserIdentitys() {
        (function worker() {
            $.ajax({
                url: 'inc/backend/user/leo/getUserIdentitys.php',
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
    getLEODivisions();

    $('.select2').select2({
        minimumInputLength: 3
    });
});

function checkActiveDispatchers() {
  $.ajax({
      url: 'inc/backend/user/leo/checkActiveDispatchers.php',
      success: function(data) {
          $('#checkDispatchers').html(data);

          if (data !== "") {
              // If no dispatchers are online....
                //Future update
          } else {
              // If dispatchers are online....
                //Future update
          }
      },
      complete: function() {
          setTimeout(checkActiveDispatchers, 5000);
      }
  });
}
checkActiveDispatchers();

function getActiveUnits() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/leo/getActiveUnits.php',
            success: function(data) {
                $('#getActiveUnits').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(worker, 15000);
            }
        });
    })();
}

getActiveUnits();

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

function getAllCharacters2() {
    (function getAllCharacters2() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllCharacters.php',
            success: function(data) {
                $('#getAllCharacters2').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllCharacters2, 60000);
            }
        });
    })();
}
getAllCharacters2();

function getAllCharacters3() {
    (function getAllCharacters3() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllCharacters.php',
            success: function(data) {
                $('#getAllCharacters3').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllCharacters3, 60000);
            }
        });
    })();
}
getAllCharacters3();

function getAllCharacters4() {
    (function getAllCharacters4() {
        $.ajax({
            url: 'inc/backend/user/leo/getAllCharacters.php',
            success: function(data) {
                $('#getAllCharacters4').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(getAllCharacters3, 60000);
            }
        });
    })();
}
getAllCharacters4();

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

function getPendingIds() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/leo/getPendingIds.php',
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
        url: "inc/backend/user/leo/approveID.php?id=" + i,
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
        url: "inc/backend/user/leo/rejectID.php?id=" + i,
        cache: false,
        success: function(result) {
            toastr.error('ID Rejected.', 'System:', {
                timeOut: 10000
            })
        }
    });
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

function deleteWarrantLEO(str) {
    var i = str.id;

    $.ajax({
        url: "inc/backend/user/leo/deleteWarrantLEO.php?id=" + i,
        cache: false,
        success: function(error) {
            var error = JSON.parse(error);
            if (error['msg'] === "") {
                showName();
                toastr.success('Warrant Deleted', 'System:', {
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

function getBolos() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/leo/getBolos.php',
            success: function(data) {
                $('#getBolos').html(data);
            },
            complete: function() {
                setTimeout(worker, 5000);
            }
        });
    })();
}

getBolos();

function getMyCalls() {
    (function worker() {
        $.ajax({
            url: 'inc/backend/user/leo/getMyCalls.php',
            success: function(data) {
                $('#getMyCalls').html(data);
            },
            complete: function() {
                setTimeout(worker, 1000);
            }
        });
    })();
}

getMyCalls();

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

function officerPanicBtn() {
    $.ajax({
        url: 'inc/backend/user/leo/officerPanicButton.php',
        success: function(data) {
            changeSignal();
            toastr.error('PANIC BUTTON PUSHED.')
        },
    });
}
