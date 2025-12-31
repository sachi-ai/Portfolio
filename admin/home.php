<a href="#" id="logoutBtn">
    <i class="fa fa-power-off"></i> Logout
</a>


<script>
$('#logoutBtn').click(function(e){
    e.preventDefault();
    start_loader(); // show loader
    $.ajax({
        url: _base_url_ + 'classes/Login.php?f=logout',
        method: 'POST',
        dataType: 'json',
        // data: {
        //     csrf_token: $('meta[name="csrf-token"]').attr('content')
        // },
        success: function(resp){
            // console.log("AJAX response:", resp); // 👈 DEBUG output
            if(resp.status == 'success'){
                window.location.href = _base_url_ + 'admin/login.php';
            } else {
                alert_toast("Logout failed", 'error');
            }
            end_loader(); // hide loader
        },
        error: function(){
            alert_toast("An error occurred while logging out.", 'error');
            end_loader(); // hide loader
        }
        // success: function(){
        //     window.location.href = _base_url_ + 'admin/login.php';
        // },
        // error: function(){
        //     alert_toast("Logout failed", 'error');
        //     end_loader();
        // }
    });
});
</script>
