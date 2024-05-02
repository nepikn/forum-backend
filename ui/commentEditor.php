<?php
function printEditor($username)
{
  ob_start();
?>
  <div>
    <figure>
      <figcaption>%s</figcaption>
    </figure>
    <form action="./handler/comment_add.php" method="post">
      <textarea required name="content" id="" cols="30" rows="10"></textarea>
      <button>comment</button>
    </form>
  </div>

<?php
  printf(ob_get_clean(), $username);
}
