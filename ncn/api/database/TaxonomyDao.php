<?php
 
class TaxonomyDao extends BaseDao {

	public function getTerm( $termId ){
		$sql = 'SELECT *FROM '.$this->tblPrefix.'terms
				WHERE term_id =:term_id';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'term_id', $termId, \PDO::PARAM_INT );
		$stmt->execute();

		return $stmt->fetchObject();
	}

	public function getTermTaxonomy( $termTaxonomyId, $taxonomy ){
		$sql = 'SELECT t.*, tt.term_taxonomy_id, tt.description
				FROM '.$this->tblPrefix.'terms as t
				JOIN '.$this->tblPrefix.'term_taxonomy as tt ON t.term_id = tt.term_id
				WHERE tt.term_taxonomy_id =:term_taxonomy_id AND tt.taxonomy = :taxonomy';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'term_taxonomy_id', $termTaxonomyId, \PDO::PARAM_INT );
		$stmt->bindParam ( 'taxonomy', $taxonomy, \PDO::PARAM_STR );
		$stmt->execute();

		return $stmt->fetchObject();
	}

	public function getListTermTaxonomyByTermRelation( $termTaxonomyId ){
		$sql = 'SELECT t.*, tt.term_taxonomy_id, tt.description, tt.parent
				FROM '.$this->tblPrefix.'term_relationships tr
				JOIN '.$this->tblPrefix.'term_taxonomy tt ON tt.term_taxonomy_id = tr.object_id
				JOIN '.$this->tblPrefix.'terms t ON t.term_id = tt.term_id
				WHERE tr.term_taxonomy_id = :termTaxonomyId
				ORDER BY tr.term_order';

		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT );
		$stmt->execute();

		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
	}

	public function getListTermTaxonomyByTermRelationAndTaxonomyName( $termTaxonomyName ){
		$sql = 'SELECT tr.object_id AS id
				FROM wp_term_relationships tr
				JOIN wp_terms t ON t.term_id = tr.term_taxonomy_id
				WHERE t.name = :termTaxonomyName
				ORDER BY tr.term_order';

		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'termTaxonomyName', $termTaxonomyName, \PDO::PARAM_STR );
		$stmt->execute();

		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
	}

	public function getListTermTaxonomyByParent( $parent, $taxonomy ){
		$sql = 'SELECT t.*, tt.term_taxonomy_id, tt.description, tt.parent
				FROM '.$this->tblPrefix.'terms as t
				JOIN '.$this->tblPrefix.'term_taxonomy as tt ON t.term_id = tt.term_id
				WHERE tt.taxonomy = :taxonomy';

		if ( $parent !== NULL ){
			$sql .= ' AND tt.parent =:parent';
		}

		$sql .= ' ORDER BY tt.count';

		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'taxonomy', $taxonomy, \PDO::PARAM_STR );
		if ( $parent !== NULL ){
			$stmt->bindParam ( 'parent', $parent, \PDO::PARAM_INT );
		}
		$stmt->execute();

		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
	}

	public function getTermBySlug( $slug ){
		$sql = 'SELECT *FROM '.$this->tblPrefix.'terms
				WHERE slug LIKE :slug
				ORDER BY slug DESC LIMIT 1';
		$slug = $slug .'%';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'slug', $slug, \PDO::PARAM_STR );
		$stmt->execute();

		return $stmt->fetchObject();
	}


	public function getBySlugAndTaxonomy( $slug, $taxonomy, $parent ){
		$sql = 'SELECT *FROM '.$this->tblPrefix.'terms t
				JOIN '.$this->tblPrefix.'term_taxonomy tt ON tt.term_id = t.term_id AND tt.taxonomy = :taxonomy AND tt.parent = :parent
				WHERE slug = :slug';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'slug', $slug, \PDO::PARAM_STR );
		$stmt->bindParam ( 'taxonomy', $taxonomy, \PDO::PARAM_STR );
		$stmt->bindParam ( 'parent', $parent, \PDO::PARAM_INT );
		$stmt->execute();

		return $stmt->fetchObject();
	}

	public function getByNameAndTaxonomy( $name, $taxonomy, $parent = null ){
		$sql = 'SELECT *FROM '.$this->tblPrefix.'terms t
				JOIN '.$this->tblPrefix.'term_taxonomy tt ON tt.term_id = t.term_id AND tt.taxonomy = :taxonomy
				WHERE t.name = :name';

		if( $parent !== null) {
			$sql .= ' AND tt.parent = :parent';
		}

		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam ( 'name', $name, \PDO::PARAM_STR );
		$stmt->bindParam ( 'taxonomy', $taxonomy, \PDO::PARAM_STR );

		if( $parent !== null) {
			$stmt->bindParam ( 'parent', $parent, \PDO::PARAM_INT );
		}

		$stmt->execute();

		return $stmt->fetchObject();
	}

	public function getTermRelationship ( $objectId, $termTaxonomyId ){
		$sql = 'SELECT tr.*
				FROM '.$this->tblPrefix.'term_relationships tr
				WHERE tr.object_id = :objectId AND tr.term_taxonomy_id = :termTaxonomyId';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam ('objectId', $objectId, \PDO::PARAM_INT);
		$stmt->bindParam ('termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
	}


	public function getListTermRelationship ( $termTaxonomyId ){
		$sql = 'SELECT tr.*
				FROM '.$this->tblPrefix.'term_relationships tr
				WHERE tr.term_taxonomy_id = :termTaxonomyId';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam ('termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT);
		$stmt->execute();

		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
	}


	public function addTerm( $dto ){
		$sql = 'INSERT INTO '.$this->tblPrefix.'terms (name, slug) VALUES (:name, :slug)';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam( 'name', $dto->name, \PDO::PARAM_STR );
		$stmt->bindParam( 'slug', $dto->slug, \PDO::PARAM_STR );
		$stmt->execute();

		return $this->getTerm( $this->db->lastInsertId() );
	}

	public function addTermTaxonomy( $dto ){
		$sql = 'INSERT INTO '.$this->tblPrefix.'term_taxonomy (term_id, taxonomy, description, parent) VALUES (:term_id, :taxonomy, :description, :parent)';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam('term_id', $dto->term_id, \PDO::PARAM_INT);
		$stmt->bindParam('parent', $dto->parent, \PDO::PARAM_INT);
		$stmt->bindParam('taxonomy', $dto->taxonomy, \PDO::PARAM_STR);
		$stmt->bindParam('description', $dto->description, \PDO::PARAM_STR);
		$stmt->execute();

		return $this->getTermTaxonomy( $this->db->lastInsertId(), $dto->taxonomy );
	}

	public function addTermRelationship( $dto ){
		$sql = 'INSERT INTO '.$this->tblPrefix.'term_relationships (object_id, term_taxonomy_id, term_order) VALUES (:object_id, :term_taxonomy_id, :term_order)';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam('object_id', $dto->object_id, \PDO::PARAM_INT);
		$stmt->bindParam('term_taxonomy_id', $dto->term_taxonomy_id, \PDO::PARAM_INT);
		$stmt->bindParam('term_order', $dto->term_order, \PDO::PARAM_INT);
		$stmt->execute();
	}

	public function updateTermRelationshipOrder( $dto, $addIfNotExists = true ){
		if ($addIfNotExists) {
			$sql = 'REPLACE INTO '.$this->tblPrefix.'term_relationships (object_id, term_taxonomy_id, term_order) VALUES (:object_id, :term_taxonomy_id, :term_order)';
			$stmt = $this->db->prepare( $sql );
			$stmt->bindParam('object_id', $dto->object_id, \PDO::PARAM_INT);
			$stmt->bindParam('term_taxonomy_id', $dto->term_taxonomy_id, \PDO::PARAM_INT);
			$stmt->bindParam('term_order', $dto->term_order, \PDO::PARAM_INT);
			$stmt->execute();
		} else {
			$sql = 'UPDATE '.$this->tblPrefix.'term_relationships SET term_order =:term_order WHERE object_id =:object_id AND term_taxonomy_id =:term_taxonomy_id';
			$stmt = $this->db->prepare( $sql );
			$stmt->bindParam('term_order', $dto->term_order, \PDO::PARAM_INT);
			$stmt->bindParam('object_id', $dto->object_id, \PDO::PARAM_INT);
			$stmt->bindParam('term_taxonomy_id', $dto->term_taxonomy_id, \PDO::PARAM_INT);
			$stmt->execute();
		}
	}

	public function updateTerm( $dto ){
		print_r($dto);
		$sql = 'UPDATE '.$this->tblPrefix.'terms SET name=:name, slug=:slug WHERE term_id =:term_id';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam('term_id', $dto->term_id, \PDO::PARAM_INT);
		$stmt->bindParam('name', $dto->name, \PDO::PARAM_STR);
		$stmt->bindParam('slug', $dto->slug, \PDO::PARAM_STR);
		$stmt->execute();
	}

	public function updateTermTaxonomy( $dto ){
		$sql = 'UPDATE '.$this->tblPrefix.'term_taxonomy SET description=:description WHERE term_taxonomy_id =:term_taxonomy_id';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam('term_taxonomy_id', $dto->term_taxonomy_id, \PDO::PARAM_INT);
		$stmt->bindParam('description', $dto->description, \PDO::PARAM_STR);
		$stmt->execute();
	}

	public function updateTermTaxonomyOrder( $dto ){
		$sql = 'UPDATE '.$this->tblPrefix.'term_taxonomy SET count =:count WHERE term_taxonomy_id =:term_taxonomy_id';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam('count', $dto->count, \PDO::PARAM_INT);
		$stmt->bindParam('term_taxonomy_id', $dto->term_taxonomy_id, \PDO::PARAM_INT);
		$stmt->execute();
	}

	public function deleteTermRelationship( $objectId, $termTaxonomyId ){
		$sql = 'DELETE FROM '.$this->tblPrefix.'term_relationships
				WHERE object_id = :objectId AND term_taxonomy_id = :termTaxonomyId';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam( 'objectId', $objectId, \PDO::PARAM_INT );
		$stmt->bindParam( 'termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT );
		$stmt->execute();
	}

	public function deleteTermRelationshipByTermTaxonomyId( $termTaxonomyId ){
		$sql = 'DELETE FROM '.$this->tblPrefix.'term_relationships
				WHERE term_taxonomy_id = :termTaxonomyId';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam( 'termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT );
		$stmt->execute();
	}

	public function deleteTermTaxonomy( $termTaxonomyId ){
		$sql = 'DELETE FROM '.$this->tblPrefix.'term_taxonomy
				WHERE term_taxonomy_id = :termTaxonomyId';
		$stmt = $this->db->prepare($sql);
		$stmt->bindParam( 'termTaxonomyId', $termTaxonomyId, \PDO::PARAM_INT );
		$stmt->execute();
	}


};