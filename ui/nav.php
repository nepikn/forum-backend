<?php
function printNav($username)
{
  ob_start();

  if (!$username) : ?>
    <a href="auth.php">sign in or create account</a>

  <?php else : ?>
    <form action="./handler/logout.php" method="post"><button>Log out</button></form>
    <form action="./handler/username_edit.php" method="post">
      <input type="text" name="name">
      <button>Edit Name</button>
    </form>

<?php endif;
  printf('<nav>%s</nav>', ob_get_clean());
}
