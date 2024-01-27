$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
    if (options.crossDomain) {
        delete options.headers['X-CSRF-TOKEN'];
    }
});

const _URL = window.URL || window.webkitURL;

// Helpers
const checkLimitHightAndWidthFromInputImages = async (image, maxWidth, maxHeight) => {
    const response = new Promise((resolve, reject) => {
        img = new Image();
        const objectUrl = _URL.createObjectURL(image);
        img.onload = () => {
            _URL.revokeObjectURL(objectUrl);
            if(this.img.width > maxWidth || this.img.height > maxHeight) {
                resolve(false);
            } else {
                resolve(true);
            }
        }
        img.onerror = reject
        img.src = objectUrl;
    });
    return await response;
}

const formatDateForDatatable = (date) => {
    return date.toLocaleString('pt-BR', {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'});
}

const formatMoney = (value) => {
    const formatMoney = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' });
    return formatMoney.format(value);
}

// Masks
$('.zip_code_mask').mask('00000-000')
$('.cpf_mask').mask('000.000.000-00', {reverse: true});
$('.cnpj_mask').mask('00.000.000/0000-00', {reverse: true});
$('.money').mask("#.##0,00", {reverse: true});
const SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
  },
  spOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
      }
  };

$('.cell_phone_mask').mask(SPMaskBehavior, spOptions);
