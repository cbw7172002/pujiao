
oFReader = new FileReader(), rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;

oFReader.onload = function (oFREvent) {
    document.getElementById("uploadPreview").src = oFREvent.target.result;
    document.getElementById("uploadPreview").width = '246';
    document.getElementById("uploadPreview").height = '200';
};

function loadImageFile() {
    if (document.getElementById("uploadImage").files.length === 0) { return; }
    var oFile = document.getElementById("uploadImage").files[0];
    if (!rFilter.test(oFile.type)) { alert("请上传正确的图片格式！"); return; }
    oFReader.readAsDataURL(oFile);
}