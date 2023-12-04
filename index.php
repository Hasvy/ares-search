<?php
require "layout.php";
require "menu.php"; ?>
<script src="check.js"></script> <!-- aby zjistit jestli pole pro IČO je prázdné -->

<nav class="navbar navbar-light bg-light">
  <div class="container-fluid">
    <form class="d-flex mx-auto" name="form" method="POST" action="action.php" onsubmit="return required()">
      <input class="form-control me-2" name="ICO" maxlength="8" type="search" placeholder="Enter ICO" aria-label="Search">
      <button class="btn btn-outline-danger" type="submit">Search</button>
    </form>
  </div>
</nav>