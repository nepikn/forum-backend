<?php
function printInput($username)
{
?>
  <section>
    <figure>
      <figcaption><?= $username ?></figcaption>
    </figure>
    <form action="./handler/comment_add.php" method="post">
      <textarea required name="content" id="" cols="30" rows="10"></textarea>
      <button>comment</button>
    </form>
    </div>
  <?php
}
