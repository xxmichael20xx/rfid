<div>
    <button type="button" class="btn btn-danger text-white p-2" onclick="confirmDelete(`{{ $modelKey }}`)">
        <i class="fa fa-trash"></i>
    </button>
    <button type="button" class="hidden" id="{{ $modelKey }}" wire:click="delete"></button>
    <script>
        function confirmDelete(id)
        {
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                text: 'Homeowner will be deleted!',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes, confirm'
            }).then((e) => {
                if (e.isConfirmed) {
                    document.getElementById(id).click()
                }
            })
        }
    </script>
</div>

