<?php
require_once 'inc/connect.php';

require_once 'inc/config.php';

$page['name'] = 'Login';
?>
<?php include 'inc/page-top.php'; ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#userLogin').ajaxForm(function(error) {
            console.log(error);
            error = JSON.parse(error);
            if (error['msg'] === "") {
                toastr.success('Ingresando...', 'System:', {
                    timeOut: 10000
                })
                window.location.href = "index.php";
            } else {
                toastr.error(error['msg'], 'System:', {
                    timeOut: 10000
                })
            }
        });
    });
</script>

<body>
    <?php
        if (isset($_GET['error']) && strip_tags($_GET['error']) === 'banned') {
            throwError('Tas exiliado wn, vete pa la chucha.');
        } elseif (isset($_GET['error']) && strip_tags($_GET['error']) === 'access') {
            throwError('Primero ingresa para ver eso.');
        }
        ?>
    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="text-center">
            <a href="<?php echo $url['index']; ?>" class="logo"><img src="assets/images/sheriffmotd.png" width="150" height="130"></a>
        </div>
        <div class="m-t-40 card-box">
            <div class="p-10">
                <form class="form-horizontal m-t-10" id="userLogin" action="inc/backend/user/auth/userLogin.php" method="POST">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" name="username" placeholder="Usuario">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" required="" name="password" placeholder="Contraseña">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-30">
                        <div class="col-xs-12">
                            <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">Ingresar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'inc/page-bottom.php'; ?>
