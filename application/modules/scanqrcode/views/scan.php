<div class="container">
    <div class="row">
        <div class="col-12">
            <button onclick="scan()"><?=$this->lang->line('btn_scan')?></button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <video id="preview"></video>
        </div>
    </div>
    <form action="<?=base_url('scanqrcode/read')?>" method="POST" id="form">
        <input type="hidden" name="json" id="json">
    </form>
</div>
<script type="text/javascript">
    function scan(){
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            console.log(content);
            scanner.stop();
            document.getElementById('json').value = content;
            document.getElementById('form').submit();
        });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
    }
</script>