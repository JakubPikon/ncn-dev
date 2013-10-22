<?php
 
class TextHelper {
	public static function createSeoLink( $str ) {
		return strtolower(
			str_replace(' ', '-',
				preg_replace('/[ ]{2,}/', ' ',
					trim(preg_replace('/[^a-zA-Z0-9 ]+/', ' ',
						str_replace(array('&', ':', 'ä','ö','ü','Ä','Ö','Ü','ß'),
							array(' und ',' ', 'ae','oe','ue','Ae','Oe','Ue','ss'), $str))))));
	}

	public static function getUniqueSlug( $slug, $slugFromDb ){
		// find last index from the slug
		$numberIndex = strrpos( $slugFromDb, '-' );
		if ( $numberIndex > -1 ){
			$lastIndex = intval( substr( $slugFromDb, $numberIndex + 1 ) );
			return $slug . '-' . ($lastIndex+1);
		}
		return $slug . '-1';
	}


	public static function textLimit( $string, $length, $replacer = '...' ) {
	  	if( strlen( $string ) > $length ){
	    	return ( preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length) ) . $replacer;
	    }
	  	return $string;
	}

	public static function startsWith( $haystack, $needle ){
    	return !strncmp( $haystack, $needle, strlen( $needle ) );
	}

	public static function endsWith( $haystack, $needle ){
    	$length = strlen( $needle );
    	if ( $length == 0 ) {
        	return true;
    	}

    	return substr( $haystack, -$length ) === $needle;
	}

	public static function ArrayToCommaString ( array $array ){
		$result = '';
		for ( $i = 0, $k = count( $array ); $i < $k; $i++ ) {
			$result .= $array[$i];
			if ( $i + 1 < $k ) {
				$result .= ',';
			}
		}

		return $result;
	}

	public static function renderTemplate( $templateFile, $vars = array() ){
  		extract($vars);
  		ob_start();
  		require $templateFile;
  		$templateString = ob_get_contents();
  		ob_end_clean();

  		return $templateString;
	}

    public static function formatSizeUnits( $bytes ){
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576){
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024){
            $bytes = number_format($bytes / 1024, 2) . ' kB';
        }
        elseif ($bytes > 1){
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1){
            $bytes = $bytes . ' byte';
        }
        else{
            $bytes = '0 bytes';
        }

        return $bytes;
	}

	public static function renameType( $dbType ) {
		switch ($dbType) {
			case Taxonomy::ALBUM:
				return 'album';
			case Taxonomy::MEDIA:
				return 'img';
			default:
				return '';
		};
	}
};