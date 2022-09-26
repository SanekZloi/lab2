<?ob_start();
session_start();?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"> 			
		<title>Барилюк А.Р. Лабораторная работа №2</title>
	</head>
	<body>
		<?php
			$host = 'localhost'; // имя хоста
			$user = 'root';      // имя пользователя
			$pass = '';          // пароль
			$name = 'lab2';      // имя базы данных
			
			$link = mysqli_connect($host, $user, $pass, $name);  //подключение к бд

			if (isset($_POST['username'])) {  //проверка на отправку формы
		        $username = $_POST['username']; 
		        $password = $_POST['password'];
		        $query = mysqli_query($link, "SELECT * FROM users WHERE username='".$username."'"); //находим пользователей с введеным логином

		        $result = mysqli_fetch_assoc($query); //из формата бд переводим на понятный для php

		        if (!$result) { //проверка на пустой ли запрос, то есть, нашли ли мы пользователя
		            echo '<p class="error">Неверные пароль или имя пользователя!</p>';
	        	} 
		        else {
		            if (md5($password) == $result['password']) { // если нашли, то сравниваем правильный ли пороль в бд
		                $_SESSION['user_id'] = $result['id']; //записываем сессию id юзера
		            } else {
		                echo '<p class="error">Неверные пароль или имя пользователя!</p>'; //соответственно если пороль неверный, то выдает ошибку
		            }
		        }
    		}

    		if (isset($_SESSION['user_id']) && isset($_POST['text'])){ //проверка входа в систему и на редактирование текста в форме
    			mysqli_query($link,"UPDATE content SET content ='".$_POST['text']."' WHERE content.id = 1"); //редактирование текста, другими словами обновление базы данных
    		}

    		if (isset($_GET['des'])) { //выход из учетной записи
		    session_destroy(); //разрушение сессии
		    header('Location: http://lab3:8080//index.php'); //обновление страницы (переадресация)
		}
 		?>
			<?php
				$query1 = mysqli_query($link, "SELECT * FROM content WHERE id = 1"); //поиск контента
		        $result1 = mysqli_fetch_assoc($query1); //из формата бд в php

			 if (!isset($_SESSION['user_id'])): // если пользователь не авторизирован, то он не может изменять контент
			 	echo $result1['content']; //контент страницы
			 	?> 

  		<form name="login" action="" method="POST">  <!-->форма авторизации</!-->
		    <input type="text" name="username" placeholder="Username"> 
		    <input type="password" name="password" placeholder="Password">
		    <input type="submit" value="Login" id="login-form-submit">
		</form>
	<?php else: ?>
		<form name="text" action="" method="POST">  <!-->форма редактирования контента</!-->
		    <? 
		        echo '<input type="text" name="text" value="'.$result1['content'].'">';
		    ?>
		    <input type="submit" value="editing" id="login-form-submit">
		</form>
		<a href="index.php/?des=true"> <button type="button" >Exit</button></a> <!-->кнопка выхода из учетной записи</!-->
	<?php endif; ?> 
 		
	</body>
</html>

