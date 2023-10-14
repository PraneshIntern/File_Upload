<?php
$conn = new PDO('mysql:host=localhost; dbname=demo', 'root', '') or die(mysqli_connect_errno());

if (isset($_POST['submit']) != "") {
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $type = $_FILES['file']['type'];
    $temp = $_FILES['file']['tmp_name'];
    $fname = date("YmdHis") . '_' . $name;
    $state = $_POST['state'];
    $city = $_POST['city'];
    $branch = $_POST['branch'];
    $department = $_POST['department'];

    try {
        $chk = $conn->query("SELECT * FROM uploaded_file where name = '$name' ")->rowCount();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    if ($chk) {
        $i = 1;
        $c = 0;
        while ($c == 0) {
            $i++;
            $reversedParts = explode('.', strrev($name), 2);
            $tname = (strrev($reversedParts[1])) . "_" . ($i) . '.' . (strrev($reversedParts[0]));
            $chk2 = $conn->query("SELECT * FROM  uploaded_file where name = '$tname' ")->rowCount();
            if ($chk2 == 0) {
                $c = 1;
                $name = $tname;
            }
        }
    }

    $move =  move_uploaded_file($temp, "upload/" . $fname);
    if ($move) {
        $query = $conn->query("insert into uploaded_file(name, fname, state, city, branch, department) values('$name', '$fname', '$state', '$city', '$branch', '$department')");
        if ($query) {
            header("location:index.php");
        } else {
            die(mysqli_connect_errno());
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Upload and Download Files</title>
  <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
  <link href="style.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center text-white">Upload and Download Files</h1>
        <form enctype="multipart/form-data" action="" name="form" method="post">
       
 


        <div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="text-white">Select State</label>
            <select name="state" id="state" class="form-control">
                <option value="Tamilnadu">Tamilnadu</option>
                <option value="Kerela">Kerela</option>
                <option value="Telangana">Telangana</option>
                <option value="Karnataka">Karnataka</option>
            </select>
            <p id="selectedState" class="text-white"></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="text-white">Select City</label>
            <select name="city" id="city" class="form-control">
                <option value="Chennai">Chennai</option>
                <option value="Coimbatore">Coimbatore</option>
                <option value="Hyderabad">Hyderabad</option>
                <option value="Kochi">Kochi</option>
            </select>
            <p id="selectedCity" class="text-white"></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="text-white">Select Branch</label>
            <select name="branch" id="branch" class="form-control">
                <option value="Perungudi">Perungudi</option>
                <option value="Arumbakkam">Arumbakkam</option>
                <option value="Neelankarai">Neelankarai</option>
                <option value="Pallavaram">Pallavaram</option>
                <option value="Maduravoyal">Maduravoyal</option>
            </select>
            <p id="selectedBranch" class="text-white"></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label class="text-white">Select Department</label>
            <select name="department" id="department" class="form-control">
                <option value="IT">IT</option>
                <option value="Accounts">Accounts</option>
                <option value="HR">HR</option>
                <option value="Homecare">Homecare</option>
                <option value="Assisted Living">Assisted Living</option>
                <option value="Admins">Admins</option>
                <option value="clinical">Clinical</option>
                <option value="training">Training</option>
                <option value="security">Security</option>
                <option value="others">Others</option>
            </select>
            <p id="selectedDepartment" class="text-white"></p>
        </div>
    </div>
</div>

    <div class="form-group text-center" style="padding-top: 50px;">
  <label class="text-white"><h3>Select File</h3></label> <br>
  <input type="file" name="file" id="file" class="form-control-file text-white" style="width: 450px; padding-bottom: 80px; margin-left: 300px;">

</div>

      
<div class="text-center"> 
    <button type="submit" name="submit" id="submit" class="btn btn-primary" style="width: 300px;">Submit</button>
</div>
        </form>


        
        <div class="container">
  <div class="row">
    <table class="table table-white table-striped  mt-4" id="example">
      <thead>
        <tr>
          <th width="90%" align="center">Files</th>
          
          <th align="center">state</th>
          <th align="center">city</th>
          <th align="center">branch</th>
          <th align="center">department</th>
          <th align="Center">Date</th>
          <th align="center">Action</th>
          <th align="center">View</th>
          <th align="center">Print</th>


        </tr>
      </thead>
      <?php
      $query = $conn->query("select * from uploaded_file order by id desc");
      while ($row = $query->fetch()) {
        $name = $row['name'];
        $state = $row['state'];
        $city = $row['city'];
        $branch = $row['branch'];
        $department = $row['department'];
        $date = $row['upload_at'];
        
      ?>
        <tr>
        <td>&nbsp;<?php echo $name; ?></td>
          <td>&nbsp;<?php echo $state; ?></td>
          <td>&nbsp;<?php echo $city; ?></td>
          <td>&nbsp;<?php echo $branch; ?></td>
          <td>&nbsp;<?php echo $department; ?></td>
          <td>&nbsp;<?php echo $date; ?></td>

          <td>
            <button class="btn btn-success">
              <a href="download.php?filename=<?php echo $name; ?>&f=<?php echo $row['fname'] ?>">Download</a>
            </button>
          </td>
          
          
          <td>
    <button class="btn btn-success view-btn" data-fname="<?php echo $row['fname']; ?>">View</button>
</td>


<td>
    <button class="btn btn-success print-btn" data-fname="<?php echo $row['fname']; ?>">Print</button>
</td>



        </tr>
      <?php } ?>
    </table>
  </div>
</div>

  </div>
  <script src="js/jquery.js" type="text/javascript"></script>
  <script src="js/bootstrap.js" type="text/javascript"></script>
  <script>
    $('.print-btn').on('click', function() {
            var fname = $(this).data('fname');
            var fileURL = 'upload/' + fname;

            // Open the file in a new tab
            var newWindow = window.open(fileURL, '_blank');
            
            // Trigger the print dialog
            if (newWindow) {
                newWindow.onload = function() {
                    newWindow.print();
                }
            }
        });
    const cityDropdown = document.getElementById("city");
    const branchDropdown = document.getElementById("branch");
    const stateDropdown = document.getElementById("state");

    stateDropdown.addEventListener("change", function () {
        const selectedState = stateDropdown.value;
        cityDropdown.innerHTML = "";
        branchDropdown.innerHTML = "";

        const cities = {
            Tamilnadu: ["Chennai", "Coimbatore"],
            Kerela: ["Kochi"],
            Telangana: ["Hyderabad"],
            Karnataka: ["Bangalore"]
        };




        const branches = {
            Chennai: ["Perungudi", "Arumbakkam", "Neelankarai", "Pallavaram", "Maduravoyal"],
            Coimbatore: ["Coimbatore"],
            Hyderabad: ["Hyderabad"],
            Kochi: ["Kochi"],
            Bangalore: ["Kasavanahalli"]
        };

        cities[selectedState].forEach(city => {
            const option = document.createElement("option");
            option.value = city;
            option.text = city;
            cityDropdown.appendChild(option);
        });

        console.log(selectedState);
        branches[cityDropdown.value].forEach(branch => {
            const option = document.createElement("option");
            option.value = branch;
            option.text = branch;
            branchDropdown.appendChild(option);
        });
    });

    cityDropdown.addEventListener("change", function () {
        branchDropdown.innerHTML = "";

        const branches = {
            Chennai: ["Perungudi", "Arumbakkam", "Neelankarai", "Pallavaram", "Maduravoyal"],
            Coimbatore: ["Coimbatore"],
            Hyderabad: ["Hyderabad"],
            Kochi: ["Kochi"],
            Bangalore: ["Kasavanahalli"]
        };

        branches[cityDropdown.value].forEach(branch => {
            const option = document.createElement("option");
            option.value = branch;
            option.text = branch;
            branchDropdown.appendChild(option);
        });
    });

 
    const viewButtons = document.querySelectorAll('.view-btn');

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const fname = this.getAttribute('data-fname');
            const fileURL = 'upload/' + fname;

            window.open(fileURL, '_blank');
        });
    });


</script>


  <script type="text/javascript" charset="utf-8" language="javascript" src="js/jquery.dataTables.js"></script>
  <script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>
</body>
</html>
