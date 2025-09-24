<!DOCTYPE html>
<html>
<head>
  <title>Term Project</title>
  <style>
    input {
        width: 700px;
        height: 100px;
        margin-bottom: 10px;
    }
    .project-label {
      font-size: 34px;
      font-weight: bold;
      margin-top: 0;
      text-decoration: underline;
    }
    .query-label {
      font-size: 24px;
      font-weight: bold;
      margin-top: 0;
    }
    </style>
</head>
<body>
    <h1 class="project-label">Mohab Yousef Term Project</h1>
    <h1 class="query-label">Query Tables</h1>
    <input type="text" id='text-input' placeholder="Enter text here">
  <div>
    <button id="submit-button">Submit</button>
    <button type="button" id="clear-button">Clear</button>
  </div>
  <?php
  if(!empty($_POST)){
    $servername = "sysmysql8.auburn.edu";
    $username = "mey0012";
    $password = "@mlpzaq123$%";
    $schema = "mey0012db";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $schema);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $sql = stripslashes($_POST["text"]);
    if (preg_match("/^(drop|DROP)/i", $sql)) {
      echo "DROP Statement Not Allowed.";
    } else if (preg_match("/^(select|SELECT)/i", $sql)) {

      $result = $conn ->query($sql);
      // Get the column names from the result set
      $column_names = $result->fetch_fields();
      $rowCount = 0;
      // Start the HTML table
      $echo_output = "<table>";
      $echo_output .=  "<tr>";
      foreach ($column_names as $column_name) {
        $echo_output .=  "<th>" . $column_name->name . "</th>";
      }
      $echo_output .=  "</tr>";

      // Iterate over the result set and add each row to the table
      while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $echo_output .= "<tr>";
        foreach ($column_names as $column_name) {
          $echo_output .=  "<td>" . $row[$column_name->name] . "</td>";
        }
        $echo_output .=  "</tr>";
        $rowCount++;
      }
      $echo_output .=  "</table>";
      echo $echo_output;
      echo "<br>";
      echo $rowCount . " Rows Retrieved";
    } else if (preg_match("/^(create|CREATE|update|UPDATE)/i", $sql)) {
      $result = $conn ->query($sql);
      echo "Table Created/Updated";
    } else if (preg_match("/^(delete|DELETE)/i", $sql)) {
      $result = $conn ->query($sql);
      echo "Row(s) Deleted";
    } else if (preg_match("/^(insert|INSERT)/i", $sql)) {
      $result = $conn ->query($sql);
      echo "Row Inserted";
    } else {
      echo "Incorrect or Disallowed Statemennt Entered.";
    }   
}
?>
  <script src="https://code.jquery.com/jquery-1.10.1.min.js" ></script>
  <script>
    $(document).ready(function(){
      function submitMe(selector)
      {
        $.ajax({
        type: "POST",
        url: "",
        data: {text:$(selector).val()},
        success: function(newHtml) {
          $("body").html(newHtml);
        }
        });
      }
   $('#submit-button').click(function(){
    submitMe('#text-input');
   });
   $('#clear-button').click(function(){
    document.getElementById('text-input').value = "";
   });
});  
  </script>
</body>
</html>