$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
