<?php

namespace OC\PhpSymfony\Blog\Model;


require_once("model/Manager.php");

class PostManager extends Manager
{
		private $post_userid ;
		private $post_title;
		private $post_lede;
		private $post_content ;
		private $is_enabled ;

	  public function gettotalPosts( $ispublished = '1')
    {
			$db = $this->dbConnect();
			try
				{	
					$sql = $db->query('SELECT count(id) AS id FROM posts WHERE is_published = "'.$ispublished.'"')->fetchAll();
					$totalRecrods = $sql[0]['id'];
/*var_dump($totalRecrods);
exit;*/
					return $totalRecrods;
								
				}
			catch (Exception $e)
				{
					echo 'Connexion échouée : ' . $e->getMessage();
				}					
			//var_dump($result);
		//return $result;			
	}									

    public function getPosts($post_userid = null, $from, $is_published = null, $paginationStart, $limit)
    {
        $db = $this->dbConnect();
		try
		{
			$req =('
						SELECT id, title, author, lede, image, content, is_published, DATE_FORMAT(update_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS update_date_fr 
						FROM posts '
						);

						if ( isset($post_userid) && ($post_userid > 0) && ( ! isset  ($from))) {
	
							$req.= ' WHERE user_id = "'.$post_userid.'" ORDER BY creation_date DESC ';
							$result = $db->query($req);
							/* $req.= ;
							$posts = $db->prepare($req);
							$result = $posts->execute(array(':post_userid'=>$post_userid));*/
							
							//$result = $posts->fetch();
							//var_dump($result);
							//exit;
						}elseif( isset($is_published) && ($is_published != null)){

							
							//$req.= ' ORDER BY creation_date DESC ';
							$req.= ' WHERE is_published = '.$is_published.' ORDER BY update_date DESC LIMIT '.$paginationStart.', '.$limit;
							//$req.= ' WHERE is_published = ? ';
							$result = $db->query($req);
							//$result = $result->execute();
							/*$resultat = $req->execute(array(
								":is_published" => $is_published,
								":paginationStart" => $paginationStart,
								":limit" => $limit
								));*/
							//return $req;	

						}
						else{
							$req.= ' ORDER BY update_date DESC ';
							$result = $db->query($req);
							
							//return $req;	

						}/**/
		}
		catch (Exception $e)
		{
			echo 'Connexion échouée : ' . $e->getMessage();
		}					
			//var_dump($result);
		return $result;			
	}									

  public function getPost($postId, $is_published)
    {
        $db = $this->dbConnect();

		 
		if($is_published == '1'){			

			$req = $db->prepare('SELECT posts.id, title,lede, author, image, content, is_published, photo, DATE_FORMAT(posts.update_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS update_date_fr FROM posts  JOIN user ON posts.user_id = user.id WHERE posts.id = ? AND posts.is_published = ?');
			$req->execute(array($postId, $is_published));
		}else{
			$req = $db->prepare('SELECT id, title,lede, author, image, content, is_published, DATE_FORMAT(creation_date, \'%d/%m/%Y &agrave; %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ? ');
			$req->execute(array($postId));
		}
        
        $post = $req->fetch();

        return $post;
    }

//  ADD Post **************************************************

	public function addPost( $post_userid,$post_title, $post_lede,$post_author,$post_content, $pimage_name,$is_published) 
		{
			$db = $this->dbConnect();
       
            /*
            $req = $db->prepare('INSERT INTO posts(user_id, title, lede, content, is_enabled, creation_date, update_date) VALUES (:user_id,:title, :lede, :content, :is_enabled, NOW(), NOW())');
							$resultat = $req->execute(array(
								":user_id" => $post_userid,
								":title" => $post_title,
								":lede" => $post_lede,
								":content" => $post_content,
								":is_enabled" => $is_enabled,
							));*/ 
		    //$sql = 'INSERT INTO posts(user_id, title, lede, content, is_enabled, creation_date, update_date) VALUES ( ?, ?, ? , ?, ?, NOW(), NOW())';
				//if($db){
			$req = $db->prepare('INSERT INTO posts(user_id, title, lede, author, content,image, is_published, creation_date, update_date) VALUES( ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
			$resultat = $req->execute(array($post_userid,$post_title,$post_lede,$post_author,$post_content, $pimage_name,$is_published));
							
			return $resultat;
				//}
            
        }


		 public function updatePost($idpost, $data, $postimage = '') {
	
			$db = $this->dbConnect();
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
		
		//echo $sql.'  '.$id;
		//exit;
		
		/*$req = $db->prepare($sql);

        $resultat = $req->execute(array('id' => $postId)); */
	}
	catch (Exception $e)
	{
		echo 'Connexion échouée : ' . $e->getMessage();
	}	
		return $resultat;
	}

	

		public function deletePost($postId, $userid) {

            $db = $this->dbConnect();
			//$sql = 'DELETE FROM posts WHERE id = :idpost LIMIT 1;';

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