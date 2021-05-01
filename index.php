<!DOCTYPE HTML>
<html>
<?php 
	require_once "src/View.php";
	$load = new View('views/autoload.php');
?>

<head>

	<meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Millwood Constant Contact Admin Page</title>
 	<link rel="stylesheet" href="assets/bootstrap-3.4.0.css" />
    <link href="assets/styles.css" rel="stylesheet" />
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" />
    <script type="application/javascript" src="assets/jQuery-3.4.1.js"></script>
    <script src="assets/bootstrap.min.js"></script>
    <script src = "assets/cookie.js"></script>
    <script type="application/javascript" src = "assets/admin_js.js"></script>

</head>

<?php 
	$sess = new Sessions();
	$env = new Env();

?>

<?php

	$ccdata = array(
		'client_id'=> $env->attr['client_id'],
		'redirect_uri' => $env->attr['redirect_uri'],
		'base_url' => $env->attr['base_url'],
		'auth_method' => 'idfed',
		'response_type' => $env->attr['response_type'],
		'scope' => $env->attr['scope']
		);
		
		$cc = new CC($ccdata);

?>
<script type = "application/javascript">var auth_url = "<?php echo $cc->auth_url; ?>";</script>

<body>

	<div class = "container-fluid">

		<?php $header = new View('views/header_view.txt'); ?>

		<?php if ($sess->check_session() == true) { ?>
		<div id = "body" class = "container">


			<div id = "button-wrap">
				<?php 
				if (isset($_GET['access_token'])==false) {
					$redirect = new View('views/script_redirect_url_to_hash.txt'); 
				} else if (isset($_GET['access_token'])) {
					$cc->access_token= $_GET['access_token'];			
					$campaingbtn = new View('views/get_campaign_btn_view.txt');
					$success = new View('views/success_wrap.txt');
				}
				?>
			</div>
		</div>
	<?php } else { ?>

		<div id = "body" class = "container">
			<div id = "login">
				<div class = "row">
					<div class = "col-lg-2"></div>
					<div class = "col-lg-2">Pin Number : </div>
					<div class = "col-lg-2">
						<input class = "form-control" type = "password" name= "pin" id = "pin" maxlength="4" />
					</div>
				</div>
				<div class = "row" id = "status-row">
					<div class = "col-lg-2"></div>
					<div class = "col-lg-1"><button class = "btn btn-primary" id = "submit">Submit</button></div>
					<div class = "col-lg-4" id = "status"></div>
				</div>
		
			</div>
		</div>
		<?php 	} ?>
		<?php $modal = new View('views/modal.txt'); ?>
	</div>
	<script type = "application/javascript">
		
		<?php if (isset($env)) { ?>
			var env = {<?php foreach($env->attr as $key=>$item) {echo('"' . $key . '":"' . $item . '",');} ?> };
		<?php } ?>
	</script>
</body>
</html>