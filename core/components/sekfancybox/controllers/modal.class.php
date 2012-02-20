<?php
 /**
 * sekFancyBox
 *
 * Copyright 2012 by Stephen Smith <ssmith@seknetsolutions.com>
 *
 * sekFancyBox is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * sekFancyBox is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * sekFancyBox; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package sekfancybox
 */

class sekFancyBoxModalController extends sekFancyBoxController {
    /**
     * Initialize this controller, setting up default properties
     * @return void
     */
    public function initialize() {
		$loadjquery = $this->modx->getOption('sekfancybox.load_jquery');
		$this->setDefaultProperties(array(
            'modalwidth' => '400',
            'type' => 'inline',
            'linkname' => 'sekfancybox',
			'modalclass' => 'fancybox',
			'group' => '',
			'mousewheel' => '0',
			'buttonhelper' => '0',
			'thumbnailhelper' => '0',
			'customjs' => '',
			'customcss' => '',
			'loadjquery' => $loadjquery,
        ));
				
    }

    public function process() {
		// text that will appear within hyperlink tags
        $linktext = $this->getProperty('linktext','');
		
		// text that will appear in modal window
        $text = $this->getProperty('text','');
		
		// optional, header that will appear in modal window		
		$header = $this->getProperty('header','');
		
		// optional, title of modal box		
        $title = $this->getProperty('title','');
		
		// optional, name of modal box, if not set defaults to 'sekfancybox' will not work well if multiple snippet calls on same page.
        $link = $this->getProperty('link');
		
		// optional, width of modal box, if not set defaults to '400'
        $width = $this->getProperty('modalwidth');
		
		// optional, text that will appear in modal window
        $type = $this->getProperty('type','');
		
		// optional, text that will appear in modal window
        $group = $this->getProperty('group','');
		
		// optional, set the modal class
		$modalclass = $this->getProperty('modalclass');
		
        $this->loadStartupScripts();
		
		$output = '';
		
		$title = ($title > '') ? '" title="'.$title.'"' : '';
		
        switch ($type) {
            case 'media':
				$group = ($group > '') ? '" data-fancybox-group="'.$group.'"' : '';	
				$output = '<a class="'.$modalclass.'"'.$group.' href="'.$link.'"'.$title.'>'.$linktext.'</a>';
                break;
            case 'document':
				$output = '<a class="'.$modalclass.' fancybox.ajax" href="'.$link.'"'.$title.'>'.$linktext.'</a>';
                break;
            case 'iframe':
				$output = '<a class="'.$modalclass.' fancybox.iframe" href="'.$link.'"'.$title.'>'.$linktext.'</a>';
                break;
            case 'inline':
            default:
				$output = '<div style="display: none;">';
				$output .= '<div id="'.$link.'" style="width:'.$width.'px;">';
				$output .= ($header > '') ? '<h3>'.$header.'</h3>' : '';
				$output .= '<p>'.$text.'</p>';
				$output .= '</div>';
				$output .= '</div>';
				$output = '<a class="'.$modalclass.'" href="#'.$link.'"'.$title.'>'.$linktext.'</a>' . $output;
                break;
        }
		
        return $output;
    }
		
    /**
     * Load any scripts for the top of the page
     * @return void
     */
    public function loadStartupScripts() {
		// optional, helpers
		$mousewheel = $this->getProperty('mousewheel');
		$buttonhelper = $this->getProperty('buttonhelper');
		$thumbnailhelper = $this->getProperty('thumbnailhelper');
		$customjs = $this->getProperty('customjs');
		$customcss = $this->getProperty('customcss');
		$loadjquery = $this->getProperty('loadjquery');

		if($loadjquery == '1'){
			$this->modx->regClientScript($this->sekfancybox->config['assetsUrl'].'lib/jquery-1.7.1.min.js');
		}
		
		$src = '';
		
		// Add mousewheel plugin (this is optional)
		if($mousewheel == '1'){
			$this->modx->regClientScript($this->sekfancybox->config['assetsUrl'].'lib/jquery.mousewheel-3.0.6.pack.js');
		}

		// Add fancyBox main JS and CSS files 
		$this->modx->regClientScript($this->sekfancybox->config['assetsUrl'].'source/jquery.fancybox.js');
		$this->modx->regClientCSS($this->sekfancybox->config['assetsUrl'].'source/jquery.fancybox.css');

		// Add Button helper (this is optional)
		if($buttonhelper == '1'){
			$this->modx->regClientCSS($this->sekfancybox->config['assetsUrl'].'source/helpers/jquery.fancybox-buttons.css?v=2.0.3');
			$this->modx->regClientScript($this->sekfancybox->config['assetsUrl'].'source/helpers/jquery.fancybox-buttons.js?v=2.0.3');
			$src = '{
				prevEffect		: \'none\',
				nextEffect		: \'none\',
				closeBtn		: false,
				helpers : {	
					title	: { 
						type : \'inside\' 
					}, 
					buttons	: {} 
				}
			}';
		}

		// Add Thumbnail helper (this is optional)
		if($thumbnailhelper == '1'){
			$this->modx->regClientCSS($this->sekfancybox->config['assetsUrl'].'source/helpers/jquery.fancybox-thumbs.css?v=2.0.3');
			$this->modx->regClientScript($this->sekfancybox->config['assetsUrl'].'source/helpers/jquery.fancybox-thumbs.js?v=2.0.3');
			$src = '{
				prevEffect	: \'none\',
				nextEffect	: \'none\',
				helpers	: {
					title	: {
						type: \'outside\'
					},
					overlay	: {
						opacity : 0.8,
						css : {
							\'background-color\' : \'#000\'
						}
					},
					thumbs	: {
						width	: 50,
						height	: 50
					}
				}
			}';
		}

		// optional, width of modal box, if not set defaults to '400'
        $type = $this->getProperty('type','');
		
		if($customcss > ''){
			$this->modx->regClientCSS($customcss);
		}

		if($customjs > ''){
			$src = $customjs;
		}else{
			$src = '<script type="text/javascript">
						$(document).ready(function() {
							$(\'.'.$this->getProperty('modalclass').'\').fancybox('.$src.');
						});
					</script>';
        }

		$this->modx->regClientScript($src);
    }

}
return 'sekFancyBoxModalController';