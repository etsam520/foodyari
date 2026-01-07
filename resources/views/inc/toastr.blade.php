@if (Session::has("success"))
    <script>
        toastr.success('{{ Session::get("success") }}');
    </script>
@endif
@if (Session::has("info"))
    <script>
        toastr.info('{{ Session::get("info") }}');
    </script>
@endif

@if (Session::has("error"))
    <script>
        toastr.info('{{ Session::get("error") }}');
    </script>
@endif
@if (Session::has("warning"))
    <script>
        toastr.warning('{{ Session::get("warning") }}');
    </script>
@endif
@if (Session::has("sweet_success"))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ Session::get("sweet_success") }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@if (Session::has("sweet_info"))
    <script>
        Swal.fire({
            icon: 'info',
            title: '{{ Session::get("sweet_info") }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@if (Session::has("sweet_error"))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ Session::get("sweet_error") }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

@if (Session::has("sweet_warning"))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: '{{ Session::get("sweet_warning") }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif
