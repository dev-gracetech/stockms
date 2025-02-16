@vite(["resources/js/app.js"])

<script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/extensions/tinymce/tinymce.min.js') }}"></script>


<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
<script src="{{ asset('assets/extensions/chartjs/chart.min.js') }}"></script>
<script src="{{ asset('assets/compiled/js/app.js') }}"></script>
{{-- <script src="{{ asset('js/main.js') }}"></script> --}}

<script>
    $(document).ready(function() {
        $('.datatable').DataTable();
    });
</script>