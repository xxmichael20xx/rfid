<div>
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="alert alert-success d-flex flex-column w-100 align-items-center" wire:ignore>
                <span class="display-4 text-dark">Visitors  Monitoring</span>
                <span id="datetime" class="display-2 text-dark"></span>
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
            let qrLoading = null

            $(document).ready(function() {
                let html5QrcodeScanner = new Html5QrcodeScanner(
                    "qrScanner",
                    { fps: 10, qrbox: {width: 350, height: 350} },
                    false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure)
    
                function onScanSuccess(decodedText, decodedResult) {
                    if (! hasQrScanned) {
                        hasQrScanned = true
                        html5QrcodeScanner.clear()
                        qrLoading = Swal.fire({
                            title: 'Processing...',
                            allowOutsideClick: false,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        })
    
                        Livewire.emit('validateQrCode', '11_1698159530_Fvjv')
                    }
                }
    
                function onScanFailure(error) {
                    console.warn(`Code scan error = ${error}`)
                }

                /** Define qr actions */
                Livewire.on('guard.qr-processed', (e) => {
                    qrLoading.close()

                    setTimeout(() => {
                        Swal.fire({
                            icon: e.icon,
                            title: e.title,
                            text: e.message,
                        }).then(() => {
                            hasQrScanned = false
                            html5QrcodeScanner = new Html5QrcodeScanner(
                                "qrScanner",
                                { fps: 10, qrbox: {width: 350, height: 350} },
                                false
                            );
                            html5QrcodeScanner.render(onScanSuccess, onScanFailure)
                        })
                    }, 500)
                })
            })

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
