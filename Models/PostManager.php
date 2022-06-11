<?php

namespace Models;


require_once('Models/Model.php');

class PostManager extends Model
{
	

	/**
	 * Get total of Posts for Pagination
	 * @param   $isPublished = post is or not published
	 */

	public function gettotalPosts( $isPublished)
    {
			
		try{	
			
			$req = $this->db->prepare('SELECT count(id) AS id FROM posts WHERE is_published = :Published ');
			$req->bindParam(':Published', $isPublished, \PDO::PARAM_INT);
			$req->execute();
			$posts = $req->fetchColumn();
			
			return $posts;
						
			}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
			}					
					
	}									
	
	/**
	 * Get Posts list
	 * @param   $postUserId,  $isPublished = post is or not published
	 */

    public function getAll($postUserId = null, $from, $isPublished = null, $paginationStart, $limit)
    {        
		try{
			
			$sql =('SELECT id, title, author, lede, image, content, is_published, DATE_FORMAT(update_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS update_date_fr 
					FROM posts '
					);

			if ( isset($postUserId) && ($postUserId > 0) && ( ! isset  ($from))) {

				$sql.= ' WHERE user_id = ? ORDER BY creation_date DESC ';
				$req = $this->db->prepare($sql);
				$req->execute(array($postUserId));
				
				$result = $req->fetchAll();
								
			}elseif( isset($isPublished) && ($isPublished != null)){

				$sql.= ' WHERE is_published = ? ORDER BY update_date DESC LIMIT ?, ? ';
				$req = $this->db->prepare($sql);

				$req->bindParam(1, $isPublished, \PDO::PARAM_INT);
				$req->bindParam(2, $paginationStart, \PDO::PARAM_INT);
				$req->bindParam(3, $limit, \PDO::PARAM_INT);

				$result = $req->execute();
				
				$result = $req->fetchAll();
				
			}
			else{
				$sql.= ' ORDER BY update_date DESC ';
				$result = $this->db->query($sql);
				
			}
		}
		catch (Exception $e)
		{			
			$errorMessage = $e->getMessage();
    		\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}					
		
		return $result;			
	}									

	/**
	 * Get one Post
	 * @params  string $postid and $isPublished = post is or not published
	 */

  	public function get($postId, $isPublished)
    {		 
		if($isPublished == '1'){			

			$req = $this->db->prepare('SELECT posts.id, title,lede, author, image, content, is_published, photo, DATE_FORMAT(posts.update_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS update_date_fr FROM posts  JOIN user ON posts.user_id = user.id WHERE posts.id = ? AND posts.is_published = ?');
			$req->execute(array($postId, $isPublished));
		}else{
			$req = $this->db->prepare('SELECT id, title,lede, author, image, content, is_published, DATE_FORMAT(creation_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ? ');
			$req->execute(array($postId));
		}
        
        $post = $req->fetch();
		
        return $post;
    }

	/**
	 * Add Post
	 * @params  post form data
	 */

	public function addPost( $postUserId,$postTitle, $postLede,$post_author,$postContent, $photoName,$isPublished) 
		{      
			$req = $this->db->prepare('INSERT INTO posts(user_id, title, lede, author, content, image, is_published, creation_date, update_date) VALUES( ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
			$resultat = $req->execute(array($postUserId,$postTitle,$postLede,$post_author,$postContent, $photoName,$isPublished));
							
			return $resultat;

        }

	/**
	 * Update Post
	 * @params  idPost, form data, uploaded $photo
	 */

	public function updatePost($idPost, $data, $postImage = '') {

		try{

			$sql = ('UPDATE posts SET title = :title, 
									  lede = :lede, 
									  author = :author, 
									  content = :content, 
									  update_date= NOW() '
					);

			if($postImage != '')	{
				$sql.= ', image = :postImage ';
			}
			$sql.= ' WHERE id = :id';			

			$req = $this->db->prepare($sql);
			
			if($postImage == '')	{
				
				$resultat = $req->execute(array(
					":id" => $idPost,
					":title" => $data["title"],
					":lede" => $data["lede"],
					":author" => $data["author"],
					":content" => $data["content"]
				));
			}else{
				$resultat = $req->execute(array(
					":id" => $idPost,
					":title" => $data["title"],
					":lede" => $data["lede"],
					":author" => $data["author"],
					":content" => $data["content"],
					":postImage"=>$postImage
				));
			}
		}
		catch (Exception $e){
			
			$errorMessage = $e->getMessage();
			\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
			
		}	
		
		return $resultat;
	}


	/**
	 * ActivatePost
	 * @param  string $id and $isPublished = post is or not published
	 */

	public function publishPost(int $id, string $isPublished)
	{
		
	 try{

		$req = $this->db->prepare('UPDATE posts SET is_published = ?  WHERE id = ?');
		$req->bindValue(1, $isPublished, \PDO::PARAM_STR);
		$req->bindValue(2, $id, \PDO::PARAM_INT);
		$resultat = $req->execute();
		
	}
	catch (Exception $e)
	{
		
		$errorMessage = $e->getMessage();
    	\Http::redirect("view/errorView.php?errorMessage = $errorMessage");
		
	}	
		return $resultat;
	}

	/**
	 * DELETE Post
	 * @param  string $userid and $postId
	 */

	public function deletePost($postId = 0) {
		
		$query = 'DELETE FROM posts';
		if ($postId != null) {
			$query.= ' WHERE posts.id = ? ';
			$deletePosts = $this->db->prepare($query);
			$resultat = $deletePosts->execute(array($postId));
			
		}elseif ($userid != null) {
			$query.= '  WHERE posts.user_id = ? ';
			$deletePosts = $this->db->prepare($query);
			$resultat = $deletePosts->execute(array($userid));
		}
		
		return $resultat;
	}

}
