<?php


namespace OC\PhpSymfony\Blog\Model;

require_once("model/Manager.php");

class CommentManager extends Manager
{
    
	
	# **************
        # GET ALL POST COMMENTS with params working for front and back end
        # @ Params $postId $isenabled $userid**************
	
	public function getComments($postId = null, $isenabled = null, $userid = null)
    {
				
        $db = $this->dbConnect();

		if(isset($userid) && ($userid != null)){

					$query = ('
													SELECT comments.id, comments.post_id, comments.author, comments.comment, user.photo, user.id AS userid, DATE_FORMAT(comments.comment_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS comment_date_fr  
													FROM comments 
													 JOIN user 
													ON comments.user_id = user.id  
													WHERE comments.user_id = ? 
													ORDER BY comments.comment_date DESC'
													);
													
														
														$comments = $db->prepare($query);
														$comments->execute(array($userid));
		}else{
					$query = ('
													SELECT comments.id, comments.post_id, comments.author, comments.comment, user.photo, user.id AS userid, DATE_FORMAT(comments.comment_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS comment_date_fr  
													FROM comments 
													 JOIN user 
													ON comments.user_id = user.id  
													WHERE comments.is_enabled = ?'
													);
													if ($postId != null) {
														$query.= ' AND comments.post_id = ?  ORDER BY comments.comment_date DESC';
														$comments = $db->prepare($query);
														$comments->execute(array($isenabled,$postId));
														
													}else{
														$query.= '  ORDER BY comments.comment_date ASC';
														$comments = $db->prepare($query);
														$comments->execute(array($isenabled));
													}
										
		}											
        
        return $comments;
    }

	/**
	 * Insert a comment **********************
	 * @param   $postId $author, $comment and $userid
	 */

    public function postComment($postId, $author, $comment, $userid)
    {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO comments(post_id, user_id,  author, comment, is_enabled, comment_date) VALUES(?, ?, ?, ?, ?, NOW())');
        $affectedLines = $comments->execute(array($postId, $userid,  $author, $comment, '0'));

        return $affectedLines;
    }
	
	/**
	 * Get single comment for modification**** TO BE DELETED
	 * @param   $commentId**************************
	 */

	public function getComment($commentId)
    { 
	
        $db = $this->dbConnect();
        $comment = $db->prepare('SELECT id, post_id,  author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS comment_date_fr FROM comments WHERE id = ? ');
        $comment->execute(array($commentId));

        return $comment;
    }
	
	/**
	 * uPDATE comment **** TO BE DELETED
	 * @paramS   $commentId, $comment***
	 */

	public function updateComment($comment, $commentId)
    { 
        $db = $this->dbConnect();
        $commentupdate = $db->prepare('UPDATE comments SET comment = ? WHERE id = ? ');
        $commentupdate->execute(array($comment, $commentId));

        return $commentupdate;
    }
	
	/**
	 * Validate commentby admin
	 * @param   $commentId**************************
	 */

	public function validateComment($commentId)
    { 
        $db = $this->dbConnect();
        $commentupdate = $db->prepare('UPDATE comments SET is_enabled = ? WHERE id = ? ');
        $commentupdate->execute(array('1', $commentId));

        return $commentvalidate;
    }

	public function deleteCommentPost($idpost, $idcomment, $userid) {


            $db = $this->dbConnect();
			$query =( 'DELETE FROM comments ');
													if ($idpost != null) {
														$query.= ' WHERE comments.post_id = ? ';
														$comments = $db->prepare($query);
														$resultat = $comments->execute(array($idpost));
														
													}elseif($idcomment != null){
														$query.= '  WHERE comments.id = ? ';
														$comments = $db->prepare($query);
														$resultat = $comments->execute(array($idcomment));
													
													}elseif($userid != null){
														$query.= '  WHERE comments.user_id = ? ';
														$comments = $db->prepare($query);
														$resultat = $comments->execute(array($userid));
													}
		
			
            return $resultat;


													
        
	}

	}
