<?php
    //login2.php
    require_once 'login2.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die(error("Oops! Connection Error! Please try again!"));
    
    //HTML form
    echo <<<_END
    <form action="assignment5.php" method="post" enctype='multipart/form-data'>
    <pre style="text-align:center">
    <label style="font-size: 15px">ADD SECTION</label>
        Advisor Name: <input style="margin-bottom: 10px" type="text" name='advisorname'>
        Student Name: <input style="margin-bottom: 10px" type="text" name="studentname">
          Student ID: <input style="margin-bottom: 10px" type="text" name="id">
          Class Code: <input style="margin-bottom: 10px" type="text" name="class">
        <input style="margin: 2px" type="submit" value="ADD">
    </pre></form>

    <form action="assignment5.php" method="post">
        <input type="text" placeholder="Advisor's name here" name="search">
        <input style="margin: 2px" type="submit" name="submit-search" value="SEARCH">
    </form>
_END;

    //ADD SECTION
    if (isset($_POST['advisorname']) && isset($_POST['studentname']) &&
        isset($_POST['id']) && isset($_POST['class']))
    {   
        $adname = get_post($conn, 'advisorname');           //sanitize input
        $stname = get_post($conn, 'studentname');
        $stid = get_post($conn, 'id');
        $class = get_post($conn, 'class');
        
        $query = "INSERT INTO assignment5 VALUES('$adname', '$stname','$stid','$class')";
        $result = $conn->query($query);
        if (!$result) echo (error("Oops! We cannot upload your data. Please try again!"));
    }

    //SEARCH SECTION
    if (isset($_POST['submit-search']))
    {
        $adname = get_post($conn, 'search');           //sanitize input
        $query = "SELECT * FROM assignment5 WHERE Advisor_name = '$adname'";
        $result = $conn->query($query);
        if($result) print_search($result);
        else echo error("There are no results for your search!");
    }

    $query = "SELECT * FROM assignment5";
    $result = $conn->query($query);
    if (!$result) die (error("Oops! Connection Error! Please try again!"));

    $rows = $result->num_rows;

    for ($j = 0 ; $j < $rows ; ++$j)
    {       
        $result->data_seek($j);
        $row = $result->fetch_array(MYSQLI_NUM);
    echo <<<_END
    <pre>
    Advisor Name: $row[0]
    Student Name: $row[1]
      Student ID: $row[2]
      Class Code: $row[3]
    </pre>
_END;
    }
    
    $result->close();
    $conn->close();

    //Sanitize user input
    function get_post($conn, $var)
    {
        if (get_magic_quotes_gpc()) 
            $_POST[$var] = stripslashes($_POST[$var]);
        $res = $conn->real_escape_string($_POST[$var]);
        return htmlentities($res);
    }

    //Error function
    function error($msg)
    {
        echo "<br><br>";
        echo '<img style="width: 50%" src="http://www.lazerhorse.org/wp-content/uploads/2014/06/Pomeranian-Puppy-cute-sad-face.jpg" />';
        echo "<br><br>". $msg. "<br>";
    }

    //Print search result
    function print_search($var)
    {
        echo "Results:";
        while ($row = mysqli_fetch_array($var))
        {
            echo"<br>---------<br>".
                "Student Name: ".$row['Student_name']."<br>".
                "Student ID: ".$row['Student_ID']."<br>".
                "Class Code: ".$row['Class_code'];
        }
    }

?>