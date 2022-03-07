<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  rel=" stylesheet" type="text/css"href=" practicals.css" title=" myStyle">
    <title>labs</title>
    <SCRIPT language=JavaScript>
        // function to reload the form//
        function reload(form){
        var mod=form.Module.options[form.Module.options.selectedIndex].value;
        document.getElementById('module').value = mod;
        console.log('f2 module:'+ document.getElementById('module').value);
        self.location='practicals.php?Module=' + mod ;
        }
        // function to set Time and location according to the selected Module//
        function setTime(form){
            var Time=form.time.options[form.time.options.selectedIndex].value;
            console.log('time:'+Time);
            document.getElementById('time').value=Time;
            console.log('f2 time:'+ document.getElementById('time').value);
        }
    </script>
</head>
<body>
<?php
    // fuction to test the input data//
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // To connect to the departmental database//
        $db_hostname = "studdb.csc.liv.ac.uk";
        $db_database = "sgspendy";
        $db_username = "sgspendy";
        $db_password = "saijet_7";
        $db_charset = "utf8mb4";
        $dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false 
        );
        try{  // connecting to database using PDO
            $pdo = new PDO($dsn,$db_username,$db_password,$opt);
            //SQL query To Select the distinct Module from the table //
            $query1 = "SELECT DISTINCT Module FROM session";
            echo '<h1> Remote practical sessions </h1>';           
            echo '<form action = "assignment3.php" method = "POST"> ';
            echo '<label> Module: </label>';
            echo "<select onchange= \"reload(this.form)\" name = 'Module'>";
            echo '<option value= ""> select a module</option>';
            if($stmt = $pdo->query($query1)){ 
                $mod = $_GET['Module'];
                global $mod;
                while($row = $stmt->fetch()) {
                        if(isset($mod)){
                            if($row['Module']==@$mod){
                                echo '<option selected value="' .$row["Module"].'">'.$row["Module"].'</option>';
                            }
                            else{
                               echo '<option value="'. $row["Module"].'">'.$row["Module"].'</option>';
                            }
                        }
                         else{
                            echo '<option value="'. $row["Module"].'">'. $row["Module"] .'</option>';
                        }     
               }
                echo '</select> ';
                echo '<label> Time and Location: </label>';
                echo "<select onchange= \"setTime(this.form)\" name = 'time'>";
                echo '<option value= ""> Select TimeandLocation</option>';
            }
            if(isset($mod)){
                // SQL query to select the time and location of the particular Module//
                $query2 = "SELECT DISTINCT `Timeandlocation` FROM session WHERE Module='". @$mod . "'";
                if($stmt = $pdo->query($query2)){
                    while($row2 = $stmt->fetch()) {
                    echo '<option value= "'.$row2["Timeandlocation"].'"> '. $row2["Timeandlocation"] .'</option>';
                    }
                }
            }
             echo '</select> </form>';
             // Name and Eamil validation//
             $nameErr = $emailErr = "";
             $name = $email = "";
             if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (empty($_POST["name"])) {
                  $nameErr = "Name is required";
                } else {
                  $name = test_input($_POST["name"]);
                  // check if name only contains letters and whitespace
                  if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
                    $nameErr = "Only letters and white space allowed";
                  }
                }
                if (empty($_POST["email"])) {
                  $emailErr = "Email is required";
                } else {
                  $email = test_input($_POST["email"]);
                  // check if e-mail address is well-formed
                  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                  }
                }
            }    
            echo '<p><span class="error">* required field</span></p>';
            echo '<form name= "f2" method="post" action="'. htmlspecialchars($_SERVER["PHP_SELF"]).'">';  
            echo 'Name: <input type="text" name="name" value="'. $name.'"/>';
            echo '<span class="error">*' .$nameErr.'</span>';
            echo '<br><br>';
            echo 'E-mail: <input type="text" name="email" value="'. $email.'"/>';
            echo '<span class="error">*' .$emailErr.'</span>';
            echo '<br><br>';
            echo '<input type="submit" name="submit" value="Submit"/> ';
            echo '<input type="hidden" id="module" name="module" value="' . $mod . '"/> ';
            echo '<input type="hidden" id="time" name="time"/> ';
            echo '</form>';
            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $email = $_POST['email'];
                $Module = $_POST['module'];
                $time = $_POST['time'];
                $query3 = "SELECT capacity from session where  Module='". @$Module . "' and  Timeandlocation='". @$time ."'";
                if($stmt = $pdo->query($query3)){             
                    while( $row3 = $stmt->fetch()){
                        $cap = $row3['capacity'];
                        if($cap > 0){
                            $capacity = $cap - 1;
                            $sql = $pdo->prepare("Insert INTO `booking info` (Name,Email,Module,Timeandlocation,capacity) values (:name,:email,:Module,:time,:capacity)");
                            $sql->execute(array('name'=>$name,'email'=>$email,'Module'=>$Module,'time'=>$time,'capacity'=>$capacity));
                            $query4 = "UPDATE session SET capacity ='" . $capacity ."' where  Module='". @$Module . "' and  Timeandlocation='". @$time ."'";
                            if($stmt = $pdo->query($query4)){                     
                                while( $row4 = $stmt->fetch()){}
                                echo ' Booking Successful! '. '<b>Module</b>: '. $Module . ' <b> Time </b>: ' . $time . ' <b> Name</b>: '. $name. '  <b>E-mail</b>:'. $email;
                            }
                        }
                        else{
                            echo 'Booking unsuccessful! '. '<b>Module</b>: '. $Module . ' <b> Time</b>: ' . $time . ' <b> Name</b>: '. $name.  ' <b> E-mail</b>: ' . $email ;
                        }
                    }
                } 
            }
        }     
        catch (PDOException $e) {
            exit("PDO Error: ".$e->getMessage()."<br>");
        }
?>
</body>
</html>
