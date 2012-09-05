<?php
class Shownotes
{
	var $items = array();
	function Shownotes($str=NULL)
	{
		$charlist = array(32,9,10,13,0,11);
		$startchars = array(45, 196);
		if (!is_null($str))
		{
			$lines = explode("\n", $str);
			for ($i=0; $i < (count($lines)); $i++) {
				$ln = trim($lines[$i]);
				if (in_array(ord(substr($ln, 0, 1)), $startchars)){
					$ln = trim(substr($ln, 1));
					$subcounter += 1;
				} else { if(isset($subcounter)){ unset($subcounter); } }
				$time = substr($ln, 0, strpos($ln, " "));

				$text_end = FALSE;
				$tags = array();
				$urls = array();
				$buffer = '';
				for ($char=0; $char < strlen($ln); $char++) { 
					$buffer .= $ln[$char];
					if ($text_end === TRUE)
					{
						if (isset($tag))
						{
							if (in_array(ord($ln[$char]), $charlist) && ($type === 'tag')){ 
								$tags[] = $tag;
								unset($tag);
							}
							elseif (($ln[$char] == ">") && ($type === 'url')){
								$urls[] = $tag;
								unset($tag);
							}
							elseif ($char+1 === strlen($ln)) {
								if ($type === 'tag'){
									$tag .= $ln[$char];
									$tags[] = $tag;
									unset($tag);
								}
								elseif ($type === 'url'){
									$this->error[] = "Unexpected end of URL on line $i";
								}
							} else {
								$tag .= $ln[$char];
							}
						}
						elseif ($ln[$char] == "#"){ $tag = ''; $type = 'tag'; }
						elseif ($ln[$char] == "<"){ $tag = ''; $type = 'url'; }
						elseif (!in_array(ord($ln[$char]), $charlist)){ $text_end = FALSE; $text = trim($buffer); $tags = array(); $urls = array(); }
					}
					elseif (($ln[$char] == "#") && (in_array(ord($ln[$char-1]), $charlist)))
					{
						$text_end = TRUE;
						$tag = '';
						$type = 'tag';
					}
					elseif (($ln[$char] == "<") && (in_array(ord($ln[$char-1]), $charlist)))
					{
						$text_end = TRUE;
						$tag = '';
						$type = 'url';
					}
					elseif (($ln[$char] == '\\') && ($ln[$char+1] == "#"))
					{
						$buffer = substr($buffer, 0, -1);
					}
					else
					{
						$text = trim($buffer);
					}
				}
				if (isset($subcounter)){
					$subi = $i - $subcounter;
					if ($subcounter === 0){
						$this->items[$subi]->items = array();
					}
					$this->items[$subi]->items[$subcounter] = (object) array('time' => $time, 'text' => $text, 'tags'=>$tags, 'urls'=>$urls);
				} else {
					$this->items[$i] = (object) array('time' => $time, 'text' => $text, 'tags'=>$tags, 'urls'=>$urls);
				}
			}
		}
	}
}
?>