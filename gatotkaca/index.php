<!DOCTYPE html>
<html>
<head>
<title>Gatotkaca - Sprit Animation</title>
<style>
canvas {
    border: 1px solid #000000;
}
</style>
<script src="js/main.js"></script>
<script>
window.onload = function() {
    var myCanvas = document.getElementById("myCanvas");
    var myGame = new MyGame(myCanvas);
    myGame.init();
    myGame.start();
}    
</script>
<?php include '../header.php'; ?>
</head>
<body>
<canvas id="myCanvas" width="640" height="480"></canvas>
</body>
</html>