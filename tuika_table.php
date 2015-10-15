CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

SELECT rental_logs.book_id,users.name FROM requests inner join rental_logs on (requests.book_id = rental_logs.book_id) inner join users on (requests.user_id = users.id);


SELECT distinct * FROM requests inner join rental_logs on (requests.book_id = rental_logs.book_id) inner join users on (requests.user_id = users.id) ;

<h1>User Request</h1>
    <div>
    <?php while($log = mysqli_fetch_array($logs)):?>
    <div>
        <p>
        Borrower id:<?php echo h($log['borrower_id']) ;?>
        </p>
        <p>
        Book id:<?php echo h($log['book_id']);?>
        </p>
        <p>
        Request date:<?php echo h($logs['requests']['modified']);?></p>
        <p><a href="view2.php?id=<?php echo h($post['id']);?>">[記事詳細]</a><br><a href="update.php?id=<?php echo h($post['id']);?>">[編集]</a><br><a href="delete.php?id=<?php echo h($post['id']);?>">[削除]</a><br>
        </p>
        <hr style="border:dotted;color:#24c1f5;">
    </div>
    <?php endwhile ;?>
    </div>