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

    public function getPosts()
    {
        $db = $this->dbConnect();
        $req = $db->query('SELECT id, title, author, lede, image, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts ORDER BY creation_date DESC ');

        return $req;
    }

    public function getPost($postId)
    {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT id, title,lede, author, image, content, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin%ss\') AS creation_date_fr FROM posts WHERE id = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

//  ADD Post **************************************************

	public function addPost( $post_userid,$post_title, $post_lede,$post_author,$post_content, $pimage_name,$is_enabled) 
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
			$req = $db->prepare('INSERT INTO posts(user_id, title, lede, author, content,image, is_enabled, creation_date, update_date) VALUES( ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
			$resultat = $req->execute(array($post_userid,$post_title,$post_lede,$post_author,$post_content, $pimage_name,$is_enabled));
							
			return $resultat;
				//}
            
        }


		 public function updatePost($idpost, $data) {
	
			$db = $this->dbConnect();
            $sql = "UPDATE posts SET title = :title, lede = :lede, author = :author, content = :content, update_date= NOW() WHERE id = :id";
			

            $req = $db->prepare($sql);
    
            $resultat = $req->execute(array(
                ":id" => $idpost,
                ":title" => $data["title"],
				":lede" => $data["lede"],
                ":author" => $data["author"],
                ":content" => $data["content"]
            ));
			return $resultat;
        }


		public function deletePost($idpost) {

            $db = $this->dbConnect();
			$sql = 'DELETE FROM posts WHERE id = :idpost LIMIT 1;';
			$req = $db->prepare($sql);
            //$obj = $this->db->prepare($sql);
    
            $resultat = $req->execute(array(
                ':idpost' => $idpost
            ));
    
            return $resultat;
        }

}