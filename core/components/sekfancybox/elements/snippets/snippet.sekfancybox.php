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

$sekfancybox = $modx->getService('sekfancybox','sekFancyBox',$modx->getOption('sekfancybox.core_path',null,$modx->getOption('core_path').'components/sekfancybox/').'model/sekfancybox/',$scriptProperties);
if (!($sekfancybox instanceof sekFancyBox)) return '';

$controller = $sekfancybox->loadController('Modal');
$output = $controller->run($scriptProperties);

return $output;
