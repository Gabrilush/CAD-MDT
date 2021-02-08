<?php
require_once 'inc/connect.php';
require_once 'inc/config.php';
$page['name'] = 'Register';
?>
<?php include 'inc/page-top.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#userRegister').ajaxForm(function(error) {
            console.log(error);
            if (error['msg'] == "") {
                toastr.success('Registrado, redireccionando al ingreso.', 'System', {
                    timeOut: 10000
                })
                window.location.href = "<?php echo $url['login']; ?>";
            } else {
                toastr.error(error['msg'], 'System', {
                    timeOut: 10000
                })
            }
        });
    });
</script>

<body>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="text-center">
        <a href="<?php echo $url['index']; ?>" class="logo"><img src="assets/images/sheriffmotd.png" width="150" height="130"></a>
        </div>
        <div class="m-t-40 card-box">
            <div class="text-center">
                <h4 class="text-uppercase font-bold mb-0">Registro de cuenta</h4>
            </div>
            <div class="p-20">
                <form class="form-horizontal m-t-20" id="userRegister" action="inc/backend/user/auth/userRegister.php" method="POST">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" name="username" placeholder="Usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="email" required="" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" required="" name="password" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-30">
                        <div class="col-xs-12">
                            <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">Registrarse</button>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center">
                        <p class="text-muted">Si ya tiene cuenta, ingrese<a href="<?php echo $url['login']; ?>" class="text-primary m-l-5"><b>aquí</b></a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'inc/page-bottom.php'; ?>
