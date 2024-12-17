<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Quote</title>
  <link rel="stylesheet" href="/CSS/generate.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
</head>

<body>
  <nav>
    <ul>
      <li class="crown"><i class="fa-solid fa-crown"></i>Qfit</li>

      <li><a href="dashboard.php">Home</a></li>

      <li><a href="post_form.php">Post</a></li>
      <li><a href="Generate.php">Generate</a></li>
      <li><a href="profile.php">Profile</a></li>
      <li>
        <a href="setting.html">Setting<i class="fa-solid fa-caret-down"></i></a>
        <ul class="dropdown">
          <li><a href="includes/logout.php">Log Out</a></li>
          <li><a href="service.html">Services</a></li>
        </ul>
      </li>
    </ul>
  </nav>


  <!-- Include PHP code for displaying the quote -->
  <?php include 'includes/quote_roll.php'; ?>
  


  <button class="btn3" onclick="window.location.reload();"><i class="fa-solid fa-rotate-right"></i></button>


</body>

</html>