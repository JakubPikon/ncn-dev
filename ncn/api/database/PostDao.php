<?php
 
class PostDao extends BaseDao {

	public function getListByTermTaxonomyId( $termTaxonomyId, $postType ){
		$query = 'SELECT p.ID as id, p.post_title, p.post_content, p.guid, pm.meta_value, tr.term_taxonomy_id
				  FROM ' . $this->tblPrefix . 'posts p
				  JOIN ' . $this->tblPrefix . 'term_relationships tr ON tr.term_taxonomy_id = :termTaxonomyId AND tr.object_id = p.ID
				  JOIN ' . $this->tblPrefix . 'postmeta pm ON pm.post_id = p.ID AND meta_key = \'_wp_attachment_metadata\'
				  WHERE post_type=:post_type
				  ORDER BY tr.term_order';

		$stmt = $this->db->prepare($query);
		$stmt->bindParam ('termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT);
		$stmt->bindParam ('post_type', $postType, \PDO::PARAM_STR);
		$stmt->execute();

		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
	}

	public function getAll( $postType, $parentId ){
		$query = 'SELECT ID as id, post_title, post_content, post_content_filtered, post_excerpt, post_parent, menu_order
				  FROM ' . $this->tblPrefix . 'posts
				  WHERE post_status = \'publish\' and post_type=:post_type';

		if ($parentId != NULL){
			$query .= ' and post_parent=:post_parent';
		}

		$stmt = $this->db->prepare($query);
		$stmt->bindParam ('post_type', $postType, \PDO::PARAM_STR);
		if ($parentId != NULL){
			$stmt->bindParam ('post_parent', $parentId, \PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function get( $id, $postType ){
		$sql = 'SELECT ID as id, post_title, post_content
				FROM '.$this->tblPrefix.'posts
				WHERE ID=:id AND post_type=:post_type';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam( 'id', $id, \PDO::PARAM_INT );
		$stmt->bindParam( 'post_type', $postType, \PDO::PARAM_STR );
		$stmt->execute();

		return $stmt->fetchObject();
	}

	public function getByIdAndType( $id, $type ){
		$sql = 'SELECT ID as id, post_title, post_content, post_content_filtered, post_excerpt, post_parent, menu_order
				FROM '.$this->tblPrefix.'posts
				WHERE ID=:id AND post_type = :type';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam( 'id', $id, \PDO::PARAM_INT );
		$stmt->bindParam( 'type', $type, \PDO::PARAM_STR );
		$stmt->execute();

		return $stmt->fetchObject();
	}

	public function getMeta( $id ) {
		$sql = 'SELECT meta_value as value FROM wp_postmeta
				WHERE post_id = :id';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam( 'id', $id, \PDO::PARAM_INT );
		$stmt->execute();
		return $stmt->fetchObject();
	}

	public function getAllMeta(){
		$sql = 'SELECT  post_id, meta_value as value
				FROM wp_postmeta
				WHERE meta_key = "_wp_attachment_metadata"';
		$stmt = $this->db->prepare( $sql );
		$stmt->execute();
		return $stmt->fetchObject();
	}

	public function getImages() {
		$sql = 'SELECT p.ID, p.post_title, pm.meta_key, pm.meta_value FROM wp_posts as p
				JOIN wp_postmeta pm
				ON pm.post_id = p.ID
				WHERE p.post_type = "attachment" AND pm.meta_key=\'_wp_attachment_metadata\'';
		$stmt = $this->db->prepare( $sql );
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function update ( $post ){
		$sql = 'UPDATE '.$this->tblPrefix.'posts
				SET post_title=:post_title, post_content=:post_content, post_modified=now(), post_modified_gmt=now()
				WHERE ID=:id';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam('id', $post->id, \PDO::PARAM_INT);
		$stmt->bindParam('post_title', $post->post_title, \PDO::PARAM_STR);
		$stmt->bindParam('post_content', $post->post_content, \PDO::PARAM_STR);
		$stmt->execute();

		return $post;
	}
};