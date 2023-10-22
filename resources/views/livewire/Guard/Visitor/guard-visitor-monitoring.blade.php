<div>
    <div class="row g-4 mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h1 class="app-page-title">Visitors  Monitoring</h1>

            <div id="realtime-clock" wire:ignore>
                <span id="datetime" class="h4"></span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <div class="container">
                        <div id="qrScanner" width="600px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @section('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            let hasQrScanned = false
            $(document).ready(function() {
                
            })

            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qrScanner",
                { fps: 10, qrbox: {width: 350, height: 350} },
                true
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);

            function onScanSuccess(decodedText) {
                // html5QrcodeScanner.clear()
                if (! hasQrScanned) {
                    hasQrScanned = true
                    
                    
                }
            }

            function onScanFailure(error) {
                console.warn(`Code scan error = ${error}`);
            }

            function updateDateTime() {
                const dateTimeElement = document.getElementById('datetime');
                const now = new Date();
                const dateTimeString = now.toLocaleString(); // Customize the format as needed

                dateTimeElement.innerText = dateTimeString;
            }

            // Initial call to display the date and time
            updateDateTime();

            // Update the date and time every second
            setInterval(updateDateTime, 1000);
        </script>
    @endsection
</div>