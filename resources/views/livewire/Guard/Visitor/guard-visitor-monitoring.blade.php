<div>
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="alert alert-success d-flex flex-column w-100 align-items-center" wire:ignore>
                <span class="display-4 text-dark">Visitors Monitoring</span>
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

    @livewire('guard.visitor.guard-visitor-entry')
    @livewire('guard.visitor.guard-visitor-exit')

    @section('scripts')
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        <script>
            let hasQrScanned = false
            let qrLoading = null
            let html5QrcodeScanner = null

            $(document).ready(function() {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qrScanner",
                    { fps: 10, qrbox: {width: 350, height: 350} },
                    false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure)

                const search = new URLSearchParams(window.location.search)
                if (search.has('testing')) {
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

                        Livewire.emit('validateQrCode', search.get('testing'))
                    }
                }

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

                        Livewire.emit('validateQrCode', decodedText)
                    }
                }

                function onScanFailure(error) {
                    console.warn(`Code scan error = ${error}`)
                }

                /** Define qr actions */
                Livewire.on('guard.qr-processed', (e) => {
                    qrLoading.close()

                    setTimeout(() => {
                        if (e.type == 'notif') {
                            Swal.fire({
                                icon: e.icon,
                                title: e.title,
                                text: e.message
                            })
                        }

                        hasQrScanned = false
                        html5QrcodeScanner = new Html5QrcodeScanner(
                            "qrScanner",
                            { fps: 10, qrbox: {width: 350, height: 350} },
                            false
                        );
                        html5QrcodeScanner.render(onScanSuccess, onScanFailure)
                    }, 1500)
                })
            })

            function updateDateTime() {
                const dateTimeElement = document.getElementById('datetime')
                const now = new Date()
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'numeric',
                    day: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: true
                };

                const dateTimeString = now.toLocaleString('en-US', options)
                dateTimeElement.innerText = dateTimeString
            }

            // Initial call to display the date and time
            updateDateTime();

            // Update the date and time every second
            setInterval(updateDateTime, 1000);
        </script>
    @endsection
</div>
