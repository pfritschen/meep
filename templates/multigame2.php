<?php 
session_start();
ob_start();

$username2=$_SESSION['name'];
?>

<style type="text/css">
hr {color:sienna;}
p {margin-left:20px;}
body
{
margin-left:50px;
margin-top:50px;
Background:#5d9ab2
url('http://nu-tips.com/wp-content/uploads/2011/09/cool-abstract-wallpaper.jpg');
}

.container
{
text-align:center;
}

.center_div
{
border:5px solid gray;
margin-left:auto;
margin-right:auto;
width:90%;
background-color:#d0f0f6;
text-align:left;
padding:8px;
}
</style>
<FONT COLOR = WHITE>



<?php 

$numGuessesForPrinting;
$activeGame=-1;
//$_SESSION['name']= $_POST[myusername];
//$guessCount =0;
$host="mysql-user.cse.msu.edu"; // Host name
$username="fritsch7"; // username
$password="A40648129"; // password
$db_name="fritsch7"; // Database name

$tbl_name="User"; // Table name

// Replace database connect functions depending on
// dAtabasnmeweb/index.htmlchmod 644 ~/web/index.html<html>
// you are using.
$con = mysql_connect("$host", "$username", "$password");
mysql_select_db("$db_name");

$result = mysql_query("SELECT Game_idGame FROM Game_has_Player WHERE Player_User_idUser ='$username2'");  //results is the list of all games buy current player, check if any have outcome=null(only active game for single player)
while($row = mysql_fetch_array($result) )     
{
 $gameId=$row["Game_idGame"];
 $result2 = mysql_query("SELECT * FROM Game WHERE idGame ='$gameId' and outcome is NULL");
 while($row2 = mysql_fetch_array($result2) )   
{
 $activeGame= $row2["idGame"];                                     //THIS LINE GETS THE ACTIVE GAME
}
}
if ($activeGame==-1){

//check to see if the user has an exisitng game
// 
//	if so display there game state
// 	if not start a new game (insert into database)

 


//*****************************************//MAKING A NEW GAME
	$result = mysql_query("SELECT * from Dictionary ORDER BY RAND() LIMIT 0,1");
	while($row = mysql_fetch_array($result) )                   
	{
	 	$Wname=$row["WordName"];
	 	$WDef=$row["wordDefinition"];
	 	$sql="Insert Into Game (numberOfPlayers, Player1Points, Dictionary_WordName, Meaning) Values (1,0,'$Wname', '$WDef')";
		if (!mysql_query($sql,$con))
		{
	   		 die('Error: ' . mysql_error()); 
 	   		echo "PROBLEM";
		}
		$result2 = mysql_query("Select * From Game");
		$gameId="temp";
		while($row2 = mysql_fetch_array($result2) )
		{
			$gameId=$row2["idGame"];
		}


		$sql="Insert Into Game_has_Player (Game_idGame, Player_User_idUser) Values ('$gameId', '$username2')";
		if (!mysql_query($sql,$con))
		{
	  	  die('Error: ' . mysql_error()); 
	   	 echo "PROBLEM";
		}
$result = mysql_query("SELECT Game_idGame FROM Game_has_Player WHERE Player_User_idUser ='$username2'");  //results is the list of all games buy current player, check if any have outcome=null(only active game for single player)
while($row = mysql_fetch_array($result) )     
{
 $gameId=$row["Game_idGame"];
 $result2 = mysql_query("SELECT * FROM Game WHERE idGame ='$gameId' and outcome is NULL");
 while($row2 = mysql_fetch_array($result2) )   
{
 $activeGame= $row2["idGame"];                                     //THIS LINE GETS THE ACTIVE GAME
}
}


	}
//******************************************
}
else{
//echo("ACTIVE GAME:  ");

}

  $scoreResult = mysql_query("Select totalScore from Player Where User_idUser='$username2' ");              //or any previous guesses

  while($scoreRow = mysql_fetch_array($scoreResult) )                   
 {

   $totalScore = $scoreRow["totalScore"];
		   

  }


echo("ACTIVE GAME:  ");


echo ($activeGame);

 //$numGuessesForPrinting = "0";
 //find number of guesses
/*
 $numberOfGuesses = mysql_query("SELECT count(*) as tempCnt FROM Guess WHERE Game_idGame = '$activeGame'");  //get count of guesses
 while($row20 = mysql_fetch_array($numberOfGuesses) ) 
 {
   //echo( $row20['tempCnt'] ."<br />" );
   $numGuessesForPrinting = $row20['tempCnt'];
 }
*/

 echo "<br />";
 echo "<br />";

 echo "<br />";




$result = mysql_query("SELECT * from Game where idGame='$activeGame'");   




//**********************************************Displaying Game State
while($row = mysql_fetch_array($result) ) 
{
 echo "Word:  ";
 echo( $row["Dictionary_WordName"] ."<br />" );

 echo "Word: ";


 $correct7 =false;

 for ($i = 1; $i < strlen($row["Dictionary_WordName"]); $i++)
 {
    if($i==1)
    {
      // $firstword = substr($row["WordName"], 1);    // returns  first letter
      echo $row["Dictionary_WordName"][0] ; // returns first letter
    }
    else
    {
 
 }
$win=false;
 if (isset($_POST['Submit1'])) // catch a user guess on click of guess button
 {
   for ($i = 1; $i < strlen($row["Dictionary_WordName"]); $i++){

  $userguess = $_POST['guess'];
 // echo(strlen($userguess));
 
  $inserted=false;
  $print=false;
   $found3=false;

 	if (strlen($userguess)==1)   //guessing letter only
	{
			$found=false;
	    		$result7 = mysql_query("Select guess from Guess Where Game_idGame='$activeGame' ");              //or any previous guesses

		   	   while($row7 = mysql_fetch_array($result7) and $found==false)                   
			   {
				if($row7["guess"] != Null){

					// echo "<br />";
					//echo($row["guess"]);
			
				 if ($row7["guess"] == $row["Dictionary_WordName"][$i] )
					{
					
					 echo ($row7["guess"]);
					$found=true;
					}
				}	

			   }
    
 	   if ($found==false and $userguess == $row["Dictionary_WordName"][$i] )  //check if letter guessed is in word
 	   {
 		   
		     if ($inserted==false and strlen($userguess)>0){
				$sql=" INSERT INTO Guess (guess, Game_idGame, Player_User_idUser, correctness) Values ('$userguess','$activeGame', '$username2', 'Yes') ";
				mysql_query($sql,$con);
				//echo("ZOMG WTF NOOBS");
                                 $correct7=true;		
	 		  	 $print=true;
			  	 $inserted=true;
$numberOfGuesses = mysql_query("SELECT count(*) as tempCnt FROM Guess WHERE Game_idGame = '$activeGame'");  //get count of guesses
 while($row20 = mysql_fetch_array($numberOfGuesses) ) 
 {
  // echo( $row20['tempCnt'] ."<br />" );
   $numGuessesForPrinting = $row20['tempCnt'];
 }

                           //add to score
	        	$sql="UPDATE Player  SET totalScore = ($totalScore+1) where User_idUser = '$username2'"; //update score
		        if (!mysql_query($sql,$con))
			{
	  		  die('Error: ' . mysql_error()); 
	   		  echo "PROBLEM";
			}


		      }
		
	   		
 	   } 
 	
		if($print==false and $found==false and $i!=(strlen($row["Dictionary_WordName"])-1))
 		      echo " _ "; //guessed letter doesnt match that part of the string, so print -
 	   
		if($print==true)
 			 echo $userguess; //correctly guessed letter so print


 	}


  	else   //guess is not equal to one      
  	{
                if(strlen($userguess)!=1) // guessing whole word
                {
                       $trimmed = trim($row["Dictionary_WordName"]);
                       //check if guess is correct
                       if(strcmp($userguess,$trimmed) ==0 ) // user won
                       {
                            
	                	$sql="UPDATE Game  SET outcome = 'win' where idGame = '$activeGame'"; //update game outcome 
		            if (!mysql_query($sql,$con))
		            {
	  	            die('Error: ' . mysql_error()); 
	   	            echo "PROBLEM";
		            }


                        //add to score
	        	$sql="UPDATE Player  SET totalScore = ($totalScore+5) where User_idUser = '$username2'"; //update score
		        if (!mysql_query($sql,$con))
			{
	  		  die('Error: ' . mysql_error()); 
	   		  echo "PROBLEM";
			}
			$win=true;			

                           //echo $userguess;
                               
                     		echo "<script type = 'text/javascript'>
					alert( 'YOU WIN! 5 points added to your score!');
					location.replace('http://www.cse.msu.edu/~speetern/cgi-bin/gameMenu.php');
					
					</script>";  
				



                        }
             
                        else // wrong guess
                        {
                           $result7 = mysql_query("Select guess from Guess Where Game_idGame='$activeGame' ");              //or any previous guesses
              		 $found3=false;
             
		   	   while($row7 = mysql_fetch_array($result7) and $found3 == false)                   
			   {
				if($row7["guess"] != Null){

					// echo "<br />";
					//echo($row["guess"]);
			
				 if ($row7["guess"] == $row["Dictionary_WordName"][$i] )
					{
					
					 echo ($row7["guess"]);
					$found3=true;
					}
				}	

			   }
    

                         }
                       
                }
                if ($found3==false and $i!=strlen(($row["Dictionary_WordName"])-1) )
                {
      		  echo " _ ";//
                }
  	}

 }

if ($inserted==false and strlen($userguess)>0 and $correct7 ==false){
$sql=" INSERT INTO Guess (guess, Game_idGame, Player_User_idUser, correctness) Values ('$userguess','$activeGame', '$username2', 'No') ";
				mysql_query($sql,$con);
$numberOfGuesses = mysql_query("SELECT count(*) as tempCnt FROM Guess WHERE Game_idGame = '$activeGame'");  //get count of guesses
 while($row20 = mysql_fetch_array($numberOfGuesses) ) 
 {
  // echo( $row20['tempCnt'] ."<br />" );
   $numGuessesForPrinting = $row20['tempCnt'];
 }

                       //subtract 1 from score
			if ($win==false and $numGuessesForPrinting != 4){        //win flag is when you win 
	        	$sql="UPDATE Player  SET totalScore = ($totalScore-1) where User_idUser = '$username2'"; //update score
		        if (!mysql_query($sql,$con))
			{
	  		  die('Error: ' . mysql_error()); 
	   		  echo "PROBLEM";
			}

}
}


}
 else   //for first time loading the page     IF NO SUBMIT
 {
                         
                         $result7 = mysql_query("Select guess from Guess Where Game_idGame='$activeGame' ");              //or any previous guesses
              		 $found3=false;
             
		   	   while($row7 = mysql_fetch_array($result7) and $found3 == false)                   
			   {
				if($row7["guess"] != Null){

					// echo "<br />";
					//echo($row["guess"]);
			
				 if ($row7["guess"] == $row["Dictionary_WordName"][$i] )
					{
					
					 echo ($row7["guess"]);
					$found3=true;
					}
				}	

			   }
                         if ($found3==false )
                         {
      		               echo " _ ";//
                          }



 }

    }
 echo "<br />";
 echo "\n";
 echo "Hint:  ";
 echo( $row["Meaning"]);
 echo "\n"; 
 echo "<br />";

  $scoreResult = mysql_query("Select totalScore from Player Where User_idUser='$username2' ");              //or any previous guesses

  while($scoreRow = mysql_fetch_array($scoreResult) )                   
 {

   $totalScore = $scoreRow["totalScore"];
		   

  }
 echo( "Total Score: ");
 echo( $totalScore);
 echo "<br />";
 echo ("Number Of Guesses: ");
 echo $numGuessesForPrinting;
   if($numGuessesForPrinting == 3)//last guess is to guess the word
 {
                     		echo "<script type = 'text/javascript'>
				window.onload=function(){alert( 'Guess the word.');
					}
				</script>"; 
$last=true;  
 }
 if($numGuessesForPrinting == 4)//they lose
 {
                     		echo "<script type = 'text/javascript'>
				alert( 'You Lose. Good day to you.');
				location.replace('http://www.cse.msu.edu/~speetern/cgi-bin/gameMenu.php');	
				</script>"; //need to redirect to game menu

	 	$sql="UPDATE Game  SET outcome = 'lose' where idGame = '$activeGame'"; //update game outcome 
		if (!mysql_query($sql,$con))
		{
	  	  die('Error: ' . mysql_error()); 
	   	 echo "PROBLEM";
		}
 }
 }








//****************************************************************8


?>

<head>
<script type="text/javascript">
function showHint(str)
{
if (str.length==0)
  {
  document.getElementById("txtHint").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","gethint.php?q="+str,true);
xmlhttp.send();
}
setInterval(function(){gethint()},1000);
</script>

</head>
<body>

<p><b>Start typing a name in the input field below:</b></p>
<form>
First name: <input type="text" onkeyup="showHint(this.value)" size="20" />
</form>
<p>Suggestions: <span id="txtHint"></span></p>

</body>





<!---
<body>


<form name="form1" method="post" action="multigame.php">
<!--first time on page add a game to database with ID,points=0
check for current game, if not create new game, if there is a current game have database drive the game

<hr>
<strong>Guess a Letter or Word </strong>
<input name="guess" type="text" id="guessid" />

<input type="submit" name="Submit1" value="Guess!" /></form>

<div>
<A HREF="http://www.cse.msu.edu/~speetern/cgi-bin/gameMenu.php"> Exit 
</A>

</body>
-->
<html>

