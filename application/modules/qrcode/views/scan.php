<div class="container">
    <div class="row">
        <div class="col-12">
            <div id="loading-message"><?=$this->lang->line('msg_no_cam')?></div>
            <video id="video" hidden style="max-width: 100%;"></video>
        </div>
    </div>
    <form action="<?=base_url('qrcode/scanQRCode/read')?>" method="POST" id="form">
        <input type="hidden" name="json" id="json">
    </form>
</div>
<script type="text/javascript">
    var video = document.createElement("video");
    var canvasElement = document.getElementById("video");
    var canvas = canvasElement.getContext("2d");
    var loadingMessage = document.getElementById("loading-message");
    var sumbited = false;

    // Use facingMode: environment to attemt to get the front camera on phones
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
      video.srcObject = stream;
      video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
      video.play();
      requestAnimationFrame(tick);
    });

    function tick() {
        loadingMessage.innerText = "<?=$this->lang->line('msg_loading_video')?>"
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            loadingMessage.hidden = true;
            canvasElement.hidden = false;

            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth
            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
            var code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });
            if (code && !sumbited) {
                sumbited = true;
                document.getElementById('json').value = code.data;
                document.getElementById('form').submit();
            }
        }
        requestAnimationFrame(tick);
    }
</script>