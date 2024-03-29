<!-- PHP -->
<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "pdf_documents";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

$searchQuery = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$whereClause = '';
if ($searchQuery !== '') {
    $whereClause = "WHERE title LIKE '%$searchQuery%'";
}

$resultsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $resultsPerPage;

$sqlTotal = "SELECT COUNT(*) AS total FROM documents $whereClause";
$resultTotal = $conn->query($sqlTotal);
$totalRows = $resultTotal->fetch_assoc()['total'];

$sql = "SELECT * FROM documents $whereClause LIMIT $offset, $resultsPerPage";
$result = $conn->query($sql);
$files = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $files[] = $row;
    }
}
$conn->close();
?>
<!-- PHP -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- เรียกใช้ Bootstrap 5 -->
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <!-- เรียกใช้ CSS -->
    <link rel="stylesheet" href="style_2.css">
    <!-- เพิ่ม icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <!-- ล็อคตำเเหน่ง side bar -->

</head>
<body>
<!-- header -->
<header class="p-3 bd-indigo-700">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <img src="Logo.png" width=40px height=40px alt="">

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="index.php" class="nav-link fw-bold px-2 text-warning fs-5" >Home</a></li>
          <li><a href="https://www.facebook.com/profile.php?id=100006315335157" class="nav-link px-2 text-white fs-5">About</a></li>
        </ul>

        <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="search.php" method="GET">
          <input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..." aria-label="Search">
        </form>

        <div class="text-end mx-2">
          <a class="btn bd-yellow-400" href="search.php" role="button">Search</a>
        </div> -->
        
        <form method="post" action="search.php">
            <div class="input-group">
                <input type="text" name="searchTerm" class="form-control" placeholder="ค้นหาเอกสาร">
                <button type="submit" class="btn bd-yellow-400">ค้นหา</button>
            </div>
        </form>

        <div class="text-end ms-3">
          <a class="btn bd-red-500" href="login.php" role="button">Log Out</a>
        </div>
      </div>
    </div>
</header>
  <!-- header end -->
<!-- side bar -->
<div class="container-fruid">
  <div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collpase">
      <div class="position-sticky">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a href="#" class="nav-link active fw-bold" aria-current="page">
              <i data-feather="user"></i>
              <span class="ml-2">บัญชีผู้ใช้</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="view_pdf.php" class="nav-link text-dark fw-bold" aria-current="page">
              <i data-feather="search"></i>
              <span class="ml-2">ค้นหาเอกสาร</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="https://www.facebook.com/profile.php?id=100006315335157" class="nav-link text-dark fw-bold" aria-current="page">
              <i data-feather="facebook"></i>
              <span class="ml-2">ติดต่อเรา</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="insert.php" class="nav-link text-dark fw-bold" aria-current="page">
              <i data-feather="file-plus"></i>
              <span class="ml-2">นำเข้าเอกสาร</span>
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- main -->
    <main class="col-md-9 ml-sm-auto col-lg-10 px-md-2 py-2">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb fw-bold">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Overview</li>
          </ol>
      </nav>
      <!-- main2 -->
      <div class="row">
                    <div class="container col-12 mb-4 mb-lg-0">
                        <div class="card">
                            <h5 class="card-header">รายการเอกสารล่าสุด</h5>
                            <div class="card-body">
                                <div class="table-responsive">
                                <table class="table">
        <thead>
            <tr>
                <th>เรื่อง</th>
                <th>สำเนาถึง</th>
                <th>วันที่สำเนา</th>
                <th>ดูเอกสาร</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($files as $file) {
                echo "<tr>";
                echo "<td>" . $file['title'] . "</td>";
                echo "<td>" . $file['to_copy'] . "</td>";
                echo "<td>" . $file['copy_date'] . "</td>";
                echo "<td><a href='" . $file['file_path'] . "' target='_blank'>View PDF</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="pagination justify-content-center">
        <?php
        $totalPages = ceil($totalRows / $resultsPerPage);
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='index.php?page=$i&q=$searchQuery'>$i</a> ";
        }
        ?>
    </div>

                                </div>
                                <!-- <a href="#" class="btn btn-block btn-light">View all</a> -->
                            </div>
                        </div>
                    </div>

      </div>

      <!-- doc list -->

    </main>
  </div>
</div>

<!-- footer -->
<div class="container">
  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3 ">
      <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="#" class="nav-link px-2 text-body-secondary">About</a></li>
    </ul>
    <p class="text-center text-body-secondary ">© 2023 </p>
  </footer>
</div>
<!-- footer -->

<script>
  feather.replace()
</script>

<script src="js/bootstrap.min.js"></script>

</body>
</html>