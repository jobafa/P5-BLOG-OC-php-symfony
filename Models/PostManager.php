<?php

namespace Models;

//require_once("model/Manager.php");
require_once('Models/Model.php');

class PostManager extends Model
{
		
		private $post_userid ;
		private $post_title;
		private $post_lede;
		private $post_content ;
		private $is_enabled ;

		/*private $db;

		public function __construct()
		{
			$this->db = dbConnect();
		}
		*/

	/**
	 * Get total of Posts for Pagination
	 * @param   $ispublished = post is or not published
	 */

	public function gettotalPosts( $ispublished)
    {
			//$db = $this->dbConnect();
			try
				{	
					$sql = $this->db->query('SELECT count(id) AS id FROM posts WHERE is_published = "'.$ispublished.'"')->fetchAll();
					$totalRecrods = $sql[0]['id'];

					return $totalRecrods;
								
				}
			catch (Exception $e)
				{
					echo 'Connexion �chou�e : ' . $e->getMessage();
				}					
					
	}									
	
	/**
	 * Get Posts list
	 * @param   $post_userid,  $ispublished = post is or not published
	 */

    public function getAll($post_userid = null, $from, $is_published = null, $paginationStart, $limit)
    {
        //$db = $this->dbConnect();
		try
		{
			$req =('
						SELECT id, title, author, lede, image, content, is_published, DATE_FORMAT(update_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS update_date_fr 
						FROM posts '
						);

						if ( isset($post_userid) && ($post_userid > 0) && ( ! isset  ($from))) {
	
							$req.= ' WHERE user_id = "'.$post_userid.'" ORDER BY creation_date DESC ';
							$result = $db->query($req);
							
						}elseif( isset($is_published) && ($is_published != null)){

							$req.= ' WHERE is_published = '.$is_published.' ORDER BY update_date DESC LIMIT '.$paginationStart.', '.$limit;
							
							$result = $this->db->query($req);
						

						}
						else{
							$req.= ' ORDER BY update_date DESC ';
							$result = $this->db->query($req);
							
							
						}
		}
		catch (Exception $e)
		{
			echo 'Connexion �chou�e : ' . $e->getMessage();
		}					
			
		return $result;			
	}									


	/**
	 * Get one Post
	 * @params  string $postid and $ispublished = post is or not published
	 */

  	public function get($postId, $is_published)
    {
        //$db = $this->dbConnect();

		 
		if($is_published == '1'){			

			$req = $this->db->prepare('SELECT posts.id, title,lede, author, image, content, is_published, photo, DATE_FORMAT(posts.update_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS update_date_fr FROM posts  JOIN user ON posts.user_id = user.id WHERE posts.id = ? AND posts.is_published = ?');
			$req->execute(array($postId, $is_published));
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

	public function addPost( $post_userid,$post_title, $post_lede,$post_author,$post_content, $pimage_name,$is_published) 
		{
			$db = $this->dbConnect();
       
           
			$req = $db->prepare('INSERT INTO posts(user_id, title, lede, author, content,image, is_published, creation_date, update_date) VALUES( ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
			$resultat = $req->execute(array($post_userid,$post_title,$post_lede,$post_author,$post_content, $pimage_name,$is_published));
							
			return $resultat;
				//}
            
        }


		 public function updatePost($idpost, $data, $postimage = '') {
	
			$db = $this->dbConnect();

			try
		{
            $sql = ('UPDATE posts SET title = :title, 
													lede = :lede, 
													author = :author, 
													content = :content, 
													update_date= NOW() '
													);
			if($postimage != '')	{
				$sql.= ', image = :postimage ';
			}
			$sql.= ' WHERE id = :id'	;
			

            $req = $db->prepare($sql);
			
			if($postimage == '')	{
				
				$resultat = $req->execute(array(
					":id" => $idpost,
					":title" => $data["title"],
					":lede" => $data["lede"],
					":author" => $data["author"],
					":content" => $data["content"]
				));
			}else{
				$resultat = $req->execute(array(
					":id" => $idpost,
					":title" => $data["title"],
					":lede" => $data["lede"],
					":author" => $data["author"],
					":content" => $data["content"],
					":postimage"=>$postimage
				));
			}
		}
			catch (Exception $e)
		{
			echo 'Connexion �chou�e : ' . $e->getMessage();
		}	
			return $resultat;
        }


		/**
	 * ActivatePost
	 * @param  string $id and $ispublished = post is or not published
	 */

	public function publishPost($id, $ispublished)
	{
		$db = $this->dbConnect();
	 try
	{
		
		$sql = 'UPDATE posts SET is_published = "'.$ispublished.'"  WHERE id = '.$id;
		$resultat = $db->query($sql);
		
		
	}
	catch (Exception $e)
	{
		echo 'Connexion �chou�e : ' . $e->getMessage();
	}	
		return $resultat;
	}

	/**
	 * DELETE Post
	 * @param  string $userid and $postid
	 */

	public function deletePost($postId, $userid) {

		$db = $this->dbConnect();
		
		$query = 'DELETE FROM posts';
		if ($postId != null) {
			$query.= ' WHERE posts.id = ? ';
			$deleteposts = $db->prepare($query);
			$resultat = $deleteposts->execute(array($idpost));
			
		}elseif ($userid != null) {
			$query.= '  WHERE posts.user_id = ? ';
			$deleteposts = $db->prepare($query);
			$resultat = $deleteposts->execute(array($userid));
		}
		
		return $resultat;
	}

}