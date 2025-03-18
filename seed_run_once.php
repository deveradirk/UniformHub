<?php
    include "database/connect.php";

    $stmt = $dbconn->prepare("INSERT INTO users(user_id,fullname,email,password,role) VALUES(?,?,?,?,?)");

    $user_record = [
	"03-2324-032323;John Doe;johndoe@gmail.com;password1;student",
	"03-2324-032325;John Deacon;johndeacon@gmail.com;password2;student",
	"03-2324-032326;John Wick;johnwick@gmail.com;password3;student",
	"03-2324-032327;John Smith;johnsmith@gmail.com;password4;student",
	"03-2324-032328;John The Baptist;johnthebaptist@gmail.com;password5;student",
	"03-2324-032329;John Cena;johncena@gmail.com;password6;student"
    ];
    
    foreach($user_record as $user){
	$record = explode(";",$user);
	$record[3] = password_hash($record[3], PASSWORD_ARGON2ID);
	$stmt->execute($record);
    }


    $uniform_stmt = $dbconn->prepare("INSERT INTO uniforms(category, name, size, department, image_url) VALUES(?,?,?,?,?)");
    $stock_stmt = $dbconn->prepare("INSERT INTO stocks(fk_uniform_id) VALUES(?)");

    
    $uniform_record = [
	"Uniform;Pride;xs;CITE;CITE_Pride",
	"Uniform;RSO;s;CITE;CITE_RSO",
	"Uniform;RSO;m;CAS;CAS_RSO"
    ];

    foreach($uniform_record as $uniform){
	$record = explode(";",$uniform);
	for($i = 0; $i < 3;$i++){
	    $uniform_stmt->execute($record);
	    $lastId = $dbconn->lastInsertId();
	    $stock_stmt->execute([$lastId]);
	}
    }

    echo "Database seeded.";
