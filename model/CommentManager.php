<?php


namespace OC\PhpSymfony\Blog\Model;

require_once("model/Manager.php");

class CommentManager extends Manager
{
    
	/*# **************
        # GET ALL POST COMMENTS
        # **************

	 public function getComments($postId)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE post_id = ? AND is_enabled = ? ORDER BY comment_date DESC');
        $comments->execute(array($postId, '1'));

        return $comments;
    }*/

	# **************
        # GET ALL POST COMMENTS with params working for front and back end
        # **************
	
	public function getComments($postId = null, $isenabled)
    {
        $db = $this->dbConnect();
		$query = ('
													SELECT id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS comment_date_fr 
													FROM comments 
													WHERE comments.is_enabled = ?'
													);
													if ($postId != null) {
														$query.= ' AND comments.post_id = ?  ORDER BY comment_date DESC';
														$comments = $db->prepare($query);
														$comments->execute(array($isenabled,$postId));
														
													}else{
														$query.= '  ORDER BY comment_date ASC';
														$comments = $db->prepare($query);
														$comments->execute(array($isenabled));
													}
													
													
        /*$comments = $db->prepare($query)
        $comments->execute(array($postId, $isenabled));*/

        return $comments;
    }

    public function postComment($postId, $author, $comment)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO comments(post_id, author, comment, is_enabled, comment_date) VALUES(?, ?, ?, ?, NOW())');
        $affectedLines = $comments->execute(array($postId, $author, $comment, 0));

        return $affectedLines;
    }

	public function getComment($commentId)
    { 
		//echo "TEST ENTRE3";

        $db = $this->dbConnect();
        $comment = $db->prepare('SELECT id, post_id,  author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE id = ? ');
        $comment->execute(array($commentId));

        return $comment;
    }

	public function updateComment($comment, $commentId)
    { 
        $db = $this->dbConnect();
        $commentupdate = $db->prepare('UPDATE comments SET comment = ? WHERE id = ? ');
        $commentupdate->execute(array($comment, $commentId));

        return $commentupdate;
    }

	public function validateComment($commentId)
    { 
        $db = $this->dbConnect();
        $commentupdate = $db->prepare('UPDATE comments SET is_enabled = ? WHERE id = ? ');
        $commentupdate->execute(array('1', $commentId));

        return $commentvalidate;
    }

	public function deleteCommentPost($idpost) {

            $db = $this->dbConnect();
			$sql = 'DELETE FROM comments WHERE post_id = :idpost LIMIT 1;';
			$req = $db->prepare($sql);
            //$obj = $this->db->prepare($sql);
    
            $resultat = $req->execute(array(
                ':idpost' => $idpost
            ));
    
            return $resultat;
        }
}
