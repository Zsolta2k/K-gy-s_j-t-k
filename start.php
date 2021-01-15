<!DOCTYPE html>
<?php
$dbconnect=mysqli_connect('localhost:3308','root','','snake');

if(isset($_POST['submit']))
{
	
  $nev=$_POST['nev'];
$score=$_POST['score'];
$sql=mysqli_query($dbconnect,"INSERT INTO users(nev,score) values('$nev','$score')");
}
?>
<html>
  <head>
  <link rel="stylesheet" href="Snake_Style.css" >
  	<title>Snake Game</title>
  </head>

  <body>
  
    <table id="pictab">
  <tr>
        
        <th id="pic"><img src="Snake_logo.png"></th>
       
</tr>
</table>
  <div id="minden">
 
 <br>
    <table>
     
  <tr>
    <th id="scorelist"><?php	
	$dbconnect=mysqli_connect('localhost:3308','root','','snake');
   $sql = "SELECT * FROM users Order BY score";
   $result = mysqli_query($dbconnect,$sql);
   $resultcheck=mysqli_num_rows($result);
   if($resultcheck >0)
   {
	   while($row=mysqli_fetch_assoc($result))
	   {
      
      
        echo  "<table id='ectab'><tr id='ectab'><th id='ectab'>User: ".$row['nev']."</th> <th id='ectab'> score: ".$row['score']."</th></tr></table><br>";
      
        
     
	   }
   }
	?>	</th>
    <th> <canvas id="snakeboard" width="400" height="400"></canvas></th> 
    <th id="leírás">A Kígyózó kígyózás másnéven Snake játék egy mindenki által bizonyára ismert egyszerű de szórakoztató játék.
     Szabályok: <br>
     <ul>
  <li>a kígyó irányításával vedd fel az almákat (a piros négyzeteket) és gyűjts össze minél több pontot.</li>
  <li>Ha falnak ütközöl vége a játéknak,ugyanez vonatkozik arra ha megeszed a kígyó testének az egyik részét.</li>
  
</ul>
  A pontjaid elmentésére lehetőséged van amit egy listával ki lehet majd listázni hogy
       megnézd az elmentett pontok közül a tiéd hanyadik.</th>
  </tr>
 
  <tr id="bott">
    <th><input type="button" value="Start" onclick="start()">
    <input type="button" value="Restart" onclick="restart()">
    </th>
    <th>
 <form action="" method="post">
Name: <input type="text" name="nev"><br>
Score: <input type="text" name="score" id="scoore" value="0" READONLY ><br>
<input type="submit" name="submit">
</form>
</th> 
    <th><div id="score">0</div></th>
 </tr>
  
  </table>
  </div>
   
   
<footer>
			<hr />
			<p>Copyright &copy; 2020 AFP1 Trio. All Rights Reserved</p>
		</footer>
  </body>
</html>
  <script>
  function restart(){
	score=0;
    document.getElementById('score').innerHTML = score;
    start();  
  }
  function start(){
    const board_border = 'black';
    const board_background = "lightgreen";
    const snake_col = 'darkgreen';
    const snake_border = 'darkyellow';
    
    let snake = [
      {x: 200, y: 200},
      {x: 190, y: 200},
      {x: 180, y: 200},
      {x: 170, y: 200},
      {x: 160, y: 200}
    ]

    let score = 0;
    let changing_direction = false;
    let food_x;
    let food_y;
    let dx = 10;
    let dy = 0;
    
    const snakeboard = document.getElementById("snakeboard");
    const snakeboard_ctx = snakeboard.getContext("2d");
    main();
    gen_food();
    document.addEventListener("keydown", change_direction);
 
    function main() {

        if (has_game_ended()) return;

        changing_direction = false;
        setTimeout(function onTick() {
        clear_board();
        drawFood();
        move_snake();
        drawSnake();
        main();
      }, 100)
      
    }
    
    function clear_board() {
      snakeboard_ctx.fillStyle = board_background;
      snakeboard_ctx.strokestyle = board_border;
      snakeboard_ctx.fillRect(0, 0, snakeboard.width, snakeboard.height);
      snakeboard_ctx.strokeRect(0, 0, snakeboard.width, snakeboard.height);
    }
    function drawSnake() {
      snake.forEach(drawSnakePart)
    }

    function drawFood() {
      snakeboard_ctx.fillStyle = 'red';
      snakeboard_ctx.strokestyle = 'darkred';
      snakeboard_ctx.fillRect(food_x, food_y, 10, 10);
      snakeboard_ctx.strokeRect(food_x, food_y, 10, 10);
    }
    
    function drawSnakePart(snakePart) {
      snakeboard_ctx.fillStyle = snake_col;
      snakeboard_ctx.strokestyle = snake_border;
      snakeboard_ctx.fillRect(snakePart.x, snakePart.y, 10, 10);
      snakeboard_ctx.strokeRect(snakePart.x, snakePart.y, 10, 10);
    }

    function has_game_ended() {
      for (let i = 4; i < snake.length; i++) {
        if (snake[i].x === snake[0].x && snake[i].y === snake[0].y) return true
      }
      const hitLeftWall = snake[0].x < 0;
      const hitRightWall = snake[0].x > snakeboard.width - 10;
      const hitToptWall = snake[0].y < 0;
      const hitBottomWall = snake[0].y > snakeboard.height - 10;
      return hitLeftWall || hitRightWall || hitToptWall || hitBottomWall
    }

    function random_food(min, max) {
      return Math.round((Math.random() * (max-min) + min) / 10) * 10;
    }

    function gen_food() {
      food_x = random_food(0, snakeboard.width - 10);
      food_y = random_food(0, snakeboard.height - 10);
      snake.forEach(function has_snake_eaten_food(part) {
        const has_eaten = part.x == food_x && part.y == food_y;
        if (has_eaten) gen_food();
      });
    }

    function change_direction(event) {
      const LEFT_KEY = 37;
      const RIGHT_KEY = 39;
      const UP_KEY = 38;
      const DOWN_KEY = 40;
    
      if (changing_direction) return;
      changing_direction = true;
      const keyPressed = event.keyCode;
      const goingUp = dy === -10;
      const goingDown = dy === 10;
      const goingRight = dx === 10;
      const goingLeft = dx === -10;
      if (keyPressed === LEFT_KEY && !goingRight) {
        dx = -10;
        dy = 0;
      }
      if (keyPressed === UP_KEY && !goingDown) {
        dx = 0;
        dy = -10;
      }
      if (keyPressed === RIGHT_KEY && !goingLeft) {
        dx = 10;
        dy = 0;
      }
      if (keyPressed === DOWN_KEY && !goingUp) {
        dx = 0;
        dy = 10;
      }
    }

    function move_snake() {
      const head = {x: snake[0].x + dx, y: snake[0].y + dy};
      snake.unshift(head);
      const has_eaten_food = snake[0].x === food_x && snake[0].y === food_y;
      if (has_eaten_food) {
        score += 10;
        document.getElementById('score').innerHTML = score;
		document.getElementById('scoore').value = score;
		
        gen_food();
      } else {
        snake.pop();
      }
    }
  }   
  </script>
    </script>