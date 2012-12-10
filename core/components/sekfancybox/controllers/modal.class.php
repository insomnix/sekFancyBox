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
            'link' => '',
			'modalclass' => 'fancybox',
			'group' => '',
			'mousewheel' => '0',
			'buttonhelper' => '0',
			'thumbnailhelper' => '0',
			'customjs' => '',
			'customcss' => '',
			'loadjquery' => $loadjquery
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
		
        $scriptReturned = $this->loadStartupScripts();
		
		$output = '';
		
		$title = ($title > '') ? '" title="'.$title.'"' : '';
		
        switch ($type) {
            case 'jcode':
                if($link>''){
                    $output = '$("#'.$link.'").fancybox('.$scriptReturned.');';
                }else{
                    $output = '$.fancybox(\''.$text.'\''.($scriptReturned>''?','.$scriptReturned:'').');';
                }
                break;
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
                $link = $link>''?$link:'sekfancybox';
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
        // config settings
        $assetsUrl = $this->sekfancybox->config['assetsUrl'];

		// optional, helpers
		$mousewheel = $this->getProperty('mousewheel');
        $buttonhelper = $this->getProperty('buttonhelper');
        $mediahelper = $this->getProperty('mediahelper');
		$thumbnailhelper = $this->getProperty('thumbnailhelper');

        // custom options
		$customjs = $this->getProperty('customjs');
        $customcss = $this->getProperty('customcss');
        $custombuttonscss = $this->getProperty('custombuttonscss');
        $customthumbnailcss = $this->getProperty('customthumbnailcss');
		$loadjquery = $this->getProperty('loadjquery');

        // load jquery if the system setting or option says to load it
        $loadjquery = ($loadjquery>'')?$loadjquery:$this->modx->getOption('sekfancybox.load_jquery',null,1);
        if($loadjquery == 1){
            if($this->modx->getOption('sekfancybox.load_jquery',null,1) == 1){
                $this->modx->regClientStartupScript($assetsUrl.'lib/jquery-1.8.3.min.js');
            }else{
                $this->modx->regClientScript($assetsUrl.'lib/jquery-1.8.3.min.js');
            }
        }
		
		// Add mousewheel plugin (this is optional)
		if($mousewheel == '1'){
			$this->modx->regClientScript($assetsUrl.'lib/jquery.mousewheel-3.0.6.pack.js');
		}

		// Add fancyBox main JS file
		$this->modx->regClientScript($assetsUrl.'source/jquery.fancybox.pack.js?v=2.1.3');

        // Add fancyBox css or custom css file
        $customcss = ($customcss>'')?$customcss:($this->modx->getOption('sekfancybox.custom_css')>'')?$this->modx->getOption('sekfancybox.custom_css'):$assetsUrl.'source/jquery.fancybox.css?v=2.1.3';
        $this->modx->regClientCSS($customcss);

        // Add Button helper (this is optional)
        if($buttonhelper == '1'){
            $custombuttonscss = ($custombuttonscss>'')?$custombuttonscss:($this->modx->getOption('sekfancybox.custom_buttons_css')>'')?$this->modx->getOption('sekfancybox.custom_buttons_css'):$assetsUrl.'source/helpers/jquery.fancybox-buttons.css?v=1.0.5';
            $this->modx->regClientCSS($custombuttonscss);

            $this->modx->regClientScript($assetsUrl.'source/helpers/jquery.fancybox-buttons.js?v=1.0.5');
            if($this->getProperty('helpers')>''){
                $helperProperties = $this->modx->fromJSON($this->getProperty('helpers'));
                if(!array_key_exists('buttons', $helperProperties)) {
                    $itemOptions[] = 'buttons:{}';
                }
            }else{
                $itemOptions[] = 'buttons:{}';
            }
        }

        // Add Media helper (this is optional)
        if($mediahelper == '1'){
            $this->modx->regClientScript($assetsUrl.'source/helpers/jquery.fancybox-media.js?v=1.0.5');
        }

        // Add Thumbnail helper (this is optional)
        if($thumbnailhelper == '1'){
            $customthumbnailcss = ($customthumbnailcss>'')?$customthumbnailcss:($this->modx->getOption('sekfancybox.custom_thumbs_css')>'')?$this->modx->getOption('sekfancybox.custom_thumbs_css'):$assetsUrl.'source/helpers/jquery.fancybox-thumbs.css?v=1.0.7';
            $this->modx->regClientCSS($customthumbnailcss);

            $this->modx->regClientScript($assetsUrl.'source/helpers/jquery.fancybox-thumbs.js?v=1.0.7');
            if($this->getProperty('helpers')>''){
                $helperProperties = $this->modx->fromJSON($this->getProperty('helpers'));
                if(!array_key_exists('thumbs', $helperProperties)) {
                    $itemOptions[] = 'thumbs:{}';
                }
            }else{
                $itemOptions[] = 'thumbs:{}';
            }
        }

        // build options for fancybox
        $jsOptions = array();
        foreach($this->getProperties() as $key=>$value){
            switch($key){
                case 'scrolling':
                case 'width':
                case 'height':
                case 'wrapCSS':
                case 'prevEffect':
                case 'nextEffect':
                case 'openEffect':
                case 'closeEffect':
                case 'openSpeed':
                case 'closeSpeed':
                case 'nextSpeed':
                case 'prevSpeed':
                case 'openEasing':
                case 'closeEasing':
                case 'nextEasing':
                case 'prevEasing':
                case 'openMethod':
                case 'closeMethod':
                case 'nextMethod':
                case 'prevMethod':
                case 'scrollOutside':
                    if(!(is_numeric($value) || $value=='null' || $value=='true' || $value=='false')){$value='\''.$value.'\'';}
                    $jsOptions[] = $key.':'.$value;
                    break;
                case 'padding':
                case 'margin':
                case 'minWidth':
                case 'minHeight':
                case 'maxWidth':
                case 'maxHeight':
                case 'autoWidth':
                case 'autoResize':
                case 'autoCenter':
                case 'fitToView':
                case 'aspectRatio':
                case 'topRatio':
                case 'leftRatio':
                case 'autoSize':
                case 'autoHeight':
                case 'arrows':
                case 'closeBtn':
                case 'closeClick':
                case 'nextClick':
                case 'mouseWheel':
                case 'autoPlay':
                case 'playSpeed':
                case 'preload':
                case 'modal':
                case 'loop':
                case 'openOpacity':
                case 'closeOpacity':
                    $jsOptions[] = $key.':'.$value;
                    break;
                case 'helpers':
                    foreach ($this->modx->fromJSON($value) as $hkey=>$hvalue) {
                        //$this->modx->log(modX::LOG_LEVEL_ERROR,'[sekFancyBox] : '.print_r($hvalue,false));
                        if(is_array($hvalue)){
                            foreach ($hvalue as $subkey=>$subvalue) {
                                if(is_array($subvalue)){
                                    foreach ($subvalue as $subbkey=>$subbvalue) {
                                        if(!(is_numeric($subbvalue) || $subbvalue=='null' || $subbvalue=='true' || $subbvalue=='false')){$subbvalue='\''.$subbvalue.'\'';}
                                        $subbItemOptions[] = $subbkey.':'.$subbvalue;
                                    }
                                    $subItemOptions[] = $subkey.':{'.implode(',',$subbItemOptions).'}';
                                    unset($subbItemOptions);
                                }else{
                                    if(!(is_numeric($subvalue) || $subvalue=='null' || $subvalue=='true' || $subvalue=='false')){$subvalue='\''.$subvalue.'\'';}
                                    $subItemOptions[] = $subkey.':'.$subvalue;
                                }
                            }
                            $itemOptions[] = $hkey.':{'.implode(',',$subItemOptions).'}';
                            unset($subItemOptions);
                        }else{
                            if(!(is_numeric($hvalue) || $hvalue=='null' || $hvalue=='true' || $hvalue=='false')){$hvalue='\''.$hvalue.'\'';}
                            $itemOptions[] = $hkey.':'.$hvalue;
                        }
                    }

                    $jsOptions[] = $key.':{'.implode(',',$itemOptions).'}';
                    unset($itemOptions);
                    break;
            }

        }
        // set js src
        $src = implode(',',$jsOptions);
        $return = '';

		if($customjs > ''){
            $this->modx->regClientScript($customjs);
		}elseif($this->getProperty('type') == 'jcode'){
            $return = ($src>''?'{'.$src.'}':'');
        }else{
			$src = '<script type="text/javascript">
						$(document).ready(function() {
							$(\'.'.$this->getProperty('modalclass').'\').fancybox('.($src>''?'{'.$src.'}':'').');
						});
					</script>';
            $this->modx->regClientScript($src);
        }

		return $return;
    }

}
return 'sekFancyBoxModalController';