<?php

/* item example

	{
		cid: 21,
		name: 'Unnamed21',
		file: {
			link: 'http://placehold.it/950x590',
			thumbLink: 'http://placehold.it/315x196&text=[950x590]',
			format: 'jpg',
			width: 950,
			height: 590,
			size: 125
		},
		details: {
			link: 'http://eplus-gruppe.de/realitaetscheck-ratschlaege-fuer-das-datenroaming/',
			description: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet, omnis, dicta, eum pariatur molestias magni laboriosam harum sunt deserunt eius provident voluptas optio.',
			tags: ['big','dinozaur','rex']
		}
	},
*/

 

class ShortcodeHandler implements ContextAware, Initializable {
	private $taxonomyDao;
	private $postDao;

	public function init($context) {
		$this->taxonomyDao = $context->load('dao:TaxonomyDao');
		$this->postDao = $context->load('dao:PostDao');
	}

	public function parseShortcodeContentsArg($c) {
		$contents = array(
			'albums' => array(),
			'channels' => array(),
			'tags' => array(),
		);

		// Parse $c
		$cArr = explode(',', $c);
		foreach ($cArr as $cItem) {
			// Album ID
			if (is_numeric($cItem)) {
				$contents['albums'][] = $cItem;
			}
			// Tag
			else if ($cItem[0] === '{') {
				$contents['tags'][] = $cItem;
			}
			// Channel
			else {
				$contents['channels'][] = $cItem;
			}
		}

		foreach ($contents['channels'] as $channelName) {
			$this->getAlbumsFromChannel($channelName, $contents['albums']);
		}

		foreach ($contents['tags'] as $tag) {
			$this->getAlbumsByTag($tag, $contents['albums']);
		}

		return $contents['albums'];
	}

	public function getAlbumsFromChannel($channelName, &$albumsArray) {
		$albumsInChannel = $this->taxonomyDao->getListTermTaxonomyByTermRelationAndTaxonomyName($channelName);

		if (is_array($albumsInChannel) && count($albumsInChannel) > 0) {
			foreach ($albumsInChannel as $a) {
				$albumsArray[] = $a['id'];
			}
		}
	}

	public function getAlbumsByTag($tag, &$albumsArray) {
		// $albumsByTag = array();

		// if (is_array($albumsByTag) && count($albumsByTag) > 0) {
		// 	foreach ($albumsByTag as $a) {
		// 		$albumsArray[] = $a['id'];
		// 	}
		// }
	}

	public function extractAlbumContents($albumId, &$itemsArray) {
		$mediaArray = $this->postDao->getListByTermTaxonomyId($albumId, Taxonomy::MEDIA);

		if (is_array($mediaArray) && count($mediaArray) > 0) {
			foreach ($mediaArray as $m) {
				$meta = unserialize($m['meta_value']);
				$fileUrl = MB_WP_CONTEXT.'/wp-content/uploads/'.$meta['file'];

				// Defaults if can't set later
				$fileFormat = substr($meta['file'], strrpos($meta['file'], '.') + 1);
				$thumbUrl = $fileUrl;

				// Try to read ext info
				if (isset($meta['sizes'])) {
					$anySize = reset($meta['sizes']); // gets first item of assotiative array
					$fileFormat = substr($anySize['mime-type'], strrpos($anySize['mime-type'], '/') + 1);

					if (isset($meta['thumbnail'])) {
						substr($fileUrl, 0, strrpos($meta['file'], '/') + 1).$meta['sizes']['thumbnail']['file'];
					}
				}

				$item = array(
					// also available
					// 'taxo_id' => $m['term_taxonomy_id'],
					// 'taxo_type' => MediaTypes::IMG,

					'cid' => $m['id'],
					'name' => $m['post_title'],
					'file' => array(
						'link' => $fileUrl,
						'thumbLink' => $thumbUrl,
						'format' => $fileFormat,
						'width' => $meta['width'],
						'height' => $meta['height'],
						'size' => filesize($_SERVER['DOCUMENT_ROOT'].$fileUrl),
					),
					'details' => array(
						// 'link' => '',
						'description' => $m['post_content'],
						'tags' => array(
							'big',
							'dinozaur',
							'rex',
						),
					),
				);

				$itemsArray[] = $item;
			}
		}
	}
};
