<!--   Core JS Files   -->
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
var win = navigator.platform.indexOf('Win') > -1;
if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
    damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
}
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
<script src="assets/js/material-dashboard.js?v=3.1.0"></script>
<script src="libs/jquery.min.js"></script> 
<script src="libs/notify.min.js"></script> 
<script src="libs/sweetalert2/sweet.js"></script> 
<script src="libs/jquery-ui/jquery-ui.min.js"></script> 
<script src="libs/datatables/jquery.dataTables.min.js"></script> 
<script src="libs/datatables/dataTables.bootstrap4.min.js"></script> 
<script src="js/main.js"></script>

