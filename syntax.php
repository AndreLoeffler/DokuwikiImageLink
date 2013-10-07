<?php
/**
 * Plugin googlecal: Inserts an Google Calendar iframe
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andre Löffler <confirm@andre-loeffler.net>
 * @seealso    (http://www.dokuwiki.org/plugin:iframe)
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_imagelink extends DokuWiki_Syntax_Plugin {

    function getType() { return 'substition'; }
    
    function getPType(){ return 'block'; }
    
    function getSort() { return 319; }
    
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('{{imlin>[^}]*?}}', $mode, 'plugin_imagelink');
    }

    function handle($match, $state, $pos, &$handler){        
        if(preg_match('/{{imlin>(.*)/', $match)) {             // Hook for future features

        	$match = html_entity_decode(substr($match, 8, -2));
        	
        	// Split on pipes, $disp is new and optional
        	@list($url, $img, $capt, $clear) = explode('|',$match,4);
        	$matches = array();

        	$cle = '';
        	if ($clear != '') {
        		$cle = '<div style="clear:both;"></div>';
        	}
        	
            return array('wiki', trim($url), trim($img), trim($capt), $cle);
        } else {
            return array('error', $this->getLang("gcal_Bad_iFrame"));  // this is an error
        } // matched {{conf>...
    }

    function render($mode, &$renderer, $data) {
        list($style, $url, $img, $capt, $cle) = $data;
        
        if($mode == 'xhtml'){
            // Two styles: wiki and error
            switch($style) {
                case 'wiki':
                	$renderer->doc .= "<div style='width: 106px; margin-right: 10px; float: left;'><a href='_media/".$url."'><img src='_media/".$img."' style ='width: 106px; height: 150px;'><div style='clear: both; text-align: center;'>".$capt."</div></a></div>".$cle;
                						
                    break;
                case 'error':
                    $renderer->doc .= "<div class='error'>$url</div>";
                    break;
                default:
                    $renderer->doc .= "<div class='error'>" . $this->getLang('gcal_Invalid_mode') . "</div>";
                    break;
            }
            return true;
        }
        return false;
    }
}
