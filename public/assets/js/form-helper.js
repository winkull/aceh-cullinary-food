var cropper_options,
    $image,
    ImageResult = null,
    uploadedImageType;

var FormHelper = function () {

    var startDate;
    var endDate;

    var arrows;
    if (KTUtil.isRTL()) {
        arrows = {
            leftArrow: '<i class="la la-angle-right"></i>',
            rightArrow: '<i class="la la-angle-left"></i>'
        }
    } else {
        arrows = {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>'
        }
    }

    // Private functions
    var dateRange_ = function () {
        $('.form-daterange').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
                format: 'DD-MM-YYYY',
                cancelLabel: 'Batal',
                applyLabel: 'Terapkan'
            }
        }, function (start, end) {
            startDate   = start;
            endDate     = end;
        });
    }

    var yearPicker = function () {
        $('.yearpicker').datepicker({
            defaultDate: new Date().getFullYear(),
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            orientation: "bottom left",
            autoclose: true,
            templates: arrows,
        });
    }

    var datePicker = function () {
        $('.datepicker').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            format: "dd-mm-yyyy",
            orientation: "bottom left",
            autoclose: true,
            templates: arrows,
        });
    }

    var dateTimePicker = function () {
        var y = $('.datetimepicker').data('datey');
        var m = $('.datetimepicker').data('datem');
        var d = $('.datetimepicker').data('dated');
        var h = $('.datetimepicker').data('dateh');
        var i = $('.datetimepicker').data('datei');

        $('.datetimepicker').datetimepicker({
            defaultDate: new Date(y,m-1,d,h,i),
            locale: 'id',
            format: "DD-MM-YYYY HH:mm",
            autoclose: true
        });
    }

    var timePicker = function () {
        $('.timepicker').timepicker({
            minuteStep: 5,
            defaultTime: '',
            showSeconds: false,
            showMeridian: false,
            snapToStep: true
        });
    }

    var number = function () {
        $('.number').number(true);
    }

    var select2_ = function () {
        $('.form-select2').select2({
            placeholder: $(this).data('placeholder'),
            allowClear: true,
            "language": {
                "noResults": function () {
                    return "Tidak ada data ditemukan";
                }
            }
        });

        $('.form-select2').on('select2:select', function (){
            $(this).valid();
        });
    }

    var select2_timer = function () {
        setTimeout(function () {
            $('.form-select2-timer').select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                "language": {
                    "noResults": function () {
                        return "Tidak ada data ditemukan";
                    }
                }
            });

            // $('.form-select2').on('select2:select', function (){
            //     $(this).valid();
            // });
        }, 2000);
    }

    var cropper_option_ = function () {
        var URL = window.URL || window.webkitURL;
        $image = $('#crop-img');
        var $download = $('#download');
        var $dataX = $('#dataX');
        var $dataY = $('#dataY');
        var $dataHeight = $('#dataHeight');
        var $dataWidth = $('#dataWidth');
        var $dataRotate = $('#dataRotate');
        var $dataScaleX = $('#dataScaleX');
        var $dataScaleY = $('#dataScaleY');
        cropper_options = {
            aspectRatio: NaN,
            preview: '.crop-img-preview',
            crop: function (e) {
                $dataX.val(Math.round(e.x));
                $dataY.val(Math.round(e.y));
                $dataHeight.val(Math.round(e.height));
                $dataWidth.val(Math.round(e.width));
                $dataRotate.val(e.rotate);
                $dataScaleX.val(e.scaleX);
                $dataScaleY.val(e.scaleY);
            }
        };
        var originalImageURL = $image.attr('src');
        var uploadedImageName = 'cropped.jpg';
        var uploadedImageType = 'image/jpeg';
        var uploadedImageURL;

        // Tooltip
        $('[data-toggle="tooltip"]').tooltip();

        // Buttons
        if (!$.isFunction(document.createElement('canvas').getContext)) {
            $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
        }

        if (typeof document.createElement('cropper').style.transition === 'undefined') {
            $('button[data-method="rotate"]').prop('disabled', true);
            $('button[data-method="scale"]').prop('disabled', true);
        }

        // Download
        // if (typeof $download[0].download === 'undefined') {
        //     $download.addClass('disabled');
        // }

        // Options
        $('.docs-toggles').on('change', 'input', function () {
            var $this = $(this);
            var name = $this.attr('name');
            var type = $this.prop('type');
            var cropBoxData;
            var canvasData;

            if (!$image.data('cropper')) {
                return;
            }

            if (type === 'checkbox') {
                cropper_options[name] = $this.prop('checked');
                cropBoxData = $image.cropper('getCropBoxData');
                canvasData = $image.cropper('getCanvasData');

                cropper_options.ready = function () {
                    $image.cropper('setCropBoxData', cropBoxData);
                    $image.cropper('setCanvasData', canvasData);
                };
            } else if (type === 'radio') {
                cropper_options[name] = $this.val();
            }

            $image.cropper('destroy').cropper(cropper_options);
        });

        $('#crop-img-ratio').on('change', function () {
            var $this = $(this);
            var name = $this.attr('name');

            cropper_options[name] = $this.val();
        });

        // Methods
        $('.crop-buttons').on('click', '[data-method]', function () {
            var $this = $(this);
            var data = $this.data();
            var cropper = $image.data('cropper');
            var cropped;
            var $target;
            var result;

            if ($this.prop('disabled') || $this.hasClass('disabled')) {
                return;
            }

            if (cropper && data.method) {
                data = $.extend({}, data); // Clone a new one

                if (typeof data.target !== 'undefined') {
                    $target = $(data.target);

                    if (typeof data.option === 'undefined') {
                        try {
                            data.option = JSON.parse($target.val());
                        } catch (e) {
                            console.log(e.message);
                        }
                    }
                }

                cropped = cropper.cropped;

                switch (data.method) {
                    case 'rotate':
                        if (cropped && cropper_options.viewMode > 0) {
                            $image.cropper('clear');
                        }

                        break;

                    case 'getCroppedCanvas':
                        if (uploadedImageType === 'image/jpeg') {
                            if (!data.option) {
                                data.option = {};
                            }

                            data.option.fillColor = '#fff';
                        }

                        break;
                }

                result = $image.cropper(data.method, data.option, data.secondOption);

                switch (data.method) {
                    case 'rotate':
                        if (cropped && cropper_options.viewMode > 0) {
                            $image.cropper('crop');
                        }

                        break;

                    case 'scaleX':
                    case 'scaleY':
                        $(this).data('option', -data.option);
                        break;

                    case 'getCroppedCanvas':
                        if (result) {
                            // Bootstrap's Modal
                            $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

                            if (!$download.hasClass('disabled')) {
                                download.download = uploadedImageName;
                                $download.attr('href', result.toDataURL(uploadedImageType));
                            }
                        }

                        break;

                    case 'destroy':
                        if (uploadedImageURL) {
                            URL.revokeObjectURL(uploadedImageURL);
                            uploadedImageURL = '';
                            $image.attr('src', originalImageURL);
                        }

                        break;
                }

                if ($.isPlainObject(result) && $target) {
                    try {
                        $target.val(JSON.stringify(result));
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }
        });

        // Keyboard
        $(document.body).on('keydown', function (e) {
            if (e.target !== this || !$image.data('cropper') || this.scrollTop > 300) {
                return;
            }

            switch (e.which) {
                case 37:
                    e.preventDefault();
                    $image.cropper('move', -1, 0);
                    break;

                case 38:
                    e.preventDefault();
                    $image.cropper('move', 0, -1);
                    break;

                case 39:
                    e.preventDefault();
                    $image.cropper('move', 1, 0);
                    break;

                case 40:
                    e.preventDefault();
                    $image.cropper('move', 0, 1);
                    break;
            }
        });

        // Import image
        var $inputImage = $('#inputImage');

        if (URL) {
            $inputImage.change(function () {
                var files = this.files;
                var file;

                if (!$image.data('cropper')) {
                    return;
                }

                if (files && files.length) {
                    file = files[0];

                    if (/^image\/\w+$/.test(file.type)) {
                        uploadedImageName = file.name;
                        uploadedImageType = file.type;

                        if (uploadedImageURL) {
                            URL.revokeObjectURL(uploadedImageURL);
                        }

                        uploadedImageURL = URL.createObjectURL(file);
                        $image.cropper('destroy').attr('src', uploadedImageURL).cropper(cropper_options);
                        $inputImage.val('');
                    } else {
                        window.alert('Please choose an image file.');
                    }
                }
            });
        } else {
            $inputImage.prop('disabled', true).parent().addClass('disabled');
        }
    }

    var cropper_action_ = function () {
            $image          = $("#crop-img"),
            CropContainer   = $("#crop-img-container"),
            UploadImageBtn  = $("#upload-image");

        function MoveToCropArea(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $("#crop-img").attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);

                setTimeout(function () {
                    $image.on({
                        ready: function (e) {
                            console.log(e.type);
                        },
                        cropstart: function (e) {
                            console.log(e.type, e.detail.action);
                        },
                        cropmove: function (e) {
                            console.log(e.type, e.detail.action);
                        },
                        cropend: function (e) {
                            console.log(e.type, e.detail.action);
                        },
                        crop: function (e) {
                            console.log(e.type);
                        },
                        zoom: function (e) {
                            console.log(e.type, e.detail.ratio);
                        }
                    }).cropper(cropper_options);
                }, 100);
            }
        }

        $('#input-picture').change(function () {
            var size = parseFloat($(this)[0].files[0].size / 1024).toFixed(2);

            if (size > 2000) {
                size = (Math.round((size / 1024) * 100) / 100);
                swal.fire({
                    html: 'Ukuran file terlalu besar [' + size + 'MB]',
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "OK!",
                    customClass: {
                        confirmButton: "btn font-weight-bold btn-light-primary"
                    }
                });
            } else {
                MoveToCropArea(this);

                CropContainer.show();

                $image.cropper('destroy');
            }
        });

        UploadImageBtn.on('click', function () {
            uploadedImageType = 'image/png';
            ImageResult = $image.cropper('getCroppedCanvas');

            if($('#crop-img-type').val() == 'general-upload-image')
            {
                if(ImageResult.tagName != 'CANVAS')
                {
                    swal.fire({
                        html: 'Pilih foto terlebih dahulu',
                        icon: "error",
                        timer: 2000,
                        timerProgressBar: true,
                        willOpen: () => {
                            Swal.showLoading()
                        }
                    });
                }
                else
                {
                    $('#profile-picture').attr('src', ImageResult.toDataURL(uploadedImageType));
                    $('#picture-val').val('OK');

                    $("#uploadPictureModal").modal("hide");
                }
            }
        });
    }

    var tinymce_ = function () {
        tinymce.init({
            selector: '.tinymce',
            height: $(".tinymce").height(),
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table directionality',
                'emoticons template paste textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link',
            toolbar2: 'print preview media image | forecolor backcolor',
            image_advtab: true,
            setup: function(editor) {
                editor.on('change', function(e) {
                    tinymce.triggerSave();
                    $("#" + editor.id).valid();
                });
            }
        });
    }

    return {
        // public functions
        init: function() {
            dateRange_();
            yearPicker();
            datePicker();
            dateTimePicker();
            timePicker();
            number();
            select2_();
            select2_timer();
            cropper_option_();
            cropper_action_();
            tinymce_();
        }
    };
}();

jQuery(document).ready(function() {
    FormHelper.init();
});
