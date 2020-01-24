<div class="container">
    <div class="row">
        <video id="preview"></video>
        <p id="content"></p>
        <script type="text/javascript">
            function scan(){
                let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
                scanner.addListener('scan', function (content) {
                    console.log(content);
                    scanner.stop();
                    document.getElementById('content').innerHTML = content;
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
        <button onclick="scan()">Scan</button>
    </div>
</div>
