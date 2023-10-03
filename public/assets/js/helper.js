/* Write here your custom javascript codes */

jQuery.validator.setDefaults({
    messages: {},
    groups: {},
    rules: {},
    errorClass: "is-invalid",
    pendingClass: "pending",
    validClass: "is-valid",
    errorElement: "label",
    focusCleanup: false,
    focusInvalid: true,
    errorContainer: $( [] ),
    errorLabelContainer: $( [] ),
    onsubmit: true,
    ignore: ":hidden",
    ignoreTitle: false,
    onfocusin: function( element ) {
        this.lastActive = element;

        // Hide error label and remove error class on focus if enabled
        if ( this.settings.focusCleanup ) {
            if ( this.settings.unhighlight ) {
                this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
            }
            this.hideThese( this.errorsFor( element ) );
        }
    },
    onfocusout: function( element ) {
        if ( !this.checkable( element ) && ( element.name in this.submitted || !this.optional( element ) ) ) {
            this.element( element );
        }
    },
    onkeyup: function( element, event ) {

        // Avoid revalidate the field when pressing one of the following keys
        // Shift       => 16
        // Ctrl        => 17
        // Alt         => 18
        // Caps lock   => 20
        // End         => 35
        // Home        => 36
        // Left arrow  => 37
        // Up arrow    => 38
        // Right arrow => 39
        // Down arrow  => 40
        // Insert      => 45
        // Num lock    => 144
        // AltGr key   => 225
        var excludedKeys = [
            16, 17, 18, 20, 35, 36, 37,
            38, 39, 40, 45, 144, 225
        ];

        if ( event.which === 9 && this.elementValue( element ) === "" || $.inArray( event.keyCode, excludedKeys ) !== -1 ) {
            return;
        } else if ( element.name in this.submitted || element.name in this.invalid ) {
            this.element( element );
        }
    },
    onclick: function( element ) {

        // Click on selects, radiobuttons and checkboxes
        if ( element.name in this.submitted ) {
            this.element( element );

            // Or option elements, check parent select in that case
        } else if ( element.parentNode.name in this.submitted ) {
            this.element( element.parentNode );
        }
    },
    highlight: function( element, errorClass, validClass ) {
        if ( element.type === "radio" ) {
            this.findByName( element.name ).addClass( errorClass ).removeClass( validClass );
        } else {
            $( element ).addClass( errorClass ).removeClass( validClass );
        }
    },
    unhighlight: function( element, errorClass, validClass ) {
        if ( element.type === "radio" ) {
            this.findByName( element.name ).removeClass( errorClass ).addClass( validClass );
        } else {
            $( element ).removeClass( errorClass ).addClass( validClass );
        }
    }
});

$.notifyDefaults({
    type: 'info',
    allow_dismiss: false,
    showProgressbar: true,
    spacing: 10,
    timer: 2000,
    placement: {
        from: 'top',
        align: 'center'
    },
    offset: {
        x: 30,
        y: 30
    },
    delay: 2000,
    z_index: 10000,
    animate: {
        enter: 'animate__animated animate__fadeIn',
        exit: 'animate__animated animate__fadeOut'
    }
});

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {type: contentType});
    return blob;
}

function appendFileToForm(form, imageData, imageFieldName) {
    // Split the base64 string in data and contentType
    var block = imageData.split(";");
    // Get the content type
    var contentType = block[0].split(":")[1];// In this case "image/gif"
    // get the real base64 content of the file
    var realData = block[1].split(",")[1];// In this case "iVBORw0KGg...."

    // Convert to blob
    var blob = b64toBlob(realData, contentType);

    // Create a FormData and append the file
    form.append(imageFieldName, blob);
}

function checkfile(sender) {
    var validExts = [".jpg", ".jpeg", ".png"];
    var fileExt = sender.value;
    fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
    if (validExts.indexOf(fileExt) < 0) {
        new Noty({
            type: "error",
            text: "Gambar tidak valid, pilih tipe gambar " + validExts.toString(),
            timeout: 5000
        }).show();

        $('#image').val("");

        return false;
    }
    else
    {
        return true;
    }
}

function getFileSizes(file) {
    var iSize = (file[0].files[0].size / 1024);
    iSize = (Math.round(iSize * 100) / 100);

    if(iSize > 2048)
    {
        iSize = (Math.round((iSize / 1024) * 100) / 100);
        new Noty({
            type: 'error',
            text: 'Ukuran file terlalu besar [' + iSize + "MB]",
            timeout: 2000,
        }).show();

        file.val('');
    }

    // console.log(iSize/1024);
    //
    // if (iSize / 1024 > 2) {
    //     if (((iSize / 1024) / 1024) > 1) {
    //         iSize = (Math.round(((iSize / 1024) / 1024) * 100) / 100);
    //
    //         new Noty({
    //             type: 'error',
    //             text: 'Ukuran file terlalu besar [' + iSize + "Gb]",
    //             timeout: 2000,
    //         }).show();
    //
    //         file.val('');
    //     }
    //     else
    //     {
    //         iSize = (Math.round((iSize / 1024) * 100) / 100);
    //
    //         new Noty({
    //             type: 'error',
    //             text: 'Ukuran file terlalu besar [' + iSize + "Mb]",
    //             timeout: 2000,
    //         }).show();
    //     }
    //
    //     file.val('');
    // }
    // else
    // {
    //
    // }
}

function select2Image(data) {
    var originalOption = data.element;

    if(originalOption)
    {
        return '<span><img src="' + $(originalOption).data('logo') + '" width="30" /> ' + data.text + '</span>';
    }
    else
    {
        return '<span class="fa fa-bank"> ' + data.text + '</span>';
    }
}

function decimalNumber(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}

function longDateFormat(date) {
    var monthNames = [
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni", "Juli",
        "Agustus", "September", "Oktober",
        "November", "Desember"
    ];

    var d = date.split("-").reverse().join("-");

    var dates = new Date(d);

    var day = dates.getDate();
    var monthIndex = dates.getMonth();
    var year = dates.getFullYear();

    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function longDateFormatJs(date) {
    var monthNames = [
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni", "Juli",
        "Agustus", "September", "Oktober",
        "November", "Desember"
    ];

    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();

    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function shortDateFormat(date) {
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "Mei", "Jun", "Jul",
        "Agt", "Sep", "Okt",
        "Nov", "Des"
    ];

    var d = date.split("-").reverse().join("-");

    var dates = new Date(d);

    var day = dates.getDate();
    var monthIndex = dates.getMonth();
    var year = dates.getFullYear();

    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function shortDateFormatJs(date) {
    var monthNames = [
        "Jan", "Feb", "Mar",
        "Apr", "Mei", "Jun", "Jul",
        "Agt", "Sep", "Okt",
        "Nov", "Des"
    ];

    var day = date.getDate();
    var monthIndex = date.getMonth();
    var year = date.getFullYear();

    return day + ' ' + monthNames[monthIndex] + ' ' + year;
}

function toLetters(num) {
    "use strict";
    var mod = num % 26,
        pow = num / 26 | 0,
        out = mod ? String.fromCharCode(64 + mod) : (--pow, 'Z');
    return pow ? toLetters(pow) + out : out;
}

function fromLetters(str) {
    "use strict";
    var out = 0, len = str.length, pos = len;
    while (--pos > -1) {
        out += (str.charCodeAt(pos) - 64) * Math.pow(26, len - 1 - pos);
    }
    return out;
}


