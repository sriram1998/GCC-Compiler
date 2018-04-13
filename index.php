<?php
mysql_connect("localhost", "root","") or die(mysql_error()); 
mysql_select_db("compiler") or die("Cannot connect to database"); 
$current="";
$answer="";
$query = mysql_query("SELECT * from codes"); 
$exists = mysql_num_rows($query); 


if(!empty($_POST))
{$input = $_POST['inp'];
 $current=$_POST['ccode'];
 mysql_query("INSERT INTO codes (code,input) VALUES ('$current','$input')");
    if($exists<4)
   { 
   echo "run....";
    
    $file="input.cpp";
    file_put_contents($file, $current);
    putenv("PATH=C:\TDM-GCC-64\bin");
    shell_exec("g++ input.cpp -o input.exe");

    $descriptorspec = array(
            0 => array("pipe", "r"), 
            1 => array("pipe", "w"),  
            2 => array("file", "error.log", "a") 
        );

        $process = proc_open('C:/xampp/htdocs/compiler/input.exe', $descriptorspec, $pipes);
        

        if (is_resource($process)) {

        
        
        

      
        fwrite($pipes[0], $input);

        fclose($pipes[0]);

        
        
        while ($s = fgets($pipes[1])) {
         
            $answer=$answer.$s;
            //echo $s;

            flush();
        }
    
         fclose($pipes[1]); 
         mysql_query("DELETE FROM codes WHERE code='$current'");


        }
    }
    else{
        echo "process pending...";
        mysql_query("DELETE FROM codes order by id desc limit 1");
    }
    
}

?>
    <html>
    <head>
    <title>COMPILER</title>
    <style>
    .code1{
    position:absolute;
    margin-top:100px;
    margin-left: -15%;
    height: 400px;
    width:400px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
    }
    .code2
    {
    position:absolute;
    margin-top:100px;
    margin-left: 20%;
    width: 400px;
    height: 400px;
    padding: 12px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
    }
    #cus{
        position:absolute;
        margin-top:2%;
        width:400px;
        height:40px;
        padding: 2px 20px;
    box-sizing: border-box;
    border: 2px solid #ccc;
    border-radius: 4px;
    background-color: #f8f8f8;
    font-size: 16px;
    resize: none;
    
    }
    </style>
    <body>
<form method="POST" enctype="multipart/form-data">
<input type="file" id="files" name="files[]" multiple />
<textarea placeholder="your code here:" name="ccode" class="code1" id="in"><?php echo $current;?></textarea>
<textarea placeholder="input values(separated by space)" name="inp" id="cus"></textarea> 
<input type="submit" value="run code">
<textarea placeholder="output here:" name="output"  class="code2"><?php print $answer;?></textarea>
</form>
</body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script>
  function handleFileSelect(evt) {
    var files = evt.target.files; 
    f=files[0];
    var reader = new FileReader();
    reader.onload = (function(theFile) {
        return function(e) {
        jQuery('#in').val(e.target.result);
        };
      })(f);

      reader.readAsText(f);
    
  }

document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>

</head>
</html>
