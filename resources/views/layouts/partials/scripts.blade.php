@vite(["resources/js/app.js"])

<script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/extensions/tinymce/tinymce.min.js') }}"></script>


<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
<script src="{{ asset('assets/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/compiled/js/app.js') }}"></script>
{{-- <script src="{{ asset('js/main.js') }}"></script> --}}

<script>
    $(document).ready(function() {
        $('.datatable').DataTable();
    });

    function printReport() {
        // Hide unnecessary elements
        const elementsToHide = document.querySelectorAll('.no-print');
        elementsToHide.forEach(element => {
            element.style.display = 'none';
        });

        // Get the main content to print
        const printContent = document.getElementById('printable-area').innerHTML;
        const printTitle = document.getElementById('report-title').innerHTML;

        // Open a new window and write the content
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(`
            <html>
                <head>
                    <title>${printTitle}</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        .no-print { display: none; }
                    </style>
                </head>
                <body>
                    ${printContent}
                </body>
            </html>
        `);

        // Print the content
        printWindow.document.close();
        printWindow.print();

        // Restore the original layout
        elementsToHide.forEach(element => {
            element.style.display = '';
        });
    }
</script>